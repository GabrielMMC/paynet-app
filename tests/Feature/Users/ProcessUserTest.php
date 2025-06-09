<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\SituationsSeeder;
use Database\Seeders\RisksSeeder;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed([SituationsSeeder::class, RisksSeeder::class]);
});

it('successfully processes a user and returns the user resource', function () {
    $cpf = '12345678909';
    $cep = '15706086';
    $email = 'ana@example.com';
    $sanitizedCpf = '12345678909';
    $sanitizedCep = '15706086';

    $user = User::factory()->make([
        'cpf' => $cpf,
        'email' => $email,
        'name' => 'Ana'
    ]);

    $mockedService = Mockery::mock(\App\Services\Users\ProcessUserService::class);
    $mockedService->shouldReceive('processUser')
        ->with($sanitizedCpf, $sanitizedCep, $email)
        ->once()
        ->andReturn($user);

    $this->app->instance(\App\Services\Users\ProcessUserService::class, $mockedService);

    $response = $this->postJson('api/v1/users/process', [
        'cpf' => '123.456.789-09',
        'cep' => '15706086',
        'email' => $email,
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'user' => [
                'name',
                'email',
                'cpf',
                'created_at',
                'updated_at'
            ],
        ]);
});


it('sanitizes cpf and cep before passing to the service', function () {
    $rawCpf = '123.456.789-09';
    $rawCep = '01001-000';
    $email = 'test@example.com';

    $expectedCpf = '12345678909';
    $expectedCep = '01001000';

    $user = User::factory()->make(['cpf' => $expectedCpf, 'email' => $email]);

    $mockedService = Mockery::mock(\App\Services\Users\ProcessUserService::class);
    $mockedService->shouldReceive('processUser')
        ->with($expectedCpf, $expectedCep, $email)
        ->once()
        ->andReturn($user);

    $this->app->instance(\App\Services\Users\ProcessUserService::class, $mockedService);

    $response = $this->postJson('api/v1/users/process', [
        'cpf' => $rawCpf,
        'cep' => $rawCep,
        'email' => $email,
    ]);

    $response->assertOk();
});
