<?php

namespace App\Services\Users;

use Illuminate\Support\Facades\Log;
use App\Apis\ApiNationalize;
use App\Apis\ApiMockCpf;
use App\Apis\ApiViaCep;
use Exception;
use Throwable;

class UserService
{
    public function processUser(string $cpf, string $cep, string $email): string
    {
        try {
            $userData = $this->fetchFromUserData($email);
            $cpfStatus = $this->fetchMockCpfStatus($cpf);
            $cepData = $this->fetchFromCep($cep);

            return 'Usuário processado com sucesso!';
        } catch (Throwable $e) {
            Log::error("User processing failed: " . $e->getMessage());
            throw new Exception('Falha ao processar usuário, tente novamente mais tarde.');
        }
    }

    private function fetchFromCep(string $cep): array
    {
        return app(ApiViaCep::class)->fetchCep($cep);
    }

    private function fetchMockCpfStatus(string $cpf): int
    {
        return app(ApiMockCpf::class)->fetchMockCpfStatus($cpf);
    }

    private function fetchFromUserData(string $email): array
    {
        $firstName = explode('@', $email)[0];
        return app(ApiNationalize::class)->fetchUserData($firstName);
    }
}
