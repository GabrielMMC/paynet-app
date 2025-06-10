<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'email'      => $this->email,
            'name'       => $this->name,
            'cpf'        => $this->cpf,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
