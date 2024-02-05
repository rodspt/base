<?php

namespace App\DTO\User;

class CreateUserDTO
{
    public function __construct(
        readonly public string $cpf,
        readonly public string $nome,
        readonly public string $email,
        readonly public string $password,
        readonly public int $perfil_id
    ){}

}
