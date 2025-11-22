<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SektorSeeder extends Seeder
{
    public function run(): void
    {
        $sektor = [
            'Pertanian',
            'Pertambangan',
            'Konstruksi',
            'Perdagangan',
            'Pendidikan',
            'Kesehatan',
            'Jasa',
            'Pariwisata',
            'Teknologi Informasi',
            'Manufaktur',
            'Transportasi',
            'Keuangan',
        ];

        foreach ($sektor as $nama) {
            DB::table('sektor')->insert([
                'nama_kategori' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
