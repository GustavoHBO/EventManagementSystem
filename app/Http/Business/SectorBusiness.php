<?php

namespace App\Http\Business;

use App\Models\Event;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SectorBusiness
{
    const rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
    ];

    const messages = [
        'name.required' => 'O campo nome é obrigatório.',
        'name.string' => 'O campo nome deve ser uma string.',
        'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',

        'capacity.required' => 'O campo capacidade é obrigatório.',
        'capacity.integer' => 'O campo capacidade deve ser um número inteiro.',
        'capacity.min' => 'O campo capacidade deve ser no mínimo 1.'
    ];

    /**
     * Create a new Sector instance and return it.
     * @throws ValidationException
     */
    public static function createSector($data): Sector
    {
        $validParams = Validator::validate($data, SectorBusiness::rules, SectorBusiness::messages);
        $sector = SectorBusiness::getMySectors();
        $sector = $sector->where('name', $validParams['name'])->first();
        if (!$sector) {
            return Sector::firstOrCreate($validParams);
        }
        return $sector;
    }

    /**
     * Get all sectors in teams that the user is a member of.
     * @return Collection - Sectors data.
     */
    public static function getMySectors(): Collection
    {
        $events = Event::where('team_id', getPermissionsTeamId())->with('lots.lotSectors.sector')->get();
        $sectors = new Collection();
        // Get all sectors from all events.
        foreach ($events as $event) {
            foreach ($event->lots as $lot) {
                foreach ($lot->lotSectors as $lotSector) {
                    $sectors->push( $lotSector->sector);
                }
            }
        }
        return $sectors->unique('id');
    }

    /**
     * Update a Sector using the data and return it.
     * @throws ValidationException
     */
    public static function updateSector($sector, $data): Sector
    {
        $validParams = Validator::validate($data, SectorBusiness::rules, SectorBusiness::messages);
        $sector->update($validParams);
        return $sector;
    }

    /**
     * Get a Sector by ID.
     * @param $id  - Sector ID.
     * @return Sector - Sector data.
     */
    public static function getSectorById($id): Sector
    {
        return SectorBusiness::getMySectors()->where('id', $id)->first();
    }
}
