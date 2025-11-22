<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Lamaran;
use App\Models\Pekerjaan;
use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->middleware('user');
        $this->applicationService = $applicationService;
    }

    /**
     * List all user applications
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();

        $query = Lamaran::with(['pekerjaan.perusahaan', 'pekerjaan.kecamatan', 'pekerjaan.kategori'])
                        ->where('id_user', $user->id_user);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        if ($sortBy === 'latest') {
            $query->orderBy('tanggal_terkirim', 'desc');
        } elseif ($sortBy === 'oldest') {
            $query->orderBy('tanggal_terkirim', 'asc');
        }

        $applications = $query->paginate(10)->appends(request()->query());

        return view('user.applications.index', compact('applications'));
    }

    /**
     * Show application detail
     */
    public function show($id)
    {
        $user = Auth::guard('web')->user();

        $application = Lamaran::with(['pekerjaan.perusahaan', 'pekerjaan.kecamatan', 'pekerjaan.kategori', 'user'])
                              ->where('id_user', $user->id_user)
                              ->findOrFail($id);

        return view('user.applications.show', compact('application'));
    }

    /**
     * Submit application
     */
    public function store(StoreApplicationRequest $request)
    {
        try {
            $user = Auth::guard('web')->user();

            // Check if job exists and active
            $pekerjaan = Pekerjaan::find($request->id_pekerjaan);
            if (!$pekerjaan || !$pekerjaan->is_aktif) {
                return back()->with('error', 'Lowongan tidak ditemukan atau sudah berakhir.')
                           ->withInput();
            }

            // Submit application using service
            $lamaran = $this->applicationService->submitApplication(
                $request->validated(),
                $user
            );

            return redirect()->route('user.applications.show', $lamaran->id_lamaran)
                           ->with('success', 'Lamaran berhasil dikirim! Tunggu konfirmasi dari perusahaan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim lamaran: ' . $e->getMessage())
                       ->withInput();
        }
    }
}
