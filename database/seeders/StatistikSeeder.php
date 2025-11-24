<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistik;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Usia;
use App\Models\Sektor;

class StatistikSeeder extends Seeder
{
    public function run()
    {
        // Get all master data
        $kecamatanList = Kecamatan::all();
        $pendidikanList = Pendidikan::all();
        $usiaList = Usia::all();
        $sektorList = Sektor::all();

        if ($kecamatanList->isEmpty() || $pendidikanList->isEmpty() || $usiaList->isEmpty() || $sektorList->isEmpty()) {
            $this->command->error('Master data (Kecamatan, Pendidikan, Usia, Sektor) harus diisi dulu!');
            return;
        }

        // Sample names
        $namaLakiLaki = [
            'Ahmad Yani', 'Budi Santoso', 'Cahya Permana', 'Dedi Kurniawan', 'Eko Prasetyo',
            'Fajar Nugroho', 'Gunawan Hidayat', 'Hendra Wijaya', 'Irfan Maulana', 'Joko Susanto',
            'Kurniawan Saputra', 'Lukman Hakim', 'Muhamad Rizki', 'Nugroho Adi', 'Oki Setiawan',
            'Prayoga Utama', 'Rizal Ramadan', 'Supriyanto', 'Teguh Wibowo', 'Usman Abdullah',
            'Vino Bastian', 'Wahyu Hidayat', 'Yanto Supriyadi', 'Zainudin Ahmad', 'Agus Salim'
        ];

        $namaPerempuan = [
            'Siti Aminah', 'Rina Wati', 'Dewi Sartika', 'Fitri Handayani', 'Indah Permata',
            'Karina Sari', 'Lestari Ningrum', 'Maya Anggraini', 'Nur Azizah', 'Putri Ayu',
            'Ratna Dewi', 'Sri Wahyuni', 'Tika Puspita', 'Ulin Nisa', 'Wulan Dari',
            'Yuni Kartika', 'Zahra Amelia', 'Ani Sulistyowati', 'Bella Safitri', 'Citra Lestari',
            'Dian Purnama', 'Erna Susanti', 'Farah Diba', 'Gita Savitri', 'Hani Nurhaliza'
        ];

        $this->command->info('Generating 200 data statistik ketenagakerjaan...');

        for ($i = 1; $i <= 200; $i++) {
            // Random gender
            $jenisKelamin = rand(0, 1) ? 'Laki-laki' : 'Perempuan';
            
            // Random name based on gender
            if ($jenisKelamin === 'Laki-laki') {
                $nama = $namaLakiLaki[array_rand($namaLakiLaki)] . ' ' . $i;
            } else {
                $nama = $namaPerempuan[array_rand($namaPerempuan)] . ' ' . $i;
            }

            // Random status (70% Bekerja, 30% Menganggur)
            $status = rand(1, 100) <= 70 ? 'Bekerja' : 'Menganggur';

            // Random master data
            $kecamatan = $kecamatanList->random();
            $pendidikan = $pendidikanList->random();
            $usia = $usiaList->random();
            $sektor = $sektorList->random();

            Statistik::create([
                'nama' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'id_kecamatan' => $kecamatan->id_kecamatan,
                'id_pendidikan' => $pendidikan->id_pendidikan,
                'id_usia' => $usia->id_usia,
                'status' => $status,
                'id_sektor' => $sektor->id_sektor,
            ]);

            if ($i % 50 == 0) {
                $this->command->info("Generated {$i} records...");
            }
        }

        $this->command->info('✅ Successfully generated 200 data statistik!');
        $this->command->info('Breakdown:');
        $this->command->info('- Total: ' . Statistik::count());
        $this->command->info('- Bekerja: ' . Statistik::where('status', 'Bekerja')->count());
        $this->command->info('- Menganggur: ' . Statistik::where('status', 'Menganggur')->count());
        $this->command->info('- Laki-laki: ' . Statistik::where('jenis_kelamin', 'Laki-laki')->count());
        $this->command->info('- Perempuan: ' . Statistik::where('jenis_kelamin', 'Perempuan')->count());
    }
}
