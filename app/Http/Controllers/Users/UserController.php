<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\Users\ProcessUserResource;
use App\Http\Requests\Users\ProcessUserRequest;
use App\Services\Users\ProcessUserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function processUser(ProcessUserRequest $request, ProcessUserService $service): ProcessUserResource
    {
        [$cpf, $cep, $email] = $request->validated();
        $processedUser = $service->processUser($cpf, $cep, $email);

        return new ProcessUserResource($processedUser);
    }
}
