<?php

namespace App\Http\Controllers;

use App\Http\Business\UserBusiness;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserBusiness $userBusiness;

    /**
     * Construtor da classe.
     * @param  UserBusiness  $userBusiness  - Instância da classe de negócio de usuário.
     */
    public function __construct(UserBusiness $userBusiness)
    {
        $this->userBusiness = $userBusiness;
    }

    /**
     * Retorna uma lista de usuários.
     * @return JsonResponse - Lista de usuários.
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        return $this->sendSuccessResponse(UserResource::collection($users));
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Create a new user
            $user = $this->userBusiness->createUser($request->all());

            return $this->sendSuccessResponse(new UserResource($user), 'User created successfully');
        } catch (Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Retorna o usuário com o id informado.
     * @param $id  - Id do usuário.
     * @return JsonResponse - Usuário encontrado.
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return $this->sendSuccessResponse(new UserResource($user));
    }

    /**
     * Atualiza os dados do usuário com o id informado.
     * @param  Request  $request  - Dados do usuário.
     * @param $id  - Id do usuário.
     * @return JsonResponse - Usuário atualizado.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$id,
            ]);

            $user = User::findOrFail($id);
            $this->userBusiness->updateUser($user, $validatedData);

            return $this->sendSuccessResponse(new UserResource($user), 'User updated successfully');
        } catch (Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Exclui o usuário com o id informado.
     * @param $id  - Id do usuário.
     * @return JsonResponse - Usuário excluído.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $this->userBusiness->deleteUser($user);

            return $this->sendSuccessResponse([], 'User deleted successfully');
        } catch (Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }
}
