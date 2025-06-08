<?php

namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use App\Traits\CacheableUser;
use App\Models\User;
use Exception;
use Throwable;

class FindUserService
{
    use CacheableUser;

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function findUserByCpf(string $cpf): User
    {
        if ($cachedData = CacheableUser::getUserFromCache($cpf)) {
            return $cachedData;
        }

        try {
            $user = $this->userRepository->findByCpf($cpf);

            if (!$user) throw new Exception("Usuario de CPF $cpf não encontrado.");

            CacheableUser::cacheUserData($cpf, $user);

            return $user;
        } catch (Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
}
