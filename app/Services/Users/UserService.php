<?php

namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Log;
use App\DTOs\CreateUserPayloadDTO;
use App\Traits\CacheableUser;
use App\Apis\ApiNationalize;
use App\DTOs\CepConsultDTO;
use App\Apis\ApiMockCpf;
use App\Apis\ApiViaCep;
use App\Models\User;
use Exception;
use Throwable;

class UserService
{
    use CacheableUser;

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function processUser(string $cpf, string $cep, string $email): User | array
    {
        if ($cachedData = CacheableUser::getUserFromCache($cpf)) {
            return $cachedData;
        }

        try {
            $userData = $this->fetchFromUserData($email);
            $cpfStatus = $this->fetchMockCpfStatus($cpf);
            $cepData = $this->fetchFromCep($cep);

            $payload = $this->mountPayload($cpf, $email, $cepData);
            $createdUser = $this->userRepository->createWithAddress($payload);

            CacheableUser::cacheUserData($cpf, $createdUser);

            return $createdUser;
        } catch (Throwable $e) {
            Log::error("User processing failed: " . $e->getMessage());
            throw new Exception('Falha ao processar usuário, tente novamente mais tarde.');
        }
    }

    private function fetchFromCep(string $cep): CepConsultDTO
    {
        return app(ApiViaCep::class)->fetchCep($cep);
    }

    private function fetchMockCpfStatus(string $cpf): int
    {
        return app(ApiMockCpf::class)->fetchMockCpfStatus($cpf);
    }

    private function fetchFromUserData(string $email): array
    {
        $firstName = $this->getNameByEmail($email);
        return app(ApiNationalize::class)->fetchUserData($firstName);
    }

    private function getNameByEmail(string $email): string
    {
        return explode('@', $email)[0];
    }

    private function mountPayload(string $cpf, string $email, CepConsultDTO $cepData): CreateUserPayloadDTO
    {
        return new CreateUserPayloadDTO(
            name: $this->getNameByEmail($email),
            address: $cepData,
            email: $email,
            cpf: $cpf,
        );
    }
}
