<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Lamaran;
use App\Models\Perusahaan;
use Exception;

class PDFService
{
    /**
     * Generate PDF untuk detail pelamar
     * 
     * @param int $idLamaran
     * @param int $idPerusahaan
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function generateApplicantDetailPDF($idLamaran, $idPerusahaan)
    {
        try {
            $lamaran = Lamaran::with(['user.kecamatan', 'pekerjaan'])
                              ->where('id_lamaran', $idLamaran)
                              ->whereHas('pekerjaan', function($q) use ($idPerusahaan) {
                                  $q->where('id_perusahaan', $idPerusahaan);
                              })
                              ->firstOrFail();
            
            $pdf = Pdf::loadView('pdf.applicant-detail', compact('lamaran'));
            
            $filename = 'Pelamar_' . str_replace(' ', '_', $lamaran->user->nama) . '_' . date('YmdHis') . '.pdf';
            
            return $pdf->download($filename);
        } catch (Exception $e) {
            throw new Exception('Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF untuk list semua pelamar
     * 
     * @param \Illuminate\Support\Collection $lamarans
     * @param Perusahaan $perusahaan
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function generateApplicantsListPDF($lamarans, Perusahaan $perusahaan)
    {
        try {
            $pdf = Pdf::loadView('pdf.applicants-list', compact('lamarans', 'perusahaan'));
            
            $filename = 'DaftarPelamar_' . str_replace(' ', '_', $perusahaan->nama_perusahaan) . '_' . date('YmdHis') . '.pdf';
            
            return $pdf->download($filename);
        } catch (Exception $e) {
            throw new Exception('Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF laporan statistik
     * 
     * @param array $data
     * @return \Illuminate\Http\Response
     */
    public function generateStatistikPDF($data)
    {
        try {
            $pdf = Pdf::loadView('pdf.statistik-report', compact('data'));
            
            $filename = 'Laporan_Statistik_' . date('YmdHis') . '.pdf';
            
            return $pdf->download($filename);
        } catch (Exception $e) {
            throw new Exception('Gagal generate PDF laporan: ' . $e->getMessage());
        }
    }
}
