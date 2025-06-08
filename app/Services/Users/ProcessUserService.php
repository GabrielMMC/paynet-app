<?php

namespace App\Services\Users;

use App\Repositories\Users\UserRepository;
use App\Jobs\ProcessUserRiskAnalysis;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;
use App\DTOs\CreateUserPayloadDTO;
use App\Enums\CpfSituationEnum;
use App\Traits\CacheableUser;
use App\Apis\ApiNationalize;
use App\DTOs\CepConsultDTO;
use App\Apis\ApiMockCpf;
use App\Apis\ApiViaCep;
use App\Models\User;
use Throwable;

final class ProcessUserService
{
    use CacheableUser;

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function processUser(string $cpf, string $cep, string $email): User
    {
        if ($cachedData = CacheableUser::getUserFromCache($cpf)) {
            return $cachedData;
        }

        try {
            $userData = $this->fetchFromUserData($email);
            $cpfSituation = $this->fetchMockCpfSituation($cpf);
            $cepData = $this->fetchFromCep($cep);

            $userPayload = $this->mountUserPayload($cpf, $email, $cepData);
            $createdUser = $this->userRepository->createWithAddress($userPayload);

            CacheableUser::cacheUserData($cpf, $createdUser);
            ProcessUserRiskAnalysis::dispatch($cpfSituation, $cepData, $createdUser)
                ->onQueue('risk_analysis');

            return $createdUser;
        } catch (Throwable $th) {
            Log::channel('user')->error($th->getMessage(), $th->getTrace());
            throw new ServiceException('Falha ao processar usuário, tente novamente mais tarde.', $th);
        }
    }

    private function fetchFromCep(string $cep): CepConsultDTO
    {
        return app(ApiViaCep::class)->fetchCep($cep);
    }

    private function fetchMockCpfSituation(string $cpf): CpfSituationEnum
    {
        return app(ApiMockCpf::class)->fetchMockCpfSituation($cpf);
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

    private function mountUserPayload(string $cpf, string $email, CepConsultDTO $cepData): CreateUserPayloadDTO
    {
        return new CreateUserPayloadDTO(
            name: $this->getNameByEmail($email),
            address: $cepData,
            email: $email,
            cpf: $cpf,
        );
    }
}
