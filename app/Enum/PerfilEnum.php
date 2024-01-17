<?php

namespace App\Enum;
use ArchTech\Enums\InvokableCases;

enum PerfilEnum: int
{
    use InvokableCases;

    /* Mudancas de Alto impacto */
    case DEV = 1;
    case ADMIN = 2;
}


