<?php

use App\Services\FinancialProfiles\RiskAnalysisService;
use App\Enums\CpfSituationEnum;
use App\Enums\CpfRiskEnum;

beforeEach(function () {
    $this->service = new RiskAnalysisService();
});

it('returns HIGH risk when CPF is NEGATIVE and state is SP', function () {
    $risk = $this->service->analyze(CpfSituationEnum::NEGATIVE, 'SP');
    expect($risk)->toBe(CpfRiskEnum::HIGH);
});

it('returns HIGH risk when CPF is NEGATIVE and state is RJ', function () {
    $risk = $this->service->analyze(CpfSituationEnum::NEGATIVE, 'RJ');
    expect($risk)->toBe(CpfRiskEnum::HIGH);
});

it('returns MEDIUM risk when CPF is PENDENT regardless of state', function () {
    $risk = $this->service->analyze(CpfSituationEnum::PENDENT, 'SP');
    expect($risk)->toBe(CpfRiskEnum::MEDIUM);

    $risk = $this->service->analyze(CpfSituationEnum::PENDENT, 'MG');
    expect($risk)->toBe(CpfRiskEnum::MEDIUM);
});

it('returns LOW risk when CPF is REGULAR and state is not high risk', function () {
    $risk = $this->service->analyze(CpfSituationEnum::VALID, 'MG');
    expect($risk)->toBe(CpfRiskEnum::LOW);
});

it('returns LOW risk when CPF is NEGATIVE but state is not high risk', function () {
    $risk = $this->service->analyze(CpfSituationEnum::NEGATIVE, 'RS');
    expect($risk)->toBe(CpfRiskEnum::LOW);
});
