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
        Route::get('/qomers', 'AdminDashboardController@qomers')->name('admin.qomers');
        Route::get('/users', 'AdminDashboardController@users')->name('admin.users');
        Route::get('/data', 'AdminDashboardController@data')->name('admin.data');

        Route::prefix('user')->group(function () {
            Route::post('/', 'UsersController@store')->name('user.store');
            Route::put('{user}', 'UsersController@update')->name('user.update');
            Route::delete('{user}', 'UsersController@destroy')->name('user.destroy');
        });

    });

    Route::prefix('viewer')->middleware('viewer')->group(function () {

        Route::get('/', 'ViewerDashboardController@home')->name('viewer.home');

    });

});
