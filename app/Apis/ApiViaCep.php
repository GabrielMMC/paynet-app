<?php

namespace App\Apis;

use Illuminate\Http\Response;
use App\DTOs\CepConsultDTO;

class ApiViaCep extends BaseApi
{
    protected string $logChannel;
    protected string $baseUrl;

    public function __construct()
    {
        $this->logChannel = 'viacep';
        $this->baseUrl = config('viacep.host');
    }

    public function fetchCep(string $cep): CepConsultDTO
    {
        $response = $this->get()
            ->expectedResponse(Response::HTTP_OK)
            ->fetch("/ws/$cep/json");

        return CepConsultDTO::fromArray($response->json());
    }
}
