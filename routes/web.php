<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaliController;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::group([
    'middleware' => ['auth', 'role:admin,operator,wali'],
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => ['role:admin,operator']], function () {
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::get('ajax/users/roles/search', [UserController::class, 'searchRole'])->name('users.roles.search');
        Route::resource('/users', UserController::class);
    });

    Route::group([
        'middleware' => ['role:admin'],
    ], function () {
        //
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/setting/{setting}', [SettingController::class, 'update'])
            ->name('setting.update');

        // Route Wali
        Route::get('/wali/data', [WaliController::class, 'data'])->name('wali.data');
        Route::resource('wali', WaliController::class);
    });
});
