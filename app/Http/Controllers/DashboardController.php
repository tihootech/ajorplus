<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function loadDashboard()
    {
        $user = auth()->user();
        dd($user);
    }
}
