<?php

namespace App\Http\Controllers\API;

use App\Http\Business\SectorBusiness;
use App\Http\Controllers\Controller;
use App\Models\Sector;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SectorController extends Controller
{
    /**
     * Get the sectors in teams that the user is a member of.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Get all sectors using the SectorBusiness class
        if (!Auth::user()->hasPermissionTo('view sectors')) {
            return $this->sendErrorResponse('Você não tem permissão para visualizar os setores!', 403);
        }
        $sectors = SectorBusiness::getMySectors();
        return $this->sendSuccessResponse($sectors, 'Setores recuperados com sucesso!');
    }

    /**
     * Create a new sector and return it.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if (!Auth::user()->hasPermissionTo('create sectors')) {
            return $this->sendErrorResponse('Você não tem permissão para criar setores!', 403);
        }

        try {
            $sector = SectorBusiness::createSector($request->all());
            return $this->sendSuccessResponse($sector, 'Setor criado com sucesso!');
        } catch (ValidationException $e) {
            return $this->sendErrorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update a sector using the data and return it.
     * @param  Request  $request
     * @param  Sector  $sector
     * @return JsonResponse
     */
    public function update(Request $request, Sector $sector): JsonResponse
    {
        if (!Auth::user()->hasPermissionTo('edit sectors')) {
            return $this->sendErrorResponse('Você não tem permissão para editar setores!', 403);
        }
        // Update the sector using the SectorBusiness class
        try {
            $sector = SectorBusiness::updateSector($sector, $request->all());
        } catch (ValidationException $e) {
            return $this->sendErrorResponse($e->getMessage(), 400);
        }

        return $this->sendSuccessResponse($sector, 'Setor atualizado com sucesso!');
    }

    /**
     * Delete the sector.
     * @param  Sector  $sector
     * @return JsonResponse
     */
    public function destroy(Sector $sector): JsonResponse
    {
        if (!Auth::user()->hasPermissionTo('delete sectors')) {
            return $this->sendErrorResponse('Você não tem permissão para deletar setores!', 403);
        }
        // Delete the sector using the SectorBusiness class
        $sector->delete();
        return $this->sendSuccessResponse(null, 'Setor deletado com sucesso!');
    }
}
