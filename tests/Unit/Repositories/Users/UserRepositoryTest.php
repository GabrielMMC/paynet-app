<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Users\UserRepository;
use Database\Seeders\SituationsSeeder;
use App\DTOs\CreateUserPayloadDTO;
use Database\Seeders\RisksSeeder;
use App\DTOs\CepConsultDTO;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new UserRepository(new User);
    $this->seed([SituationsSeeder::class, RisksSeeder::class]);
});

it('can find a user by cpf', function () {
    $user = User::factory()->create([
        'cpf' => '12345678900',
    ]);

    $found = $this->repository->findByCpf('12345678900');

    expect($found)->not()->toBeNull();
    expect($found->id)->toBe($user->id);
});

it('returns null when user is not found by cpf', function () {
    $found = $this->repository->findByCpf('00000000000');

    expect($found)->toBeNull();
});

it('creates a user with address', function () {
    $dto = new CreateUserPayloadDTO(
        name: 'John Doe',
        email: 'john@example.com',
        cpf: '12345678901',
        address: new CepConsultDTO(
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
        )
    );

    $user = $this->repository->createWithAddress($dto);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->address)->not()->toBeNull();
    expect($user->address->street)->toBe('logradouro');
});
