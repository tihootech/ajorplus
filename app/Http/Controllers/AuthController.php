<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function doLogin(Request $request)
    {
        // validate incoming request
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::whereEmail($request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return ['succes' => true, 'message' => __('LOGIN_SUCCESS')];
        }else {
            return ['succes' => false, 'message' => __('WRONG_USER_PASS')];
        }

    }

    public function logout()
    {
        $user = auth()->user();
        Auth::logout($user);
        return redirect()->route('login');
    }
}
