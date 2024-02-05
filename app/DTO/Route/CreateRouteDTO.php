<?php

namespace App\DTO\Route;

class CreateRouteDTO
{
     public function __construct(
         readonly public string $name,
         readonly public string $description = '',
     ){}

}
