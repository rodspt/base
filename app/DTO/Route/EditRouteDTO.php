<?php

namespace App\DTO\Route;

class EditRouteDTO
{
     public function __construct(
         readonly public string $id,
         readonly public string $name,
         readonly public string $description,
     ){}

}
