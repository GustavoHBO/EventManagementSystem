<?php

namespace App\Http\Business;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketPrice;
use App\Models\TicketStatus;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
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
        $eventId = 12;
        if (isset($validatedData['coupon_code'])) {
            $coupon = Coupon::where('code', $validatedData['coupon_code'])->where('event_id', $eventId)->first();
            // Check if the coupon is usable.
            if (!CouponBusiness::isCouponUsable($coupon)) {
                throw ValidationException::withMessages(['coupon_code' => 'O cupom inválido ou expirado.']);
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
                'payment_method_id' => $validatedData['payment_method_id'],
                'status_id' => Payment::PENDING, // Status of the payment (1 = Pending, 2 = Paid, 3 = Canceled).
                'amount' => $orderTotal, // The amount of the payment.
            ]);
            $order = Order::create([
                'user_id' => Auth::user()->id,
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
                    if(TicketPrice::find($ticket['ticket_price_id'])->availableTickets() === 0){
                        throw ValidationException::withMessages(['ticket_price_id' => 'O ingresso não está disponível.']);
                    }
                    Ticket::create($ticket);
                }
            }
        }, $qtyRetryTransaction); // Retry the transaction 2 times if it fails.

        // Return the order.
        return $order;
    }
}
