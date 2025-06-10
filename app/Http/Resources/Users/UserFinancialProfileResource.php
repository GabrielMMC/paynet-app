<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserFinancialProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'situation' => $this->situation->description,
            'risk'      => $this->risk->description
        ];
    }
}
