<?php

namespace App\Apis;

use Illuminate\Http\Response;

final class ApiNationalize extends BaseApi
{
    public function __construct()
    {
        $this->logChannel = 'nationalize';
        $this->baseUrl = config('nationalize.host');
    }

    public function fetchUserData(string $userName): array
    {
        $response = $this->get(['name' => $userName])
            ->expectedResponse(Response::HTTP_OK)
            ->fetch();

        return $response->json();
    }
}
