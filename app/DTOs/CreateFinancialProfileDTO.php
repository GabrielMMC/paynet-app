<?php

namespace App\DTOs;

use App\Enums\CpfSituationEnum;
use App\Enums\CpfRiskEnum;

class CreateFinancialProfileDTO extends DTO
{
    public function __construct(
        public readonly CpfSituationEnum $situationId,
        public readonly CpfRiskEnum $riskId,
        public readonly int $userId
    ) {}
}
