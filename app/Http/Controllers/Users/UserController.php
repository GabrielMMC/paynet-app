<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\Users\ProcessUserResource;
use App\Http\Requests\Users\ProcessUserRequest;
use App\Http\Resources\Users\UserDataResource;
use App\Http\Requests\Users\FindUserRequest;
use App\Services\Users\ProcessUserService;
use App\Services\Users\FindUserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function processUser(ProcessUserRequest $request, ProcessUserService $service): ProcessUserResource
    {
        [$cpf, $cep, $email] = $request->validated();
        $processedUser = $service->processUser($cpf, $cep, $email);

        return new ProcessUserResource($processedUser);
    }

    public function findUser(FindUserRequest $request, FindUserService $service): UserDataResource
    {
        $cpf = $request->validated('cpf');
        $user = $service->findUserByCpf($cpf);

        return new UserDataResource($user);
    }
}
