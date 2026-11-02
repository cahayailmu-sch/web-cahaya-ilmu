<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PpdbRegistrationController;
use App\Http\Controllers\Admin\PpdbSettingController;
use App\Http\Controllers\Admin\ReferralCodeController as AdminReferralCodeController;
use App\Http\Controllers\Admin\SchoolAgendaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ReferralCodeController;
use App\Http\Controllers\SpmbFullFormController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/info-sekolah', [FrontController::class, 'infoIndex'])->name('info.index');
Route::get('/info-sekolah/{slug}', [FrontController::class, 'infoShow'])->name('info.show');
Route::get('/galeri-sekolah', [FrontController::class, 'galleryIndex'])->name('gallery.index');
Route::get('/jenjang/{slug}', [FrontController::class, 'jenjangShow'])->name('jenjang.show');
Route::get('/tentang-kami', [FrontController::class, 'about'])->name('about');
Route::get('/program-unggulan', [FrontController::class, 'programs'])->name('programs');
Route::get('/ppdb', [FrontController::class, 'ppdb'])->name('ppdb');
Route::post('/ppdb/store', [FrontController::class, 'storePpdb'])->name('ppdb.store');
Route::get('/ppdb/cek-status', [FrontController::class, 'ppdbStatus'])->name('ppdb.status');
Route::get('/ppdb/kartu/{registration_number}', [FrontController::class, 'ppdbCard'])->name('ppdb.card');
Route::match(['get', 'post'], '/spmb/check-referral', [ReferralCodeController::class, 'check'])
    ->middleware('throttle:30,1')
    ->name('spmb.check-referral');
Route::match(['get', 'post'], '/ppdb/check-referral', [ReferralCodeController::class, 'check'])
    ->middleware('throttle:30,1')
    ->name('ppdb.check-referral');

Route::redirect('/spmb', '/ppdb')->name('spmb');
Route::prefix('spmb/formulir-lengkap/{code}')->name('spmb.full-form')->group(function () {
    Route::get('/', [SpmbFullFormController::class, 'show']);
    Route::post('/', [SpmbFullFormController::class, 'store'])->name('.store')->middleware('throttle:10,1');
    Route::get('selesai', [SpmbFullFormController::class, 'success'])->name('.success');
    Route::get('cetak', [SpmbFullFormController::class, 'print'])->name('.print');
    Route::get('pdf', [SpmbFullFormController::class, 'pdf'])->name('.pdf');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::redirect('/', '/admin/dashboard')->name('home');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('ppdb', [PpdbRegistrationController::class, 'index'])->name('ppdb.index');
        Route::get('ppdb/export', [PpdbRegistrationController::class, 'export'])->name('ppdb.export');
        Route::get('ppdb-settings', [PpdbSettingController::class, 'index'])->name('ppdb-settings.index');
        Route::put('ppdb-settings', [PpdbSettingController::class, 'update'])->name('ppdb-settings.update');
        Route::get('referral-codes/generate', [AdminReferralCodeController::class, 'generate'])->name('referral-codes.generate');
        Route::post('referral-codes', [AdminReferralCodeController::class, 'store'])->name('referral-codes.store');
        Route::delete('referral-codes/{referralCode}', [AdminReferralCodeController::class, 'destroy'])->name('referral-codes.destroy');
        Route::post('referrals/reset-event-codes', [AdminReferralCodeController::class, 'resetEventCodes'])->name('referrals.reset-event');
        Route::match(['put', 'patch'], 'referrals/{referralCode}', [AdminReferralCodeController::class, 'update'])->name('referrals.update');
        Route::delete('referrals/{referralCode}', [AdminReferralCodeController::class, 'destroy'])->name('referrals.destroy');
        Route::post('referrals/{referralCode}/reset', [AdminReferralCodeController::class, 'reset'])->name('referrals.reset');
        Route::get('ppdb/{ppdbRegistration}', [PpdbRegistrationController::class, 'show'])->name('ppdb.show');
        Route::get('ppdb/{ppdbRegistration}/print', [PpdbRegistrationController::class, 'print'])->name('ppdb.print');
        Route::get('ppdb/{ppdbRegistration}/pdf', [PpdbRegistrationController::class, 'pdf'])->name('ppdb.pdf');
        Route::patch('ppdb/{ppdbRegistration}/status', [PpdbRegistrationController::class, 'updateStatus'])->name('ppdb.status');
        Route::delete('ppdb/{ppdbRegistration}', [PpdbRegistrationController::class, 'destroy'])->name('ppdb.destroy');

        Route::resource('posts', PostController::class)->except('show');
        Route::resource('galleries', GalleryController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
        Route::resource('agendas', SchoolAgendaController::class)->except(['create', 'show']);
        Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::resource('users', AdminUserController::class)->except(['create', 'show']);

        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('activity-logs/prune', [ActivityLogController::class, 'prune'])->name('activity-logs.prune');

        Route::get('backup/download', [BackupController::class, 'download'])->name('backup.download');

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
