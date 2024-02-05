<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{

    /**
     * Traduz o nome da coluna no banco para o nome de exibição no request
     */
    public function attributes()
    {
        return [
            'cpf' => 'cpf',
            'nome' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'perfil_id' => 'perfil',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'cpf' => ['required','cpf','min:11','max:11',Rule::unique('users')],
            'nome' => ['required', 'string','min:3', 'max:255'],
            'email' => ['required', 'email', 'min:5', 'max:255', Rule::unique('users')],
            'perfil_id' => ['required', 'integer',  Rule::exists('perfis','id')],
            'password' => ['required', 'confirmed', 'min:8','max:20', Password::defaults()],
        ];
    }
}
