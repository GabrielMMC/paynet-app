<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Situation;

class SituationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $situations = [
            ['description' => 'Limpo'],
            ['description' => 'Pendente'],
            ['description' => 'Negativado']
        ];

        Situation::insert($situations);
    }
}
