<?php

namespace App\Http\Controllers\Users;

use App\Http\Resources\Users\ProcessUserResource;
use App\Http\Requests\Users\ProcessUserRequest;
use App\Http\Controllers\Controller;
use App\Services\Users\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function processUser(ProcessUserRequest $request): ProcessUserResource
    {
        [$cpf, $cep, $email] = $request->validated();
        $processedUser = $this->userService->processUser($cpf, $cep, $email);

        return new ProcessUserResource($processedUser);
    }
}
