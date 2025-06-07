<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\ProcessUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessMessageResource;
use App\Services\Users\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function processUser(ProcessUserRequest $request): SuccessMessageResource
    {
        [$cpf, $cep, $email] = $request->validated();
        $processedUser = $this->userService->processUser($cpf, $cep, $email);

        return new SuccessMessageResource($processedUser);
    }
}
