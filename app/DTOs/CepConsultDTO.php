<?php

namespace App\DTOs;

class CepConsultDTO extends DTO
{
    public function __construct(
        public readonly ?string $neighborhood,
        public readonly ?string $complement,
        public readonly ?string $stateCode,
        public readonly ?string $street,
        public readonly ?string $region,
        public readonly ?string $siafi,
        public readonly ?string $state,
        public readonly ?string $city,
        public readonly ?string $ibge,
        public readonly ?string $cep,
        public readonly ?string $gia,
        public readonly ?string $ddd
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            neighborhood: $data["bairro"],
            complement: $data["complemento"],
            stateCode: $data["uf"],
            street: $data["logradouro"],
            region: $data["regiao"],
            state: $data["estado"],
            siafi: $data["siafi"],
            city: $data["localidade"],
            ibge: $data["ibge"],
            cep: $data["cep"],
            gia: $data["gia"],
            ddd: $data["ddd"]
        );
    }
}
