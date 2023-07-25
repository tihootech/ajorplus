<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function home()
    {
        return Inertia::render('Dashboard/Admin/Home');
    }

    public function qomers()
    {
        return Inertia::render('Dashboard/Admin/Qomers');
    }

    public function data()
    {
        return Inertia::render('Dashboard/Admin/Data');
    }

    public function users()
    {
        // TODO: use pagination for users
        $users = User::where('id', '!=', auth()->id())->latest()->get();
        return Inertia::render('Dashboard/Admin/Users', compact('users'));
    }

}
