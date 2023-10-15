<?php

namespace App\Http\Business;

use App\Models\Lot;
use App\Models\LotSector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
        BaseBusiness::hasPermissionTo('lot create');
        // Verify if the data is valid
        $validatedParams = Validator::validate($data, LotBusiness::rules, LotBusiness::messages);

        // Logic to create a new lot
        return Lot::create($validatedParams);
    }

    /**
     * Update a lot and return it.
     * @param  int  $id  - Lot ID.
     * @param  array  $data  - Lot data.
     * @return Lot - Lot updated.
     */
    public static function updateLot(int $id, array $data): Lot
    {
        BaseBusiness::hasPermissionTo('lot edit');
        $lot = Lot::find($id);
        $lot->update($data);
        return $lot;
    }

    /**
     * Delete a lot and return it.
     * @param  int  $id  - Lot ID.
     * @return Lot - Lot deleted.
     */
    public static function deleteLot(int $id): Lot
    {
        BaseBusiness::hasPermissionTo('lot delete');
        $lot = Lot::find($id);
        $lot->delete();
        return $lot;
    }

    /**
     * Get a lot by ID.
     * @param  int  $id  - Lot ID.
     * @return Lot - Lot found.
     * @throws UnauthorizedException - If the user does not have permission to view lots.
     */
    public static function getLotById(int $id): Lot
    {
        BaseBusiness::hasPermissionTo('lot list');
        return Lot::find($id);
    }

    /**
     * Get all lots.
     * @return array - Lots found.
     * @throws UnauthorizedException - If the user does not have permission to view lots.
     */
    public static function getAllLots(): array
    {
        BaseBusiness::hasPermissionTo('lot list');
        return Lot::all()->toArray();
    }

    /**
     * Attach a sector to a lot.
     * @param $lot  - Lot to attach the sector.
     * @param $sector  - Sector to be attached to the lot.
     * @return LotSector|Model - LotSector created.
     */
    public static function attachSector($lot, $sector): Model|LotSector
    {
        return LotSector::firstOrCreate([
            'lot_id' => $lot->id,
            'sector_id' => $sector->id
        ]);
    }
}
