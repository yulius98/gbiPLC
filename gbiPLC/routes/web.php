
<?php

use App\Http\Controllers\AuthLogin;
use App\Http\Controllers\IbadahRaya;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegController;
use App\Http\Controllers\YouthController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ListKomselController;
use App\Http\Controllers\PageJemaatController;
use App\Http\Controllers\ChunkUploadController;
use App\Http\Controllers\MateriKomselController;
use App\Http\Controllers\MateriKotbahController;
use App\Http\Controllers\YouthProgramController;
use App\Http\Controllers\ResetPasswordController;

// All web routes should be within web middleware group
Route::middleware(['web'])->group(function () {
    // Public Routes
    Route::get('/', [WelcomeController::class, 'index'])->name('home');
    Route::get('/register', function () {
        return view('jemaat.daftar');
    })->name('register');
    Route::get('/ibadah-raya',[IbadahRaya::class,'index'])->name('ibadah-raya');
    Route::get('/ibadah-raya/getlink',[IbadahRaya::class,'getLink'])->name('ibadah-raya.getlink');
    Route::get('/youth', [YouthController::class, 'index'])->name('youth');
    Route::get('/event', [JemaatController::class, 'index'])->name('event');
    Route::get('/Daftar', [RegController::class, 'showJemaat']);
    Route::get('/list-komsel',[ListKomselController::class,'index'])->name('list-komsel');
    Route::get('/materi-komsel',[MateriKomselController::class,'index'])->name('materi-komsel');
    Route::get('/materi-komsel/getlink',[MateriKomselController::class,'getLink'])->name('materi-komsel.getlink');

    
    // Materi Kotbah Routes
    Route::get('/materi-kotbah', [MateriKotbahController::class, 'index'])->name('materi-kotbah');
    Route::get('/materi-kotbah/download/{id}', [MateriKotbahController::class, 'download'])->name('materi-kotbah.download');

    // Authentication Routes
    Route::get('/login', function () {
        return view('login');
    })->name('login')->middleware('guest');

    Route::post('/login', [AuthLogin::class, 'authenticateUser'])->name('login.post')->middleware('guest');
    Route::post('/Daftar', [RegController::class, 'RegJemaat'])->middleware('guest');

    // Password Reset Routes
    Route::middleware('guest')->group(function () {
        Route::get('/forgot-password', function () {
            return view('auth.forgot-password');
        })->name('password.request');

        Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])
             ->middleware('throttle:6,1')
             ->name('password.email');

        Route::get('/reset-password/{token}', function (string $token) {
            return view('auth.reset-password', ['token' => $token]);
        })->name('password.reset');

        Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
             ->name('password.update');
    });

    // Logout route (can be accessed by authenticated users)
    Route::post('/logout', [AuthLogin::class, 'logout'])->name('logout');
    
    // JWT Test route (for debugging, remove in production)
    Route::get('/jwt-test', [App\Http\Controllers\JWTTestController::class, 'testToken']);

    // Protected Jemaat Routes (hanya untuk jemaat)
    Route::group(['middleware' => ['role:jemaat'], 'prefix' => 'jemaat'], function() {
	    Route::get('/page-jemaat', [PageJemaatController::class, 'index'])->name('page-jemaat');
        Route::get('/myprofile', [PageJemaatController::class, 'myProfile'])->name('myprofile');
        Route::get('/myprofile/edit', [PageJemaatController::class, 'editProfile'])->name('myprofile.edit');
        Route::put('/myprofile/update', [PageJemaatController::class, 'updateProfile'])->name('myprofile.update');
    });

    // Protected Admin Routes (hanya untuk pengurus)
    Route::group(['middleware' => ['role:pengurus'], 'prefix' => 'pengurus'], function () {
        // Chunk upload routes untuk file besar
        Route::match(['get', 'post'], '/chunk-upload', [ChunkUploadController::class, 'upload'])->name('chunk.upload');
        Route::post('/chunk-cleanup', [ChunkUploadController::class, 'cleanup'])->name('chunk.cleanup');
        
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

        Route::get('/materi_kotbah', function () {
            return view('pengurus.materi_kotbah');
        });

        Route::get('/link_ibadah', function () {
            return view('pengurus.link_ibadah');
        });

        Route::get('/list_komsel', function () {
            return view('pengurus.list_komsel');
        });

        Route::get('/materi_komsel', function () {
            return view('pengurus.materi_komsel');
        });

        Route::get('/youth_gallery', function () {
            return view('pengurus.youth_gallery');
        });
    });
});
