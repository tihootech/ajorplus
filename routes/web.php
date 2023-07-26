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

    Route::get('/', 'DashboardController@home')->name('dashboard');
    Route::get('qomers', 'DashboardController@qomers')->name('dashboard.qomers');
    Route::get('data', 'DashboardController@data')->name('dashboard.data');

    Route::prefix('admin')->middleware('admin')->group(function () {

        Route::get('users', 'DashboardController@users')->name('dashboard.users');

        Route::prefix('user')->group(function () {
            Route::post('/', 'UsersController@store')->name('user.store');
            Route::put('{user}', 'UsersController@update')->name('user.update');
            Route::delete('{user}', 'UsersController@destroy')->name('user.destroy');
        });

        Route::prefix('mounth')->group(function () {
            Route::post('/', 'MounthsController@store')->name('mounth.store');
            Route::put('{mounth}', 'MounthsController@update')->name('mounth.update');
            Route::delete('{mounth}', 'MounthsController@destroy')->name('mounth.destroy');
        });

    });

    Route::get('logout', 'AuthController@logout')->name('logout');

});
