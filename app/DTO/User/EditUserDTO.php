<?php

namespace App\DTO\User;

class EditUserDTO
{
    public function __construct(
        readonly public string $nome,
        readonly public string $email,
        readonly public string $password,
        readonly public int $perfil_id
    ){}

}
