<?php

use App\Http\Controllers\AuthLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegController;
use App\Http\Controllers\JemaatController;


Route::get('/', function () {
    return view('welcome');
});

//Route::get('/', function () {
//    return view('jemaat.daftar');
//});

Route::get('/register', function () {
    return view('jemaat.daftar');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/pengurus/dashboard_admin/{name_admin}', function () {
    return view('pengurus.dashboard_admin');
});

Route::get('/pengurus/pendaftara', function () {
    return view('pengurus.pendaftaran');
});

Route::get('/pengurus/kunjungan', function () {
    return view('pengurus.data_kunjungan_jemaat');
});

Route::get('/data-jemaat',[JemaatController::class,'Data_Jemaat']);
Route::get('/Daftar', [RegController::class, 'showJemaat']);



Route::post('/Daftar', [RegController::class, 'RegJemaat']);
Route::post('/Login', [AuthLogin::class,'AuthUser']);