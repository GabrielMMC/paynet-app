<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        return [
            'email'      => $data['email'],
            'name'       => $data['name'],
            'cpf'        => $data['cpf'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ];
    }
}
