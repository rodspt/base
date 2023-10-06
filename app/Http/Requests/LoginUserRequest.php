<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginUserRequest extends FormRequest
{

    /**
     * Traduz o nome da coluna no banco para o nome de exibição no request
     */
    public function attributes()
    {
        return [
            'cpf' => 'cpf',
            'password' => 'senha',
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
            'cpf' => "required|min:11|max:11|unique:users,cpf",
            'password' => ['required', Password::defaults()],
        ];
    }
}
