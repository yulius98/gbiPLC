<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AuthLoginController;
use App\Http\Controllers\Api\BirthdayController;
use App\Http\Controllers\Api\CarouselController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\IbadahRayaController;
use App\Http\Controllers\Api\LifeGroupController;
use App\Http\Controllers\Api\MateriKotbahController;
use App\Http\Controllers\Api\MyProfileController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PastorNoteController;
use App\Http\Controllers\Api\ReadingController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

use GuzzleHttp\Exception\RequestException;


Route::get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'user' => auth('api')->user()
    ]);
})->middleware('auth:api');

Route::resource('/pastornote',PastorNoteController::class);
Route::resource('/birthday',BirthdayController::class);
Route::resource('/event',EventController::class);
Route::resource('/register',RegisterController::class);
Route::resource('/carousel',CarouselController::class);
Route::resource('/lifegroup',LifeGroupController::class);
Route::get('/materi-kotbah',[MateriKotbahController::class,'index'])->name('materi-kotbah');
Route::get('/materi-kotbah/getlink/',[MateriKotbahController::class,'GetLinkKotbah'])->name('materi-kotbah.GetLinkKotbah');
Route::get('ibadahraya',[IbadahRayaController::class,'getLink'])->name('ibadahraya.getLink');
Route::post('/login',[AuthLoginController::class, 'login']);
Route::post('/refresh',[AuthLoginController::class, 'refresh']);
Route::post('/logout',[AuthLoginController::class, 'logout']);
Route::post('/forgot-password',[PasswordResetController::class,'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password',[PasswordResetController::class,'resetPassword'])->name('password.update');

Route::middleware(['auth:api','role:pengurus'])->get('/pengurus/dashboard', function() {
    return response()->json(['message'=>'Dashboard Pengurus']);
});

Route::middleware(['auth:api','role:jemaat,pengurus'])->get('/myprofile/{id}', [MyProfileController::class,'show']);
Route::middleware(['auth:api','role:jemaat,pengurus'])->get('/myprofile/{id}/edit', [MyProfileController::class,'edit']);
Route::middleware(['auth:api','role:jemaat,pengurus'])->put('/myprofile/{id}', [MyProfileController::class, 'update']);
Route::middleware(['auth:api','role:pengurus'])->get('dashboard/{id}',[AdminDashboardController::class,'index']);
Route::middleware(['auth:api','role:jemaat,pengurus'])->get('/reading/today',[ReadingController::class,'today']);
Route::middleware(['auth:api','role:jemaat,pengurus'])->post('/reading/start-date',[ReadingController::class,'setStartDate']);
Route::middleware(['auth:api','role:jemaat,pengurus'])->put('/reading/start-date',[ReadingController::class,'updateStartDate']);



