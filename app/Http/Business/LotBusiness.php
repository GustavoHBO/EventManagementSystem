<?php

namespace App\Http\Business;

use App\Models\Lot;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Type\Integer;
use Spatie\Permission\Exceptions\UnauthorizedException;

class LotBusiness
{
    const rules = [
        'event_id' => 'required|integer|exists:events,id',
        'name' => 'required|string|max:255',
        'available_tickets' => 'integer|nullable|min:0',
        'expiration_date' => 'date|nullable',
    ];

    const messages = [
        'event_id.required' => 'The event ID is required.',
        'event_id.integer' => 'The event ID must be an integer.',
        'event_id.exists' => 'The selected event does not exist in the database.',

        'name.required' => 'The name is required.',
        'name.string' => 'The name must be a string.',
        'name.max' => 'The name must not be greater than :max characters.',

        'available_tickets.integer' => 'The available tickets must be an integer.',
        'available_tickets.min' => 'The available tickets must be at least :min.',

        'expiration_date.date' => 'The expiration date must be a date.',
    ];

    /**
     * Create a new Lot instance and return it.
     * @param  array  $data  - Lot data.
     * @return Lot - Lot created.
     * @throws ValidationException - If the data is invalid.
     */
    public static function createLot(array $data): Lot
    {
        BaseBusiness::hasPermissionTo('create lots');
        // Verify if the data is valid
        $validatedParams = Validator::validate($data, LotBusiness::rules, LotBusiness::messages);

        // Logic to create a new lot
        return Lot::create($validatedParams);
    }

    /**
     * Update a lot and return it.
     * @param  Integer  $id - Lot ID.
     * @param  array  $data  - Lot data.
     * @return Lot - Lot updated.
     */
    public static function updateLot(Integer $id, array $data): Lot
    {
        BaseBusiness::hasPermissionTo('update lots');
        $lot = Lot::find($id);
        $lot->update($data);
        return $lot;
    }

    /**
     * Delete a lot and return it.
     * @param  Integer  $id - Lot ID.
     * @return Lot - Lot deleted.
     */
    public static function deleteLot(Integer $id): Lot
    {
        BaseBusiness::hasPermissionTo('delete lots');
        $lot = Lot::find($id);
        $lot->delete();
        return $lot;
    }

    /**
     * Get a lot by ID.
     * @param  Integer  $id - Lot ID.
     * @return Lot - Lot found.
     * @throws UnauthorizedException - If the user does not have permission to view lots.
     */
    public static function getLotById(Integer $id): Lot
    {
        BaseBusiness::hasPermissionTo('view lots');
        return Lot::find($id);
    }

    /**
     * Get all lots.
     * @return array - Lots found.
     * @throws UnauthorizedException - If the user does not have permission to view lots.
     */
    public static function getAllLots(): array
    {
        BaseBusiness::hasPermissionTo('view lots');
        return Lot::all()->toArray();
    }
}
