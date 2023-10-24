<?php

namespace App\Http\Business;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CouponBusiness extends BaseBusiness
{

    const rules = [
        'event_id' => 'required|integer|exists:events,id',
        'code' => 'required|string|max:7|unique:coupons',
        'discount_percentage' => 'required|numeric|min:0|max:100',
        'max_usages' => 'integer|nullable|min:1',
        'expiration_date' => 'date|nullable',
        'user_id' => 'required|integer|exists:users,id',
    ];

    const messages = [
        'event_id.required' => 'O campo evento é obrigatório.',
        'event_id.integer' => 'O campo evento deve ser um número inteiro.',
        'event_id.exists' => 'O evento selecionado não existe.',

        'code.required' => 'O campo código é obrigatório.',
        'code.string' => 'O campo código deve ser uma string.',
        'code.max' => 'O campo código não pode ter mais de 7 caracteres.',
        'code.unique' => 'O código já está em uso.',

        'discount_percentage.required' => 'O campo percentual de desconto é obrigatório.',
        'discount_percentage.numeric' => 'O campo percentual de desconto deve ser um número.',
        'discount_percentage.min' => 'O percentual de desconto não pode ser menor que 0.',
        'discount_percentage.max' => 'O percentual de desconto não pode ser maior que 100.',

        'max_usages.integer' => 'O campo número máximo de usos deve ser um número inteiro.',
        'max_usages.min' => 'O número máximo de usos deve ser no mínimo 1.',

        'expiration_date.date' => 'O campo data de validade deve ser uma data válida.',

        'user_id.required' => 'O campo usuário é obrigatório.',
        'user_id.integer' => 'O campo usuário deve ser um número inteiro.',
        'user_id.exists' => 'O usuário selecionado não existe.',
    ];

    /**
     * Create a new Coupon instance and return it.
     * @param  array  $data  - Coupon data.
     * @return Coupon - Coupon created.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to update coupons.
     *
     */
    public static function createCoupon(array $data): Coupon
    {
        BaseBusiness::hasPermissionTo('create coupons');
        // Verify if the data is valid
        $validatedParams = Validator::validate($data, CouponBusiness::rules, CouponBusiness::messages);

        // Logic to create a new coupon
        return Coupon::create($validatedParams);
    }

    /**
     * Update a coupon and return it.
     * @param  int  $id  - Coupon ID.
     * @param  array  $data  - Coupon data.
     * @return Coupon - Coupon updated.
     * @throws UnauthorizedException - If the user does not have permission to update coupons.
     */
    public static function updateCoupon(int $id, array $data): Coupon
    {
        BaseBusiness::hasPermissionTo('update coupons');
        $coupon = Coupon::find($id);
        $coupon->update($data);
        return $coupon;
    }

    /**
     * Delete a coupon and return it.
     * @param  int  $id  - Coupon ID.
     * @return Coupon - Coupon deleted.
     * @throws UnauthorizedException - If the user does not have permission to delete coupons.
     */
    public static function deleteCoupon(int $id): Coupon
    {
        BaseBusiness::hasPermissionTo('delete coupons');
        $coupon = Coupon::find($id);
        $coupon->delete();
        return $coupon;
    }

    /**
     * Get a coupon by ID.
     * @param  int  $id  - Coupon ID.
     * @return Coupon - Coupon found.
     * @throws UnauthorizedException - If the user does not have permission to view coupons.
     */
    public static function getCouponById(int $id): Coupon
    {
        BaseBusiness::hasPermissionTo('view coupons');
        return Coupon::find($id);
    }

    /**
     * Get all coupons.
     * @return array - Coupons found.
     * @throws UnauthorizedException - If the user does not have permission to view coupons.
     */
    public static function getAllCoupons(): array
    {
        BaseBusiness::hasPermissionTo('view coupons');
        return Coupon::all()->toArray();
    }

    /**
     * Verify if the coupon is usable.
     * @param  Coupon  $coupon  - Coupon to be verified.
     * @return bool - True if the coupon is usable, false otherwise.
     */
    public static function isCouponUsable(Coupon $coupon): bool
    {
        // Check if the coupon is usable.
        return $coupon->expiration_date > now() && ($coupon->max_usages === null || $coupon->max_usages > CouponUsage::where('coupon_id',
                $coupon->id)->count());
    }
}
