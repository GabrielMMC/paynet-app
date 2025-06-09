<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Risk;

class RisksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $risks = [
            ['id' => 1, 'description' => 'Baixo'],
            ['id' => 2, 'description' => 'Médio'],
            ['id' => 3, 'description' => 'Alto']
        ];

        Risk::insert($risks);
    }
}
