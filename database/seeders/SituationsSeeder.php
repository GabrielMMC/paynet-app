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
            ['id' => 1, 'description' => 'Limpo'],
            ['id' => 2, 'description' => 'Pendente'],
            ['id' => 3, 'description' => 'Negativado']
        ];

        Situation::insert($situations);
    }
}
