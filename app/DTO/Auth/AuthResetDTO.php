<?php


namespace App\DTO\Auth;

class AuthResetDTO
{
    public function __construct(
        readonly public string $cpf,
        readonly public string $email,
        readonly public string $password,
        readonly public string $token,
    )
    {}

}
