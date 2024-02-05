<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePerfilRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:255',
                'string',
                Rule::unique('perfis')->ignore($this->perfil)
            ],
            'description' => [
                'required',
                'min:3',
                'max:255',
                'string'
            ]
        ];
    }
}
