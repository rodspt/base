<?php

namespace App\Http\Requests;

use App\Models\Testtt as Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestttRequest extends FormRequest
{

  public function authorize(): bool
  {
     return true;
  }

   public function attributes()
    {
        return [
         'nome' => 'Nome',
   
        ];
    }

   public function rules(): array
   {

    return [
         'nome' => ['string','required','max:255',Rule::unique(Modal::class)->ignore($this?->testtt)],
      ];

   }

}