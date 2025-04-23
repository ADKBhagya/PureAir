<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        // Add logic like loading sensors, alerts, etc.
        return view('pages.admin.dashboard');
    }
}
