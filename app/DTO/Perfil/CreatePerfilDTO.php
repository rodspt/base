<?php

namespace App\DTO\Perfil;

class CreatePerfilDTO
{
     public function __construct(
         readonly public string $name,
         readonly public string $description = '',
     ){}

}
