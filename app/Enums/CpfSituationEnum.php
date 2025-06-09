<?php

namespace App\Enums;

enum CpfSituationEnum: int
{
    case VALID = 1;
    case PENDENT = 2;
    case NEGATIVE = 3;
}
