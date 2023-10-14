<?php

namespace App\Http\Controllers\API;

use App\Http\Business\CouponBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CouponController extends Controller
{

    /**
     * Display a listing of the coupons.
     * @return JsonResponse - Coupons data.
     * @throws UnauthorizedException - If the user does not have permission to view coupons.
     */
    public function index(): JsonResponse
    {
        $coupons = CouponBusiness::getAllCoupons();
        return $this->sendSuccessResponse($coupons, 'Cupons recuperados com sucesso!');
    }

    /**
     * Display the specified coupon.
     * @param $id  - Coupon ID.
     * @return JsonResponse - Coupon data.
     * @throws UnauthorizedException - If the user does not have permission to view coupons.
     */
    public function show($id): JsonResponse
    {
        $coupon = CouponBusiness::getCouponById($id);
        return $this->sendSuccessResponse($coupon, 'Cupom recuperado com sucesso!');
    }

    /**
     * Store a newly created coupon.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to create coupons.
     */
    public function store(Request $request): JsonResponse
    {
        $coupon = CouponBusiness::createCoupon($request->all());
        return $this->sendSuccessResponse($coupon, 'Cupom criado com sucesso!');
    }

    /**
     * Update the specified coupon.
     * @param  Request  $request - Request data.
     * @param $id  - Coupon ID.
     * @return JsonResponse - Coupon data.
     * @throws UnauthorizedException - If the user does not have permission to update coupons.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $coupon = CouponBusiness::updateCoupon($id, $request->all());
        return $this->sendSuccessResponse($coupon, 'Cupom atualizado com sucesso!');
    }

    /**
     * Delete the specified coupon.
     * @param $id  - Coupon ID.
     * @return JsonResponse - Coupon data.
     * @throws UnauthorizedException - If the user does not have permission to delete coupons.
     */
    public function destroy($id): JsonResponse
    {
        $coupon = CouponBusiness::deleteCoupon($id);
        return $this->sendSuccessResponse($coupon, 'Cupom deletado com sucesso!');
    }
}
