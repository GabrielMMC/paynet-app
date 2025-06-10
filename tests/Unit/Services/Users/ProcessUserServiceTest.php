<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Users\ProcessUserService;
use App\Repositories\Users\UserRepository;
use Database\Seeders\SituationsSeeder;
use App\Jobs\ProcessUserRiskAnalysis;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Database\Seeders\RisksSeeder;
use App\Enums\CpfSituationEnum;
use App\Apis\ApiNationalize;
use App\DTOs\CepConsultDTO;
use App\Apis\ApiMockCpf;
use App\Apis\ApiViaCep;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed([SituationsSeeder::class, RisksSeeder::class]);
});

it('processes user and dispatches job when everything succeeds', function () {
    Bus::fake();
    Cache::shouldReceive('get')->andReturn(null);

    $cpf = '12345678900';
    $cep = '01001000';
    $email = 'ana@example.com';
    $name = 'ana';

    $cepDto = new CepConsultDTO(
        cep: $cep,
        street: 'Rua Teste',
        complement: '',
        neighborhood: 'Centro',
        city: 'São Paulo',
        stateCode: 'SP',
        state: 'São Paulo',
        region: 'Sudeste',
        ibge: '3550308',
        gia: '1004',
        ddd: 11,
        siafi: '7107'
    );

    $user = User::factory()->make(['cpf' => $cpf, 'email' => $email]);

    $userRepositoryMock = Mockery::mock(UserRepository::class);
    $userRepositoryMock->shouldReceive('createWithAddress')
        ->once()
        ->andReturn($user);

    app()->instance(UserRepository::class, $userRepositoryMock);

    app()->bind(
        ApiViaCep::class,
        fn() => Mockery::mock(ApiViaCep::class)
            ->shouldReceive('fetchCep')->with($cep)
            ->andReturn($cepDto)
            ->getMock()
    );

    app()->bind(ApiMockCpf::class, fn() =>
    Mockery::mock(ApiMockCpf::class)
        ->shouldReceive('fetchMockCpfSituation')
        ->with($cpf)
        ->andReturn(CpfSituationEnum::VALID)
        ->getMock());

    app()->bind(
        ApiNationalize::class,
        fn() => Mockery::mock(ApiNationalize::class)
            ->shouldReceive('fetchUserData')
            ->with($name)
            ->andReturn(['country' => 'BR'])
            ->getMock()
    );

    $service = app(ProcessUserService::class);
    $result = $service->processUser($cpf, $cep, $email);

    expect($result)->toBeInstanceOf(User::class);
    Bus::assertDispatched(ProcessUserRiskAnalysis::class);
});

it('throws ServiceException and logs when any step fails', function () {
    Cache::shouldReceive('get')->andReturn(null);
    Log::shouldReceive('channel')->with('user')->andReturnSelf();
    Log::shouldReceive('error')->once();

    $cpf = '123456789001111';
    $cep = '01001000';
    $email = 'ana@example.com';

    $cpfApiMock = Mockery::mock(ApiMockCpf::class);
    $cpfApiMock->shouldReceive('fetchMockCpfSituation')
        ->with($cpf)
        ->andThrow(new \Exception('Erro simulado'));

    $service = app(ProcessUserService::class);

    expect(fn() => $service->processUser($cpf, $cep, $email))
        ->toThrow(ServiceException::class);
});
