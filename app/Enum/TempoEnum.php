<?php

namespace App\Enum;

enum TempoEnum: int
{
    case MINUTO = 60;
    case HORA = 3600;
    case DIA = 86400;
    case MES = 2629800;
    case SEMESTRE = 15778800;
    case ANUAL = 31557600;
}
