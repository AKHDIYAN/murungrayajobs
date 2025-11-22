<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsiaSeeder extends Seeder
{
    public function run(): void
    {
        $usia = [
            '17-20',
            '21-29',
            '30-39',
            '40-49',
            '50-59',
            '60+',
        ];

        foreach ($usia as $kelompok) {
            DB::table('usia')->insert([
                'kelompok_usia' => $kelompok,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
