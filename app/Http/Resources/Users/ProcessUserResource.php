<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\UserAddresses\UserAddressResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ProcessUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        return [
            'user' => new UserResource($data),
            'address' => new UserAddressResource($data['address'])
        ];
    }
}
