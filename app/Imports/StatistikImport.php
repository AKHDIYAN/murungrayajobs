<?php

namespace App\Imports;

use App\Models\Statistik;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Usia;
use App\Models\Sektor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StatistikImport
{
    protected $errors = [];
    protected $imported = 0;
    protected $skipped = 0;

    public function import($filePath)
    {
        // Detect file type and read accordingly
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        if ($extension === 'csv') {
            return $this->importCSV($filePath);
        } else {
            return $this->importExcel($filePath);
        }
    }
    
    protected function importCSV($filePath)
    {
        $file = fopen($filePath, 'r');
        $headers = fgetcsv($file); // Skip header row
        $rowNumber = 1;
        
        while (($row = fgetcsv($file)) !== false) {
            $rowNumber++;
            
            try {
                // Map row to associative array
                $data = [
                    'nama' => trim($row[0] ?? ''),
                    'jenis_kelamin' => trim($row[1] ?? ''),
                    'kecamatan' => trim($row[2] ?? ''),
                    'pendidikan' => trim($row[3] ?? ''),
                    'usia' => trim($row[4] ?? ''),
                    'status' => trim($row[5] ?? ''),
                    'sektor' => trim($row[6] ?? ''),
                ];
                
                // Skip empty rows
                if (empty($data['nama'])) {
                    continue;
                }
                
                $this->processRow($data, $rowNumber);
                
            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                $this->skipped++;
                Log::error("Error importing CSV row {$rowNumber}: " . $e->getMessage());
            }
        }
        
        fclose($file);
    }
    
    protected function importExcel($filePath)
    {
        // For Excel files, read as CSV for now
        // User can save Excel as CSV first
        throw new \Exception('Untuk file Excel (.xlsx/.xls), mohon save as CSV terlebih dahulu atau gunakan template CSV yang sudah disediakan.');
    }
    
    protected function processRow($data, $rowNumber)
    {
        try {
            // Validate required fields
            $validator = Validator::make($data, [
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan,L,P',
                'kecamatan' => 'required|string',
                'pendidikan' => 'required|string',
                'usia' => 'required|string',
                'status' => 'required|in:Bekerja,Menganggur',
                'sektor' => 'required|string',
            ]);

            if ($validator->fails()) {
                $this->errors[] = "Baris {$rowNumber}: " . implode(', ', $validator->errors()->all());
                $this->skipped++;
                return;
            }

            // Normalize jenis kelamin
            $jenisKelamin = $data['jenis_kelamin'];
            if (strtoupper($jenisKelamin) === 'L') {
                $jenisKelamin = 'Laki-laki';
            } elseif (strtoupper($jenisKelamin) === 'P') {
                $jenisKelamin = 'Perempuan';
            }

            // Find related records
            $kecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', '%' . $data['kecamatan'] . '%')->first();
            if (!$kecamatan) {
                $this->errors[] = "Baris {$rowNumber}: Kecamatan '{$data['kecamatan']}' tidak ditemukan";
                $this->skipped++;
                return;
            }

            $pendidikan = Pendidikan::where('tingkatan_pendidikan', 'LIKE', '%' . $data['pendidikan'] . '%')->first();
            if (!$pendidikan) {
                $this->errors[] = "Baris {$rowNumber}: Pendidikan '{$data['pendidikan']}' tidak ditemukan";
                $this->skipped++;
                return;
            }

            $usia = Usia::where('kelompok_usia', 'LIKE', '%' . $data['usia'] . '%')->first();
            if (!$usia) {
                $this->errors[] = "Baris {$rowNumber}: Usia '{$data['usia']}' tidak ditemukan";
                $this->skipped++;
                return;
            }

            $sektor = Sektor::where('nama_kategori', 'LIKE', '%' . $data['sektor'] . '%')->first();
            if (!$sektor) {
                $this->errors[] = "Baris {$rowNumber}: Sektor '{$data['sektor']}' tidak ditemukan";
                $this->skipped++;
                return;
            }

            // Create statistik record
            Statistik::create([
                'nama' => $data['nama'],
                'jenis_kelamin' => $jenisKelamin,
                'id_kecamatan' => $kecamatan->id_kecamatan,
                'id_pendidikan' => $pendidikan->id_pendidikan,
                'id_usia' => $usia->id_usia,
                'status' => $data['status'],
                'id_sektor' => $sektor->id_sektor,
            ]);

            $this->imported++;

        } catch (\Exception $e) {
            $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
            $this->skipped++;
            Log::error("Error importing statistik row {$rowNumber}: " . $e->getMessage());
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getSkipped()
    {
        return $this->skipped;
    }
}
