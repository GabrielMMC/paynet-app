<?php

namespace App\Http\Resources\UserAddresses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'neighborhood'  => $this->neighborhood,
            'complement'    => $this->complement,
            'state_code'    => $this->state_code,
            'street'        => $this->street,
            'region'        => $this->region,
            'siafi'         => $this->siafi,
            'state'         => $this->state,
            'city'          => $this->city,
            'ibge'          => $this->ibge,
            'cep'           => $this->cep,
            'gia'           => $this->gia,
            'ddd'           => $this->ddd
        ];
    }
}
