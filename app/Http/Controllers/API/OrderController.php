<?php

namespace App\Http\Controllers\API;

use App\Http\Business\OrderBusiness;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendSuccessResponse(OrderBusiness::getAllOrders(), 'Pedidos recuperados com sucesso!');
    }

    /**
     * Display the specified order.
     * @param $id  - Order ID.
     * @return JsonResponse - Order data.
     */
    public function show($id): JsonResponse
    {
        return $this->sendSuccessResponse(OrderBusiness::getOrderById($id), 'Pedido recuperado com sucesso!');
    }

    /**
     * Store a newly created order.
     * @throws ValidationException - If the data is invalid.
     * @throws Throwable - If the transaction fails.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->sendSuccessResponse(OrderResource::make(OrderBusiness::createOrder($request->all())), 'Pedido criado com sucesso!');
    }
}
