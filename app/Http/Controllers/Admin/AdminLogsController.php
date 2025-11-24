<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Filter by user type
        if ($request->has('user_type') && $request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search in description
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Get paginated results
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get unique action types for filter
        $actionTypes = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.logs.index', compact('logs', 'actionTypes'));
    }

    public function show($id)
    {
        $log = ActivityLog::findOrFail($id);
        return view('admin.logs.show', compact('log'));
    }

    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.logs.index')
            ->with('success', 'Log berhasil dihapus');
    }

    public function clear(Request $request)
    {
        $days = $request->input('days', 30);
        
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('admin.logs.index')
            ->with('success', "Berhasil menghapus {$deleted} log yang lebih dari {$days} hari");
    }
}
