<?php

namespace App\Http\Business;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserBusiness
{
    const rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|string|min:6',
        'phone' => 'nullable|string|max:20',
        'cpf_cnpj' => 'nullable|string|max:20',
    ];

    const customMessages = [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais de :max caracteres.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Informe um email válido.',
        'email.unique' => 'Este email já está em uso.',
        'password.required' => 'A senha é obrigatória.',
        'password.confirmed' => 'As senhas não coincidem.',
        'password.min' => 'A senha deve ter pelo menos :min caracteres.',
        'phone.max' => 'O número de telefone não pode ter mais de :max caracteres.',
        'cpf_cnpj.max' => 'O CPF/CNPJ não pode ter mais de :max caracteres.',
    ];

    /**
     * Create a new User instance and return it.
     * @param  array  $data  - User data.
     * @return Model|User - User created.
     * @throws ValidationException - If the data is invalid.
     */
    public static function createUser(array $data): User|Model
    {
        // Verify if the data is valid
        $validator = Validator::validate($data, UserBusiness::rules, UserBusiness::customMessages);

        // Logic to create a new user
        return User::create($validator);
    }

    /**
     * Update a user and return it.
     * @param  User  $user  - User to be updated.
     * @param  array  $data  - User data.
     * @return User - User updated.
     */
    public static function updateUser(User $user, array $data): User
    {
        // Lógica para atualizar os dados de um usuário
        $user->update($data);
        return $user;
    }

    /**
     * Exclui um usuário.
     * @param  User  $user  - Usuário a ser excluído.
     * @return void
     */
    public function deleteUser(User $user): void
    {
        // Lógica para excluir um usuário
        $user->delete();
    }
}
