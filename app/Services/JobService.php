<?php

namespace App\Services;

use App\Models\Pekerjaan;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class JobService
{
    /**
     * Create job posting
     * 
     * @param array $data
     * @param Perusahaan $perusahaan
     * @return Pekerjaan
     * @throws Exception
     */
    public function createJob(array $data, Perusahaan $perusahaan)
    {
        DB::beginTransaction();
        
        try {
            // Validate tanggal_expired
            if (Carbon::parse($data['tanggal_expired'])->lt(Carbon::today())) {
                throw new Exception('Tanggal berakhir harus hari ini atau setelahnya');
            }

            // Validate gaji range
            if ($data['gaji_max'] < $data['gaji_min']) {
                throw new Exception('Gaji maksimum harus lebih besar atau sama dengan gaji minimum');
            }

            $pekerjaan = Pekerjaan::create([
                'id_perusahaan' => $perusahaan->id_perusahaan,
                'nama_perusahaan' => $perusahaan->nama_perusahaan,
                'id_kecamatan' => $data['id_kecamatan'],
                'id_kategori' => $data['id_kategori'],
                'nama_pekerjaan' => $data['nama_pekerjaan'],
                'gaji_min' => $data['gaji_min'],
                'gaji_max' => $data['gaji_max'],
                'deskripsi_pekerjaan' => $data['deskripsi_pekerjaan'],
                'persyaratan_pekerjaan' => $data['persyaratan_pekerjaan'],
                'benefit' => $data['benefit'] ?? null,
                'jumlah_lowongan' => $data['jumlah_lowongan'],
                'jenis_pekerjaan' => $data['jenis_pekerjaan'],
                'tanggal_expired' => $data['tanggal_expired'],
                'status' => $data['status'] ?? 'Pending',
            ]);

            DB::commit();
            
            return $pekerjaan;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update job posting
     * 
     * @param int $idPekerjaan
     * @param array $data
     * @return Pekerjaan
     * @throws Exception
     */
    public function updateJob($idPekerjaan, array $data)
    {
        DB::beginTransaction();
        
        try {
            $pekerjaan = Pekerjaan::findOrFail($idPekerjaan);

            // Validate tanggal_expired if provided
            if (isset($data['tanggal_expired'])) {
                if (Carbon::parse($data['tanggal_expired'])->lt(Carbon::today())) {
                    throw new Exception('Tanggal berakhir harus hari ini atau setelahnya');
                }
            }

            // Validate gaji range if provided
            if (isset($data['gaji_min']) && isset($data['gaji_max'])) {
                if ($data['gaji_max'] < $data['gaji_min']) {
                    throw new Exception('Gaji maksimum harus lebih besar atau sama dengan gaji minimum');
                }
            }

            $pekerjaan->update($data);

            DB::commit();
            
            return $pekerjaan;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get active jobs with filters
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveJobs(array $filters = [])
    {
        $query = Pekerjaan::with(['perusahaan', 'kecamatan', 'kategori'])
                          ->aktif();

        // Filter by kecamatan
        if (isset($filters['id_kecamatan']) && $filters['id_kecamatan']) {
            $query->where('id_kecamatan', $filters['id_kecamatan']);
        }

        // Filter by kategori
        if (isset($filters['id_kategori']) && $filters['id_kategori']) {
            $query->where('id_kategori', $filters['id_kategori']);
        }

        // Filter by jenis pekerjaan
        if (isset($filters['jenis_pekerjaan']) && $filters['jenis_pekerjaan']) {
            $query->where('jenis_pekerjaan', $filters['jenis_pekerjaan']);
        }

        // Search
        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        return $query->orderBy('tanggal_posting', 'desc')->paginate(12);
    }

    /**
     * Get jobs by company
     * 
     * @param int $idPerusahaan
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getJobsByCompany($idPerusahaan, array $filters = [])
    {
        $query = Pekerjaan::with(['kecamatan', 'kategori'])
                          ->where('id_perusahaan', $idPerusahaan);

        // Filter by status (aktif/berakhir)
        if (isset($filters['status'])) {
            if ($filters['status'] === 'aktif') {
                $query->aktif();
            } elseif ($filters['status'] === 'berakhir') {
                $query->berakhir();
            }
        }

        return $query->orderBy('tanggal_posting', 'desc')->get();
    }

    /**
     * Delete job
     * 
     * @param int $idPekerjaan
     * @return bool
     */
    public function deleteJob($idPekerjaan)
    {
        $pekerjaan = Pekerjaan::findOrFail($idPekerjaan);
        return $pekerjaan->delete();
    }
}
