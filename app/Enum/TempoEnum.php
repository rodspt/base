<?php

namespace App\Enum;
use ArchTech\Enums\InvokableCases;

enum TempoEnum: int
{
    use InvokableCases;

    /* Mudancas de Alto impacto */
    case MINUTO = 60;
    case MINUTO_CINCO = 300;
    case MINUTO_DEZ = 600;
    case HORA = 3600;
    case DIA = 86400;
    case MES = 2629800;
    case SEMESTRE = 15778800;
    case ANUAL = 31557600;
}
