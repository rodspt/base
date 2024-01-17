<?php

namespace App\Services;

use App\DTO\User\CreateUserDTO;
use App\Enum\PerfilEnum;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function __construct(protected User $user)
    {}

    public function findById(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function createNew(CreateUserDTO $dto, bool $approve = false): User
    {
        $data = (array) $dto;
        $data['password'] = bcrypt($data['password']);
        if($approve)
        {
            $payload = JWTAuth::parseToken()->getPayload();
            if(in_array($payload->get('perfil'), array(PerfilEnum::DEV(), PerfilEnum::ADMIN()), true)){
                $data['cpf_aprovacao'] = $payload->get('cpf');
                $data['aprovacao_at'] = Carbon::now();
            }
        }
        return $this->user->create($data);
    }

    public function gerenciarUser(User $user, string $tipo): User
    {
        $now = Carbon::now();
        $auth = auth()->user();
        if($tipo === 'A'){
            $user->update([
               'cpf_bloqueio' => null,
               'cpf_aprovacao' => $auth?->cpf,
               'aprovacao_at' => $now,
               'updated_at' => $now
            ]);
        }else{
            $user->update([
                'cpf_bloqueio' => $auth?->cpf,
                'updated_at' => $now
            ]);
        }
        return $user;
    }

}
