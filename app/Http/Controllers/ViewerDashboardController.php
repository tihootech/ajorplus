<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewerDashboardController extends Controller
{
    public function home()
    {
        return Inertia::render('Dashboard/Viewer/Home.vue');
    }
}
