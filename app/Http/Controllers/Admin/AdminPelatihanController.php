<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\PelatihanPeserta;
use App\Models\Sektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPelatihanController extends Controller
{
    /**
     * Display a listing of pelatihan.
     */
    public function index()
    {
        $pelatihan = Pelatihan::with('sektor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pelatihan.index', compact('pelatihan'));
    }

    /**
     * Show the form for creating a new pelatihan.
     */
    public function create()
    {
        $sektorList = Sektor::all();
        return view('admin.pelatihan.create', compact('sektorList'));
    }

    /**
     * Store a newly created pelatihan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_sektor' => 'required|exists:sektor,id_sektor',
            'penyelenggara' => 'required|string|max:255',
            'instruktur' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi_hari' => 'required|integer|min:1',
            'kuota_peserta' => 'required|integer|min:1',
            'jenis_pelatihan' => 'required|in:Online,Offline,Hybrid',
            'lokasi' => 'nullable|string|max:255',
            'persyaratan' => 'nullable|string',
            'materi' => 'nullable|json',
            'status' => 'required|in:Dibuka,Ditutup,Berlangsung,Selesai',
            'sertifikat_tersedia' => 'boolean',
            'foto_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_banner')) {
            $validated['foto_banner'] = $request->file('foto_banner')->store('pelatihan/banners', 'public');
        }

        Pelatihan::create($validated);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil ditambahkan!');
    }

    /**
     * Display the specified pelatihan.
     */
    public function show($id)
    {
        $pelatihan = Pelatihan::with(['sektor', 'peserta.user'])->findOrFail($id);
        return view('admin.pelatihan.show', compact('pelatihan'));
    }

    /**
     * Show the form for editing the specified pelatihan.
     */
    public function edit($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $sektorList = Sektor::all();
        return view('admin.pelatihan.edit', compact('pelatihan', 'sektorList'));
    }

    /**
     * Update the specified pelatihan in storage.
     */
    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_sektor' => 'required|exists:sektor,id_sektor',
            'penyelenggara' => 'required|string|max:255',
            'instruktur' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi_hari' => 'required|integer|min:1',
            'kuota_peserta' => 'required|integer|min:1',
            'jenis_pelatihan' => 'required|in:Online,Offline,Hybrid',
            'lokasi' => 'nullable|string|max:255',
            'persyaratan' => 'nullable|string',
            'materi' => 'nullable|json',
            'status' => 'required|in:Dibuka,Ditutup,Berlangsung,Selesai',
            'sertifikat_tersedia' => 'boolean',
            'foto_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_banner')) {
            // Delete old banner if exists
            if ($pelatihan->foto_banner) {
                Storage::disk('public')->delete($pelatihan->foto_banner);
            }
            $validated['foto_banner'] = $request->file('foto_banner')->store('pelatihan/banners', 'public');
        }

        $pelatihan->update($validated);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil diperbarui!');
    }

    /**
     * Remove the specified pelatihan from storage.
     */
    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        // Delete banner if exists
        if ($pelatihan->foto_banner) {
            Storage::disk('public')->delete($pelatihan->foto_banner);
        }

        $pelatihan->delete();

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil dihapus!');
    }

    /**
     * Display list of participants for a pelatihan.
     */
    public function peserta($id)
    {
        $pelatihan = Pelatihan::with(['peserta.user'])->findOrFail($id);
        return view('admin.pelatihan.peserta', compact('pelatihan'));
    }

    /**
     * Approve a participant registration.
     */
    public function approvePeserta($pelatihanId, $pesertaId)
    {
        $peserta = PelatihanPeserta::where('id_pelatihan', $pelatihanId)
            ->where('id', $pesertaId)
            ->firstOrFail();

        $peserta->update([
            'status_pendaftaran' => 'Diterima',
            'tanggal_diterima' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Peserta berhasil diterima!');
    }

    /**
     * Reject a participant registration.
     */
    public function rejectPeserta($pelatihanId, $pesertaId)
    {
        $peserta = PelatihanPeserta::where('id_pelatihan', $pelatihanId)
            ->where('id', $pesertaId)
            ->firstOrFail();

        $peserta->update([
            'status_pendaftaran' => 'Ditolak',
        ]);

        // Increase available quota back when rejected
        $pelatihan = Pelatihan::findOrFail($pelatihanId);
        $pelatihan->increment('sisa_kuota');

        return redirect()->back()
            ->with('success', 'Peserta berhasil ditolak!');
    }

    /**
     * Export participants list to Excel
     */
    public function exportPeserta($id)
    {
        $pelatihan = Pelatihan::with(['peserta.user'])->findOrFail($id);
        
        $fileName = 'Peserta_' . str_replace(' ', '_', $pelatihan->nama_pelatihan) . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($pelatihan) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Nama Peserta', 'Email', 'No. HP', 'Tanggal Daftar', 'Status', 'Alasan Mengikuti']);
            
            // Data
            $no = 1;
            foreach ($pelatihan->peserta as $peserta) {
                fputcsv($file, [
                    $no++,
                    $peserta->user->nama ?? '-',
                    $peserta->user->email ?? '-',
                    $peserta->user->no_hp ?? '-',
                    $peserta->created_at->format('d M Y H:i'),
                    $peserta->status,
                    $peserta->alasan_mengikuti ?? '-',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate certificate PDF for participant
     */
    public function generateSertifikat($pelatihanId, $pesertaId)
    {
        $peserta = PelatihanPeserta::with(['pelatihan', 'user'])
            ->where('id_pelatihan', $pelatihanId)
            ->where('id_peserta_pelatihan', $pesertaId)
            ->firstOrFail();

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pelatihan.sertifikat', [
            'peserta' => $peserta,
            'pelatihan' => $peserta->pelatihan,
            'user' => $peserta->user,
        ]);

        $fileName = 'Sertifikat_' . str_replace(' ', '_', $peserta->user->nama) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Upload certificate file for participant
     */
    public function uploadSertifikat(Request $request, $pelatihanId, $pesertaId)
    {
        $request->validate([
            'file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);

        $peserta = PelatihanPeserta::where('id_pelatihan', $pelatihanId)
            ->where('id_peserta_pelatihan', $pesertaId)
            ->firstOrFail();

        // Delete old file if exists
        if ($peserta->file_sertifikat && \Storage::disk('public')->exists($peserta->file_sertifikat)) {
            \Storage::disk('public')->delete($peserta->file_sertifikat);
        }

        // Store new file
        $path = $request->file('file_sertifikat')->store('sertifikat', 'public');
        
        $peserta->update([
            'file_sertifikat' => $path,
        ]);

        return redirect()->back()->with('success', 'Sertifikat berhasil diupload!');
    }

    /**
     * Delete certificate file
     */
    public function deleteSertifikat($pelatihanId, $pesertaId)
    {
        $peserta = PelatihanPeserta::where('id_pelatihan', $pelatihanId)
            ->where('id_peserta_pelatihan', $pesertaId)
            ->firstOrFail();

        if ($peserta->file_sertifikat && \Storage::disk('public')->exists($peserta->file_sertifikat)) {
            \Storage::disk('public')->delete($peserta->file_sertifikat);
        }

        $peserta->update([
            'file_sertifikat' => null,
        ]);

        return redirect()->back()->with('success', 'Sertifikat berhasil dihapus!');
    }
}
