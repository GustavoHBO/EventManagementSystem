<?php

namespace App\Http\Business;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Ticket;
use App\Models\TicketPrice;
use App\Models\TicketStatus;
use App\Notifications\OrderPlacedNotification;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Str;
use Throwable;
use Validator;

class OrderBusiness extends BaseBusiness
{
    const rulesCreateOrder = [
        'payment_method_id' => 'required|exists:payment_methods,id',
        'order_items' => 'required|array',
        'order_items.*.ticket_price_id' => 'required|exists:ticket_prices,id',
        'order_items.*.quantity' => 'required|integer|min:1',
        'coupon_code' => 'nullable|exists:coupons,code'
    ];

    const messagesCreateOrder = [
        'payment_method_id.required' => 'O campo payment_method_id é obrigatório.',
        'payment_method_id.exists' => 'O método de pagamento especificado não existe.',

        'order_items.required' => 'Pelo menos um item de pedido é obrigatório.',
        'order_items.array' => 'Os itens de pedido devem ser um array.',

        'order_items.*.ticket_price_id.required' => 'O campo ticket_price_id é obrigatório.',
        'order_items.*.ticket_price_id.exists' => 'O preço do ingresso especificado não existe.',

        'order_items.*.quantity.required' => 'O campo quantity é obrigatório.',
        'order_items.*.quantity.integer' => 'O campo quantity deve ser um número inteiro.',
        'order_items.*.quantity.min' => 'O campo quantity deve ser no mínimo 1.',

        'coupon_code.exists' => 'O cupom inválido ou expirado.'
    ];

    /**
     * Create a new order.
     * @param  array  $data  - Order data.
     * @throws ValidationException
     * @throws Throwable
     */
    public static function createOrder(array $data): Model|Order|null
    {
        // Validate the data.
        $validatedData = Validator::validate($data, self::rulesCreateOrder, self::messagesCreateOrder);

        $coupon = null;
        if (isset($validatedData['coupon_code'])) {
            foreach ($validatedData['order_items'] as $orderItem) {
                $ticketPrice = TicketPrice::find($orderItem['ticket_price_id']);
                $eventId = $ticketPrice->lot->event_id;
                $coupon = Coupon::where('code', $validatedData['coupon_code'])->where('event_id', $eventId)->first();
                // Check if the coupon is usable.
                if (!$coupon || !CouponBusiness::isCouponUsable($coupon)) {
                    throw ValidationException::withMessages(['coupon_code' => 'O cupom inválido ou expirado.']);
                }
            }

        }

        $order = null;
        $qtyRetryTransaction = 2;

        DB::transaction(function () use ($coupon, $validatedData, &$order, &$qtyRetryTransaction) {
            $orderTotal = 0;
            $ordemItem = [];
            $tickets = [];
            // Group the order items by ticket price id and sum the quantity of each group.
            $validatedData['order_items'] = Collection::make($validatedData['order_items'])->groupBy('ticket_price_id')->map(function (
                $item
            ) {
                return [
                    'ticket_price_id' => $item[0]['ticket_price_id'],
                    'quantity' => Collection::make($item)->sum('quantity')
                ];
            })->toArray();
            foreach ($validatedData['order_items'] as $orderItem) {
                $ticketPrice = TicketPrice::find($orderItem['ticket_price_id']);

                $ordemItemTotal = ($ticketPrice->price * $orderItem['quantity']);
                // Apply the coupon discount to the order item.
                if ($coupon) {
                    $ordemItemTotal -= $ordemItemTotal * $coupon->discount_percentage / 100;
                }
                for ($i = 0; $i < $orderItem['quantity']; $i++) {
                    $tickets[] = [
                        'order_item_id' => null,
                        'ticket_price_id' => $orderItem['ticket_price_id'],
                        'user_id' => Auth::user()->id, // User to whom the ticket belongs.
                        'status_id' => TicketStatus::PENDING_APPROVAL,
                    ];
                }
                $orderItemData = [
                    'order_id' => null,
                    'subtotal' => $ordemItemTotal,
                ];
                $ordemItem[] = $orderItemData;
                $orderTotal += $ordemItemTotal;
            }

            if (!isset($orderItemData) || !isset($tickets)) {
                throw ValidationException::withMessages(['order_items' => 'O campo order_items é obrigatório.']);
            }

            $payment = Payment::create([
                'uuid' => (string)Str::uuid(), // Unique identifier of the payment.
                'team_id' => getPermissionsTeamId(),
                'payment_method_id' => $validatedData['payment_method_id'],
                'status_id' => Payment::PENDING, // Status of the payment (1 = Pending, 2 = Paid, 3 = Canceled).
                'amount' => $orderTotal, // The amount of the payment.
            ]);
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'team_id' => getPermissionsTeamId(),
                'payment_id' => $payment->id,
                'status' => 1, // Status of the order (1 = Pending, 2 = Paid, 3 = Canceled).
                'total_amount' => $orderTotal, // The total amount of the order.
            ]);

            // Attach the coupon to the order.
            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'order_id' => $order->id
                ]);
            }

            // Attach the order items to the order.
            foreach ($ordemItem as $item) {
                $item['order_id'] = $order->id;
                $orderItem = OrderItem::create($item);
                foreach ($tickets as $ticket) {
                    $ticket['order_item_id'] = $orderItem->id;
                    // Check if the ticket are has available.
                    if (TicketPrice::find($ticket['ticket_price_id'])->availableTickets() === 0) {
                        $qtyRetryTransaction = 0;
                        throw ValidationException::withMessages(['ticket_price_id' => 'O ingresso não está disponível.']);
                    }
                    Ticket::create($ticket);
                }
            }
            $order->paymentData = $payment->checkout();
        }, $qtyRetryTransaction); // Retry the transaction 2 times if it fails.
        Auth::user()->notify(new OrderPlacedNotification($order));
        // Return the order.
        return $order;
    }

    /**
     * Get all orders.
     * @return Collection - Orders found.
     */
    public static function getAllOrders(): Collection
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('order list')) {
            if ($user->hasRole(['super admin', 'producer'])) {
                return Order::where('team_id', getPermissionsTeamId())->get();
            } elseif ($user->hasRole('client')) {
                return Order::where('team_id', getPermissionsTeamId())->where('user_id', $user->id)->get();
            }
        }
        throw new UnauthorizedException(403, 'Você não tem permissão para listar pedidos.');
    }

    /**
     * Get an order by ID.
     * @param  int  $id  - Order ID.
     * @return Order - Order found.
     * @throws ValidationException - If the order does not exist.
     */
    public static function getOrderById(int $id): Order
    {
        $order = Order::find($id);
        if ($order) {
            if (Auth::user()->hasPermissionTo('order list')) {
                return $order;
            }
            throw new UnauthorizedException(403, 'Você não tem permissão para visualizar pedidos.');
        }
        throw ValidationException::withMessages(['id' => 'O pedido especificado não existe.']);
    }

    /**
     * Get all orders of the authenticated user.
     * @return Collection - Orders found.
     */
    public static function getAllOrdersOfAuthenticatedUser(): Collection
    {
        return Order::where('user_id', Auth::user()->id)->get();
    }

    /**
     * Get an order of the authenticated user by ID.
     * @param  int  $id  - Order ID.
     * @return Order - Order found.
     * @throws ValidationException - If the order does not exist.
     */
    public static function getOrderOfUserById(int $id): Order
    {
        $order = Order::where('user_id', Auth::user()->id)->find($id);
        if ($order) {
            return $order;
        }
        throw ValidationException::withMessages(['id' => 'O pedido especificado não existe.']);
    }

    /**
     * Cancel an order.
     * @param  Order  $order  - Order to be canceled.
     * @return Order - Order canceled.
     * @throws ValidationException - If the order cannot be canceled.
     * @throws Throwable - If the transaction fails.
     */
    public static function cancelOrder(Order $order): Order
    {
        if ($order->payment->status_id === Payment::PENDING) {
            $order->payment->status_id = Payment::CANCELED;
            DB::transaction(function () use ($order) {
                $order->save();
                $order->payment->update([
                    'status_id' => Payment::CANCELED
                ]);
                foreach ($order->tickets as $ticket) {
                    $ticket->status_id = TicketStatus::CANCELED;
                    $ticket->save();
                }
            }, 3);
            return $order;
        } else {
            throw ValidationException::withMessages(['id' => 'O pedido não pode ser cancelado.']);
        }
    }

    /**
     * Update payment status of the order and the tickets.
     * @param  Order  $order  - Order to be updated.
     * @param  int  $paymentStatusId  - Payment status ID.
     * @return Order - Order updated.
     * @throws ValidationException - If the order cannot be updated.
     * @throws Throwable - If the transaction fails.
     */
    public static function updatePaymentStatus(Order $order, int $paymentStatusId): Order
    {
        $paymentStatus = PaymentStatus::findOrFail($paymentStatusId);
        if ($order->payment->status_id !== $paymentStatus->id) {
            DB::transaction(function () use ($paymentStatusId, $order) {
                $order->payment->update([
                    'status_id' => $paymentStatusId
                ]);
                foreach ($order->tickets as $ticket) {
                    $ticket->status_id = TicketStatus::getStatusIdByPaymentId($paymentStatusId);
                    $ticket->save();
                }
            }, 3);
            return $order;
        } else {
            throw ValidationException::withMessages(['id' => 'O pedido não pode ser atualizado.']);
        }
    }

}
