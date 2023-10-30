<?php

namespace App\Http\Controllers;

use App\Http\Business\UserBusiness;
use App\Http\Resources\UserResource;
use App\Models\User;
use Crypt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Cria um novo usuário.
     * @param  Request  $request  - Dados do usuário.
     * @return JsonResponse - Usuário criado.
     * @throws ValidationException
     */
    public function signup(Request $request): JsonResponse
    {
        $user = UserBusiness::createUser($request->all());
        $user->assignRole('client');
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->sendSuccessResponse([
            'access_token' => $token,
            'user' => new UserResource($user),
        ], 'User created successfully', Response::HTTP_CREATED);
    }

    /**
     * Faz o login do usuário.
     * @param  Request  $request  - Dados do usuário.
     * @return JsonResponse - Usuário logado.
     */
    public function login(Request $request): JsonResponse
    {
        $paramsValidated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $paramsValidated['email'], 'password' => $paramsValidated['password']])) {
            $user = User::where('email', $paramsValidated['email'])->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            // Encrypt the team ID to be sent to the frontend.
            $additionalData = Crypt::encrypt(['team_id' => $user->myTeams()->first()->id ?? null]);
            return $this->sendSuccessResponse([
                'access_token' => $token,
                'additional_data' => $additionalData,
                'user' => $user->only(['id', 'name', 'email'])
            ]);
        }

        return $this->sendErrorResponse(['message' => 'Unauthorized'], 401);
    }

    /**
     * Faz o logout do usuário.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($request->input('all_devices')) {
            // Revogar todos os tokens do usuário (desconectar de todos os dispositivos)
            $user->tokens()->delete();
        } else {
            // Revogar apenas o token atual do usuário
            $request->user()->currentAccessToken()->delete();
        }
        return $this->sendSuccessResponse([], 'Token revoked');
    }

    /**
     * Change the team of the user.
     * @param  Request  $request  - Data to validate.
     * @return JsonResponse - User data.
     */
    public function changeTeam(Request $request): JsonResponse
    {
        $paramsValidated = $request->validate([
            'team_id' => 'required|exists:teams,id',
        ], [
                'team_id.required' => 'The team id field is required.',
                'team_id.exists' => 'The team id field must be a valid team id.',
            ]);
        $user = Auth::user();
        $team = $user->myTeams()->where('id', $paramsValidated['team_id'])->first();
        if (!$team) {
            throw new UnauthorizedException(404, 'O time informado não foi encontrado!');
        }
        $additionalData = Crypt::encrypt(['team_id' => $team->id]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->sendSuccessResponse([
            'access_token' => $token,
            'additional_data' => $additionalData,
            'user' => $user->only(['id', 'name', 'email'])
        ]);
    }
}
