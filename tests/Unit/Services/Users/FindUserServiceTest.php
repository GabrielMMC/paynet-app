<?php

use App\Repositories\Users\UserRepository;
use App\Services\Users\FindUserService;
use Illuminate\Support\Facades\Redis;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;
use App\Traits\CacheableUser;
use App\Models\User;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->repository = mock(UserRepository::class);
    $this->service = new FindUserService($this->repository);
    Redis::flushdb();
});

it('returns user from cache if available', function () {
    $cpf = '12345678900';
    $user = User::factory()->make(['cpf' => $cpf]);

    CacheableUser::cacheUserData($cpf, $user);

    $result = $this->service->findUserByCpf($cpf);

    expect($result->cpf)->toBe($cpf);
});

it('returns user from repository and caches it if not in cache', function () {
    $cpf = '98765432100';
    $user = User::factory()->make(['cpf' => $cpf]);

    expect(CacheableUser::getUserFromCache($cpf))->toBeNull();

    $this->repository->shouldReceive('findByCpf')
        ->once()
        ->with($cpf)
        ->andReturn($user);

    $result = $this->service->findUserByCpf($cpf);

    expect($result->cpf)->toBe($cpf);
    expect(CacheableUser::getUserFromCache($cpf))->not()->toBeNull();
});

it('throws ServiceException if user is not found', function () {
    $cpf = '010101010101';

    $this->repository->shouldReceive('findByCpf')
        ->once()
        ->with($cpf)
        ->andReturn(null);

    $this->expectException(ServiceException::class);
    $this->expectExceptionMessage("Falha ao buscar por usuário, tente novamente mais tarde");

    $this->service->findUserByCpf($cpf);
});

it('logs and throws generic ServiceException if something fails internally', function () {
    $cpf = '99999999999';

    Log::shouldReceive('channel')
        ->once()
        ->with('user')
        ->andReturnSelf();

    Log::shouldReceive('error')
        ->once();

    $this->repository->shouldReceive('findByCpf')
        ->once()
        ->with($cpf)
        ->andThrow(new Exception('Erro inesperado'));

    $this->expectException(ServiceException::class);
    $this->expectExceptionMessage('Falha ao buscar por usuário, tente novamente mais tarde');

    $this->service->findUserByCpf($cpf);
});
