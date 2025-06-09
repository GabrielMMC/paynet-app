<?php

namespace App\Enums;

enum CpfRiskEnum: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
}
