<?php

namespace App\Services;

use App\Models\Lamaran;
use App\Models\User;
use App\Models\Pekerjaan;
use Exception;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Submit lamaran pekerjaan
     * 
     * @param array $data
     * @param User $user
     * @return Lamaran
     * @throws Exception
     */
    public function submitApplication(array $data, User $user)
    {
        DB::beginTransaction();
        
        try {
            // Check if already applied
            $existingApplication = Lamaran::where('id_pekerjaan', $data['id_pekerjaan'])
                                          ->where('id_user', $user->id_user)
                                          ->first();
            
            if ($existingApplication) {
                throw new Exception('Anda sudah melamar pekerjaan ini');
            }

            // Check if job is still active
            $pekerjaan = Pekerjaan::find($data['id_pekerjaan']);
            if (!$pekerjaan->is_aktif) {
                throw new Exception('Lowongan ini sudah berakhir atau tidak aktif');
            }

            // Upload CV
            $cvPath = $this->imageService->uploadDocument($data['cv'], 'cv');

            // Update user documents if uploaded
            $updateData = ['cv' => $cvPath];

            if (isset($data['ktp'])) {
                $updateData['ktp'] = $this->imageService->uploadDocument($data['ktp'], 'ktp');
            }

            if (isset($data['sertifikat'])) {
                $updateData['sertifikat'] = $this->imageService->uploadDocument($data['sertifikat'], 'sertifikat');
            }

            if (isset($data['foto_diri'])) {
                $updateData['foto_diri'] = $this->imageService->uploadDocument($data['foto_diri'], 'foto_diri');
            }

            // Update user documents
            $user->update($updateData);

            // Create lamaran
            $lamaran = Lamaran::create([
                'id_pekerjaan' => $data['id_pekerjaan'],
                'id_user' => $user->id_user,
                'cv' => $cvPath,
                'status' => 'Pending',
            ]);

            DB::commit();
            
            return $lamaran;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update status lamaran
     * 
     * @param int $idLamaran
     * @param string $status
     * @return Lamaran
     */
    public function updateStatus($idLamaran, $status)
    {
        $lamaran = Lamaran::findOrFail($idLamaran);
        $lamaran->update(['status' => $status]);
        
        return $lamaran;
    }

    /**
     * Get applications by user
     * 
     * @param int $idUser
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApplicationsByUser($idUser, $status = null)
    {
        $query = Lamaran::with(['pekerjaan.perusahaan', 'pekerjaan.kecamatan'])
                        ->where('id_user', $idUser);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('tanggal_terkirim', 'desc')->get();
    }

    /**
     * Get applications by company
     * 
     * @param int $idPerusahaan
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApplicationsByCompany($idPerusahaan, array $filters = [])
    {
        $query = Lamaran::with(['user.kecamatan', 'pekerjaan'])
                        ->whereHas('pekerjaan', function($q) use ($idPerusahaan) {
                            $q->where('id_perusahaan', $idPerusahaan);
                        });

        // Filter by job
        if (isset($filters['id_pekerjaan']) && $filters['id_pekerjaan']) {
            $query->where('id_pekerjaan', $filters['id_pekerjaan']);
        }

        // Filter by status
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        // Filter by kecamatan
        if (isset($filters['id_kecamatan']) && $filters['id_kecamatan']) {
            $query->whereHas('user', function($q) use ($filters) {
                $q->where('id_kecamatan', $filters['id_kecamatan']);
            });
        }

        // Search by name or NIK
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('tanggal_terkirim', 'desc')->get();
    }
}
