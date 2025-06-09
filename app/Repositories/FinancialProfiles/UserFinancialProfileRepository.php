<?php

namespace App\Repositories\FinancialProfiles;

use App\DTOs\CreateFinancialProfileDTO;
use App\Models\UserFinancialProfile;

class UserFinancialProfileRepository
{
    public function __construct(
        private UserFinancialProfile $model
    ) {}

    public function createUserFinancialProfile(CreateFinancialProfileDTO $payload): UserFinancialProfile
    {
        return $this->model->updateOrCreate($payload->toArrayCastSnakeCase());
    }
}
