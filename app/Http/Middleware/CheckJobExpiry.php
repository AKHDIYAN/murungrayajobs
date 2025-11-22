<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pekerjaan;
use Carbon\Carbon;

class CheckJobExpiry
{
    /**
     * Handle an incoming request.
     * Auto-update expired jobs
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Update status pekerjaan yang sudah berakhir
        Pekerjaan::where('tanggal_expired', '<', Carbon::today())
                 ->where('status', '!=', 'Ditolak')
                 ->update(['status' => 'Ditolak']);

        return $next($request);
    }
}
