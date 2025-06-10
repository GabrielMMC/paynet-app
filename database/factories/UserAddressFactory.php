<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserAddress;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'cep' => $this->faker->postcode(),
            'street' => $this->faker->streetName(),
            'complement' => $this->faker->secondaryAddress(),
            'neighborhood' => $this->faker->citySuffix(),
            'city' => $this->faker->city(),
            'state_code' => 'SP',
            'state' => 'São Paulo',
            'region' => 'Sudeste',
            'ibge' => $this->faker->randomNumber(5),
            'gia' => $this->faker->randomNumber(4),
            'ddd' => '11',
            'siafi' => $this->faker->randomNumber(5),
            'user_id' => null
        ];
    }
}
