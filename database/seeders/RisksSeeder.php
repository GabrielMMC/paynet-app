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
            ['description' => 'Baixo'],
            ['description' => 'Médio'],
            ['description' => 'Alto']
        ];

        Risk::insert($risks);
    }
}
