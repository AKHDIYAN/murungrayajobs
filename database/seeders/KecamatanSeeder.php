<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatan = [
            'Muara Laung',
            'Laung Tuhup',
            'Permata Intan',
            'Tanah Siang',
            'Tanah Siang Selatan',
            'Sumber Barito',
            'Sungai Babuat',
            'Seribu Riam',
            'Uut Murung',
            'Barito Tuhup Raya',
        ];

        foreach ($kecamatan as $nama) {
            DB::table('kecamatan')->insert([
                'nama_kecamatan' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
