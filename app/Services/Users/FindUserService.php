<?php

namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Traits\CacheableUser;
use App\Models\User;
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

            if (!$user)
                throw new ServiceException(message: "Usuario de CPF $cpf não encontrado.", code: Response::HTTP_NOT_FOUND);

            CacheableUser::cacheUserData($cpf, $user);

            return $user;
        } catch (Throwable $th) {
            Log::channel('user')->error($th->getMessage(), $th->getTrace());
            throw new ServiceException("Falha ao buscar por usuário, tente novamente mais tarde", $th);
        }
    }
}
