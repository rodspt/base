<?php

namespace App\Http\Requests;

use App\Models\Teste as Modal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestRequest extends FormRequest
{
    /**
     * Traduz o nome da coluna no banco para o nome de exibição no request
     */
    public function attributes()
    {
        return [
            'name' => 'Nome',
            'description' => 'Descrição',
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:255', Rule::unique(Modal::class)->ignore($this?->teste)],
        ];
    }
}
