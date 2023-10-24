<?php

namespace App\Http\Controllers\API;

use App\Http\Business\TeamBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TeamController extends Controller
{
    /**
     * Display a listing of the teams.
     * @return JsonResponse - Teams data.
     */
    public function index(): JsonResponse
    {
        $teams = TeamBusiness::getMyTeams();
        return $this->sendSuccessResponse($teams, 'Times recuperados com sucesso!');
    }

    /**
     * Create a new Team instance and return it.
     * @param  Request  $request  - Request data.
     * @throws ValidationException - If the data is invalid.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(TeamBusiness::createTeam($request->all()), 201);
    }

    /**
     * Invite a user to a team.
     * @param  Request  $request  - Request data.
     * @throws ValidationException - If the data is invalid.
     */
    public function inviteUser(Request $request): JsonResponse
    {
        TeamBusiness::inviteUserToTeam($request->all());
        return $this->sendSuccessResponse(null, 'Usuário convidado com sucesso!');
    }

    /**
     * Remove user from team.
     * @param  Request  $request  - Request data.
     * @throws ValidationException - If the data is invalid.
     * @throws UnauthorizedException - If the user does not have permission to remove a user.
     */
    public function withdrawUser(Request $request): JsonResponse
    {
        TeamBusiness::removeUserFromTeam($request->all());
        return $this->sendSuccessResponse(null, 'Usuário removido com sucesso!');
    }
}
