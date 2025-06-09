<?php

namespace App\Apis;

use App\Enums\CpfSituationEnum;

class ApiMockCpf
{
    private const STATUSES = [
        CpfSituationEnum::VALID,
        CpfSituationEnum::PENDENT,
        CpfSituationEnum::NEGATIVE
    ];

    public function fetchMockCpfSituation(): CpfSituationEnum
    {
        $randomKey = array_rand(self::STATUSES);
        return self::STATUSES[$randomKey];
    }
}
