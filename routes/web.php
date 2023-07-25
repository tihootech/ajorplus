<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/dashboard');

Route::get('login', 'AuthController@loginForm')->middleware('guest')->name('login');
Route::post('login', 'AuthController@doLogin')->middleware('guest')->name('do_login');

Route::prefix('dashboard')->middleware('auth')->group(function () {

    Route::get('/', 'DashboardController@loadDashboard')->name('dashboard');

    Route::prefix('admin')->middleware('admin')->group(function () {

        Route::get('/', 'AdminDashboardController@home')->name('admin.home');

    });

    Route::prefix('viewer')->middleware('viewer')->group(function () {

        Route::get('/', 'ViewerDashboardController@home')->name('viewer.home');

    });

});
