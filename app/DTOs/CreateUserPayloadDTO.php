<?php

namespace App\DTOs;

class CreateUserPayloadDTO extends DTO
{
    public function __construct(
        public readonly CepConsultDTO $address,
        public readonly string $email,
        public readonly string $name,
        public readonly string $cpf
    ) {}
}
