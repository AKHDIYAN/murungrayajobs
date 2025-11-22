<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendidikanSeeder extends Seeder
{
    public function run(): void
    {
        $pendidikan = [
            'SD',
            'SMP',
            'SMA/SMK',
            'Diploma',
            'Sarjana (S1)',
            'Magister (S2)',
            'Doktor (S3)',
        ];

        foreach ($pendidikan as $tingkat) {
            DB::table('pendidikan')->insert([
                'tingkatan_pendidikan' => $tingkat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
