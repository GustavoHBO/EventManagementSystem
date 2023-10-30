<?php

namespace App\Http\Controllers;

use App\Http\Business\UserBusiness;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $users = User::where('id', Auth::user()->id)->get();
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

            return $this->sendSuccessResponse(new UserResource($user), 'User created successfully', 201);
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
        if (Auth::user()->id != $id) {
            return $this->sendErrorResponse('User not found');
        }
        $user = Auth::user();
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
            ],
            [
                'name.required' => 'Nome é obrigatório',
                'name.string' => 'Nome deve ser uma string',
                'name.max' => 'Nome deve ter no máximo 255 caracteres',
            ]);
            if(Auth::user()->id != $id) {
                return $this->sendErrorResponse('User not found');
            }
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
        return $this->sendErrorResponse('O usuário não pode ser excluído!', Response::HTTP_NOT_IMPLEMENTED);
    }
}
