<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\UserAddresses\UserAddressResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user'              => new UserResource($this),
            'address'           => new UserAddressResource($this->address()->first()),
            'financial_profile' => new UserFinancialProfileResource($this->financialProfile()->first())
        ];
    }
}
