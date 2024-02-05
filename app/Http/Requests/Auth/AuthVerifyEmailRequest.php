<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthVerifyEmailRequest extends FormRequest
{
    /**
     * Traduz o nome da coluna no banco para o nome de exibição no request
     */
    public function attributes()
    {
        return [
            'id' => 'cpf',
            'hash' => 'hash'
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
            'cpf' => ['cpf','min:11','max:11',Rule::exists('users','cpf')],
            'hash' => ['string','min:15', 'max:500'],
            'expires' => ['string','min:10', 'max:10'],
            'signature' => ['string','min:50', 'max:100'],
        ];
    }
}
