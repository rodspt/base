<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function userStatus($user)
    {
        if(!is_null($user->cpf_bloqueio))
        {
            return 'Bloqueado';
        }else{
            return !is_null($user->cpf_aprovacao) ? 'Ativo' : 'Pendente';
        }

    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cpf' => $this->cpf,
            'nome' => $this->nome,
            'email' => $this->email,
            'status' => $this->userStatus($this)
        ];
    }
}
