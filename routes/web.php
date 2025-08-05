<?php

use App\Http\Controllers\AuthLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\WelcomeController;

// All web routes should be within web middleware group
Route::middleware(['web'])->group(function () {
    // Public Routes
    Route::get('/', [WelcomeController::class, 'index'])->name('home');
    Route::get('/register', function () {
        return view('jemaat.daftar');
    })->name('register');
    Route::get('/event', [JemaatController::class, 'Data_Jemaat'])->name('event');
    Route::get('/Daftar', [RegController::class, 'showJemaat']);

    // Authentication Routes
    Route::get('/login', function () {
        return view('login');
    })->name('login')->middleware('guest');

    Route::post('/login', [AuthLogin::class, 'AuthUser'])->name('login.post')->middleware('guest');
    Route::post('/Daftar', [RegController::class, 'RegJemaat'])->middleware('guest');

    // Logout route (can be accessed by authenticated users)
    Route::post('/logout', [AuthLogin::class, 'logout'])->name('logout')->middleware('auth');

    // Protected Admin Routes (hanya untuk pengurus)
    Route::group(['middleware' => ['role:pengurus'], 'prefix' => 'pengurus'], function () {
        Route::get('/dashboard_admin/{name_admin}', function () {
            return view('pengurus.dashboard_admin');
        });
        
        Route::get('/dashboard_timbesuk', function () {
            return view('pengurus.dashboard_timbesuk');
        });

        Route::get('/dashboard_timmultimedia', function () {
            return view('pengurus.dashboard_timmultimedia');
        });

        Route::get('/dashboard_popup', function () {
            return view('pengurus.dashboard_popup');
        });
        
        Route::get('/pendaftara', function () {
            return view('pengurus.pendaftaran');
        });

        Route::get('/kunjungan', function () {
            return view('pengurus.data_kunjungan_jemaat');
        });

        Route::get('/pastor_note', function () {
            return view('pengurus.pastor_note');
        });
    });
});