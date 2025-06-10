<?php

use App\Repositories\FinancialProfiles\UserFinancialProfileRepository;
use App\Services\FinancialProfiles\RiskAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\DTOs\CreateFinancialProfileDTO;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\SituationsSeeder;
use App\Jobs\ProcessUserRiskAnalysis;
use App\Models\UserFinancialProfile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Database\Seeders\RisksSeeder;
use App\Enums\CpfSituationEnum;
use App\DTOs\CepConsultDTO;
use App\Models\UserAddress;
use App\Enums\CpfRiskEnum;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed([SituationsSeeder::class, RisksSeeder::class]);
});

it('processes risk analysis and stores PDF and profile', function () {
    Storage::fake();
    Log::shouldReceive('channel->info')->once();

    $user = User::factory()->create(['cpf' => '12345678909']);
    $user->setRelation('address', UserAddress::factory()->make(['state' => 'SP']));

    $cepConsult = new CepConsultDTO(
        neighborhood: "bairro",
        complement: "complemento",
        stateCode: "uf",
        street: "logradouro",
        region: "regiao",
        state: "estado",
        siafi: "siafi",
        city: "localidade",
        ibge: "ibge",
        cep: "cep",
        gia: "gia",
        ddd: 11
    );

    $cpfSituation = CpfSituationEnum::NEGATIVE;

    $riskServiceMock = Mockery::mock(RiskAnalysisService::class);
    $riskServiceMock->shouldReceive('analyze')
        ->once()
        ->with($cpfSituation, 'SP')
        ->andReturn(CpfRiskEnum::HIGH);

    App::instance(RiskAnalysisService::class, $riskServiceMock);

    $repoMock = Mockery::mock(UserFinancialProfileRepository::class);
    $repoMock->shouldReceive('createUserFinancialProfile')
        ->once()
        ->with(Mockery::type(CreateFinancialProfileDTO::class))
        ->andReturn(new UserFinancialProfile([
            'risk_id' => CpfRiskEnum::HIGH,
            'situation_id' => CpfSituationEnum::NEGATIVE,
        ]));

    App::instance(UserFinancialProfileRepository::class, $repoMock);

    $pdfMock = Mockery::mock();
    $pdfMock->shouldReceive('loadView')
        ->once()
        ->with('reports.user_risk', ['user' => $user])
        ->andReturnSelf();
    $pdfMock->shouldReceive('output')->once()->andReturn('PDF_CONTENT');

    App::instance('dompdf.wrapper', $pdfMock);

    $job = new ProcessUserRiskAnalysis($cpfSituation, $cepConsult, $user);
    $job->handle($riskServiceMock);

    Storage::assertExists("reports/{$user->id}.pdf");
});
