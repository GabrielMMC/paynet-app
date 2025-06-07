<?php

namespace App\Enums;

enum CpfStatusEnum: int
{
    case VALID = 0;
    case PENDENT = 1;
    case NEGATIVE = 2;
}
