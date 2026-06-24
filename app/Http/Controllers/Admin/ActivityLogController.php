<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('keyword')) {

            $keyword = $request->keyword;

            $query->where('activity', 'like', "%{$keyword}%");
        }

        $logs = $query
            ->latest()
            ->get();

        return view(
            'admin.activity_logs.index',
            compact('logs')
        );
    }
}