<?php

namespace App\Http\Business;

use App\Models\Sector;
use Illuminate\Database\Eloquent\Builder;
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
        return Sector::create($validParams);
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
     * Get all sectors in teams that the user is a member of.
     * @return Builder[]|Collection|Sector[]
     */
    public static function getMySectors(): Collection|array
    {
        return Sector::where('team_id', getPermissionsTeamId())->get();
    }
}
