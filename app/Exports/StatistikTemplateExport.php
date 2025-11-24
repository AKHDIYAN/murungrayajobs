<?php

namespace App\Exports;

use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Usia;
use App\Models\Sektor;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatistikTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Get sample data
        $kecamatan = Kecamatan::first();
        $pendidikan = Pendidikan::first();
        $usia = Usia::first();
        $sektor = Sektor::first();

        return [
            [
                'Contoh Nama',
                'Laki-laki',
                $kecamatan ? $kecamatan->nama_kecamatan : 'Barito Tuhup Raya',
                $pendidikan ? $pendidikan->tingkatan_pendidikan : 'Diploma',
                $usia ? $usia->kelompok_usia : '17-20',
                'Bekerja',
                $sektor ? $sektor->nama_kategori : 'Jasa',
            ],
            [
                'Contoh Nama 2',
                'Perempuan',
                $kecamatan ? $kecamatan->nama_kecamatan : 'Laung Tuhup',
                $pendidikan ? $pendidikan->tingkatan_pendidikan : 'Sarjana (S1)',
                $usia ? $usia->kelompok_usia : '21-29',
                'Menganggur',
                $sektor ? $sektor->nama_kategori : 'Kesehatan',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama',
            'jenis_kelamin',
            'kecamatan',
            'pendidikan',
            'usia',
            'status',
            'sektor',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
