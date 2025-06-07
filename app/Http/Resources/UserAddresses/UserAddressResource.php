<?php

namespace App\Http\Resources\UserAddresses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        return [
            'neighborhood'  => $data['neighborhood'],
            'complement'    => $data['complement'],
            'state_code'    => $data['state_code'],
            'street'        => $data['street'],
            'region'        => $data['region'],
            'siafi'         => $data['siafi'],
            'state'         => $data['state'],
            'city'          => $data['city'],
            'ibge'          => $data['ibge'],
            'cep'           => $data['cep'],
            'gia'           => $data['gia'],
            'ddd'           => $data['ddd']
        ];
    }
}
