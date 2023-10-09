<?php

namespace App\Http\Requests;

use App\Models\Testt as Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TesttRequest extends FormRequest
{

  public function authorize(): bool
  {
     return true;
  }

   public function attributes()
    {
        return [
         'nome' => 'Nome',
         'teste' => 'Teste',
         'status' => 'Status',
   
        ];
    }

   public function rules(): array
   {

    return [
         'nome' => ['string','required','min:3','max:255',Rule::unique(Modal::class)->ignore($this?->testte)],
         'teste' => ['integer','required'],
         'status' => ['boolean','required'],
      ];

   }

}