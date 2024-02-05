<?php

namespace App\DTO\Perfil;

class EditPerfilDTO
{
     public function __construct(
         readonly public int $id,
         readonly public string $name,
         readonly public string $description,
     ){}

}
