<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelatihan;
use Carbon\Carbon;

class PelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelatihan = [
            [
                'nama_pelatihan' => 'Pelatihan Digital Marketing untuk UMKM',
                'deskripsi' => 'Pelatihan komprehensif tentang strategi digital marketing untuk mengembangkan usaha mikro, kecil, dan menengah.',
                'id_sektor' => 1,
                'penyelenggara' => 'Dinas Tenaga Kerja Kabupaten Murung Raya',
                'instruktur' => 'Budi Santoso, S.Kom',
                'tanggal_mulai' => Carbon::now()->addDays(14),
                'tanggal_selesai' => Carbon::now()->addDays(21),
                'durasi_hari' => 7,
                'kuota_peserta' => 30,
                'jenis_pelatihan' => 'Hybrid',
                'lokasi' => 'Gedung Diklat Pemkab Murung Raya',
                'persyaratan' => '- Minimal lulusan SMA/SMK\n- Memiliki usaha atau berminat berwirausaha\n- Memiliki laptop/smartphone',
                'status' => 'Dibuka',
                'sertifikat_tersedia' => true,
            ],
            [
                'nama_pelatihan' => 'Pelatihan Operator Alat Berat',
                'deskripsi' => 'Program pelatihan untuk mencetak operator alat berat profesional dengan sertifikasi resmi.',
                'id_sektor' => 3,
                'penyelenggara' => 'Dinas Tenaga Kerja & LPK Karya Mandiri',
                'instruktur' => 'Agus Prasetyo (Instruktur BNSP)',
                'tanggal_mulai' => Carbon::now()->addDays(7),
                'tanggal_selesai' => Carbon::now()->addDays(37),
                'durasi_hari' => 30,
                'kuota_peserta' => 20,
                'jenis_pelatihan' => 'Offline',
                'lokasi' => 'Training Center PT. Murung Raya Resources',
                'persyaratan' => '- Pria usia 18-40 tahun\n- Minimal lulusan SMP\n- Sehat jasmani',
                'status' => 'Dibuka',
                'sertifikat_tersedia' => true,
            ],
            [
                'nama_pelatihan' => 'Pelatihan Barista & Coffee Shop Management',
                'deskripsi' => 'Pelatihan lengkap untuk menjadi barista profesional dan mengelola coffee shop.',
                'id_sektor' => 2,
                'penyelenggara' => 'Dinas Tenaga Kerja & Asosiasi Kopi Kalteng',
                'instruktur' => 'Rina Wijaya (Certified Barista)',
                'tanggal_mulai' => Carbon::now()->addDays(21),
                'tanggal_selesai' => Carbon::now()->addDays(35),
                'durasi_hari' => 14,
                'kuota_peserta' => 25,
                'jenis_pelatihan' => 'Offline',
                'lokasi' => 'Kopi Nusantara Training Center',
                'persyaratan' => '- Usia 18-35 tahun\n- Minimal lulusan SMA/SMK',
                'status' => 'Dibuka',
                'sertifikat_tersedia' => true,
            ],
        ];

        foreach ($pelatihan as $data) {
            Pelatihan::create($data);
        }
    }
}
