<?php


namespace App\DTO\Auth;

class AuthDTO
{
    public function __construct(
        readonly public string $cpf,
        readonly public string $password
    )
    {}

}
