<?php

namespace App\Services\FinancialProfiles;

use App\Enums\CpfSituationEnum;
use App\Enums\CpfRiskEnum;

class RiskAnalysisService
{
    private const HIGH_RISK_STATES = [
        'SP',
        'RJ'
    ];

    public function analyze(CpfSituationEnum $cpfSituation, string $state): CpfRiskEnum
    {
        if ($this->hasHighRisk($cpfSituation, $state)) {
            return CpfRiskEnum::HIGH;
        }

        if ($this->hasMediumRisk($cpfSituation)) {
            return CpfRiskEnum::MEDIUM;
        }

        return CpfRiskEnum::LOW;
    }

    // Possibility of following a strategy pattern
    private function hasHighRisk(CpfSituationEnum $cpfSituation, string $state): bool
    {
        return $cpfSituation === CpfSituationEnum::NEGATIVE
            && in_array($state, self::HIGH_RISK_STATES);
    }

    private function hasMediumRisk(CpfSituationEnum $cpfSituation): bool
    {
        return $cpfSituation === CpfSituationEnum::PENDENT;
    }
}
