<?php

namespace App\Apis;

use App\Enums\CpfStatusEnum;

final class ApiMockCpf
{
    private const STATUSES = [
        CpfStatusEnum::VALID,
        CpfStatusEnum::PENDENT,
        CpfStatusEnum::NEGATIVE
    ];

    public function fetchMockCpfStatus(): int
    {
        return array_rand(self::STATUSES);
    }
}
