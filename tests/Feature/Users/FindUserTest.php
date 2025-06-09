<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Users\FindUserService;
use Database\Seeders\SituationsSeeder;
use Database\Seeders\RisksSeeder;
use App\Enums\CpfSituationEnum;
use App\Enums\CpfRiskEnum;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed([SituationsSeeder::class, RisksSeeder::class]);
});

it('returns user data resource when cpf is valid and user found', function () {
    $user = User::factory()->create(['cpf' => '98765432100']);

    $user->address()->create([
        'neighborhood' => 'bairro',
        'complement' => 'complemento',
        'state_code' => 'uf',
        'street' => 'logradouro',
        'region' => 'regiao',
        'state' => 'estado',
        'siafi' => 'siafi',
        'city' => 'localidade',
        'ibge' => 'ibge',
        'cep' => '15706086',
        'gia' => 'gia',
        'ddd' => 17
    ]);

    $user->financialProfile()->create([
        'situation_id' => CpfSituationEnum::VALID,
        'risk_id' => CpfRiskEnum::LOW
    ]);

    $serviceMock = \Mockery::mock(FindUserService::class);
    $serviceMock->shouldReceive('findUserByCpf')
        ->once()
        ->with('98765432100')
        ->andReturn($user);

    $this->app->instance(FindUserService::class, $serviceMock);

    $response = $this->getJson('api/v1/users/98765432100');

    $response->assertStatus(201)
        ->assertJsonStructure([
            'user' => [
                'name',
                'email',
                'cpf',
                'created_at',
                'updated_at'
            ],
            'address' => [
                'neighborhood',
                'complement',
                'state_code',
                'street',
                'region',
                'siafi',
                'state',
                'city',
                'ibge',
                'cep',
                'gia',
                'ddd'
            ],
            'financial_profile' => [
                'situation',
                'risk'
            ],
        ]);
});

it('returns validation error if cpf is invalid with equal digits', function () {
    $response = $this->getJson('api/v1/users/11111111111');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['cpf'])
        ->assertJsonFragment([
            'cpf' => ['O CPF não pode conter todos os dígitos iguais.']
        ]);
});

it('returns validation error if cpf is invalid', function () {
    $response = $this->getJson('api/v1/users/12345678910');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['cpf'])
        ->assertJsonFragment([
            'cpf' => ['O CPF informado não é válido.']
        ]);
});

it('returns 404 if user not found', function () {
    $this->app->bind(\App\Services\Users\FindUserService::class, function () {
        return new class {
            public function findUserByCpf(string $cpf)
            {
                throw new \App\Exceptions\ServiceException(message: "Usuário não encontrado", code: 404);
            }
        };
    });

    $response = $this->getJson('/v1/users/98765432100');

    $response->assertStatus(404);
});
