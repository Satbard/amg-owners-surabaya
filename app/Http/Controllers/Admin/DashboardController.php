<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [

            'total' => Registration::count(),

            'pending' => Registration::where(
                'membership_status',
                'Pending'
            )->count(),

            'approved' => Registration::where(
                'membership_status',
                'Approved'
            )->count(),

            'rejected' => Registration::where(
                'membership_status',
                'Rejected'
            )->count(),

            'totalEvents' => Event::count(),

            'upcomingEvents' => Event::whereIn('status', [
                'upcoming', 'ongoing',
            ])->count(),

            'latestEvents' => Event::latest()->limit(5)->get(),
        ]);
    }
}
