<?php

namespace App\Repositories\Users;

use App\DTOs\CreateUserPayloadDTO;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserRepository
{
    public function __construct(
        private User $model
    ) {}

    public function findByCpf(string $cpf): ?User
    {
        return $this->model->firstWhere('cpf', $cpf);
    }

    public function createWithAddress(CreateUserPayloadDTO $createUserPayload): User
    {
        return DB::transaction(function () use ($createUserPayload) {
            $user = $this->model->create($createUserPayload->toArray());
            $user->address()->create($createUserPayload->address->toArrayCastSnakeCase());

            return $user->load(['address']);
        });
    }
}
