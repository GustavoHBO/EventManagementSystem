<?php

namespace App\Http\Controllers\API;

use App\Http\Business\LotBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LotController extends Controller
{

    /**
     * Display a listing of the lots.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Get all lots.
        $lots = LotBusiness::getAllLots();

        // Return the lots as a JSON response.
        return $this->sendSuccessResponse($lots, 'Lotes recuperados com sucesso!');
    }

    /**
     * Display the specified lot.
     * @param $id  - Lot ID.
     * @return JsonResponse - Lot data.
     */
    public function show($id): JsonResponse
    {
        // Get the lot by ID.
        $lot = LotBusiness::getLotById($id);

        // Return the lot as a JSON response.
        return $this->sendSuccessResponse($lot, 'Lote recuperado com sucesso!');
    }

    /**
     * Store a newly created lot.
     * @param Request  $request - Request data.
     * @throws ValidationException - If the data is invalid.
     */
    public function store(Request $request): JsonResponse
    {
        $lot = LotBusiness::createLot($request->all());
        return $this->sendSuccessResponse($lot, 'Lote criado com sucesso!');
    }

    /**
     * Update the specified lot.
     * @param  Request  $request - Request data.
     * @param $id  - Lot ID.
     * @return JsonResponse - Lot data.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $lot = LotBusiness::updateLot($id, $request->all());
        return $this->sendSuccessResponse($lot, 'Lote atualizado com sucesso!');
    }

    /**
     * Delete the specified lot.
     * @param $id  - Lot ID.
     * @return JsonResponse - Lot data.
     */
    public function destroy($id): JsonResponse
    {
        $lot = LotBusiness::deleteLot($id);
        return $this->sendSuccessResponse($lot, 'Lote deletado com sucesso!');
    }
}
