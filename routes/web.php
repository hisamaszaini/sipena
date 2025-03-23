<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaCabangController;
use App\Http\Controllers\AnggotaDaerahController;
use App\Http\Controllers\AnggotaRantingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BiodataController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DaerahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RantingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Route::get('/profile', function () {
    //     return view('profile.edit', ['title' => 'Edit Profile']);
    // })->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
});


Route::prefix('admin')->middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/delete/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
});

Route::prefix('operator')->middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/', [OperatorController::class, 'index'])->name('operator.index');
    Route::post('/store', [OperatorController::class, 'store'])->name('operator.store');
    Route::get('/edit/{id}', [OperatorController::class, 'edit'])->name('operator.edit');
    Route::put('/update/{id}', [OperatorController::class, 'update'])->name('operator.update');
    Route::delete('/delete/{id}', [OperatorController::class, 'destroy'])->name('operator.destroy');
    Route::get('/export', [OperatorController::class, 'export'])->name('operator.export');
});

Route::prefix('kecamatan')->middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/', [KecamatanController::class, 'index'])->name('kecamatan.index');
    Route::post('/store', [KecamatanController::class, 'store'])->name('kecamatan.store');
    Route::get('/edit/{id}', [KecamatanController::class, 'edit'])->name('kecamatan.edit');
    Route::put('/update/{id}', [KecamatanController::class, 'update'])->name('kecamatan.update');
    Route::delete('/delete/{id}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
    Route::get('/export', [KecamatanController::class, 'export'])->name('kecamatan.export');
});

Route::prefix('desa')->middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/', [DesaController::class, 'index'])->name('desa.index');
    Route::post('/store', [DesaController::class, 'store'])->name('desa.store');
    Route::get('/edit/{id}', [DesaController::class, 'edit'])->name('desa.edit');
    Route::put('/update/{id}', [DesaController::class, 'update'])->name('desa.update');
    Route::delete('/delete/{id}', [DesaController::class, 'destroy'])->name('desa.destroy');
    Route::get('/export', [DesaController::class, 'export'])->name('desa.export');
});

Route::prefix('daerah')->middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/', [DaerahController::class, 'index'])->name('daerah.index');
    Route::get('/edit', [DaerahController::class, 'edit'])->name('daerah.edit');
    Route::put('/update', [DaerahController::class, 'update'])->name('daerah.update');
});

Route::prefix('cabang')->middleware(['auth'])->group(function () {
    Route::get('/', [CabangController::class, 'index'])->name('cabang.index');
    Route::post('/store', [CabangController::class, 'store'])->name('cabang.store');
    Route::get('/edit/{id}', [CabangController::class, 'edit'])->name('cabang.edit');
    Route::put('/update/{id}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/delete/{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');
    Route::get('/export', [CabangController::class, 'export'])->name('cabang.export');
});

Route::prefix('ranting')->middleware(['auth'])->group(function () {
    Route::get('/', [RantingController::class, 'index'])->name('ranting.index');
    Route::post('/store', [RantingController::class, 'store'])->name('ranting.store');
    Route::get('/edit/{id}', [RantingController::class, 'edit'])->name('ranting.edit');
    Route::put('/update/{id}', [RantingController::class, 'update'])->name('ranting.update');
    Route::delete('/delete/{id}', [RantingController::class, 'destroy'])->name('ranting.destroy');
    Route::get('/export', [RantingController::class, 'export'])->name('ranting.export');
});

Route::prefix('anggotadaerah')->middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/', [AnggotaDaerahController::class, 'index'])->name('anggotadaerah.index');
    Route::post('/store', [AnggotaDaerahController::class, 'store'])->name('anggotadaerah.store');
    Route::get('/edit/{id}', [AnggotaDaerahController::class, 'edit'])->name('anggotadaerah.edit');
    Route::put('/update/{id}', [AnggotaDaerahController::class, 'update'])->name('anggotadaerah.update');
    Route::delete('/delete/{id}', [AnggotaDaerahController::class, 'destroy'])->name('anggotadaerah.destroy');
    Route::get('/export', [AnggotaDaerahController::class, 'export'])->name('anggotadaerah.export');
});

Route::prefix('anggotacabang')->middleware(['auth'])->group(function () {
    Route::get('/', [AnggotaCabangController::class, 'index'])->name('anggotacabang.index');
    Route::post('/store', [AnggotaCabangController::class, 'store'])->name('anggotacabang.store');
    Route::get('/edit/{id}', [AnggotaCabangController::class, 'edit'])->name('anggotacabang.edit');
    Route::put('/update/{id}', [AnggotaCabangController::class, 'update'])->name('anggotacabang.update');
    Route::delete('/delete/{id}', [AnggotaCabangController::class, 'destroy'])->name('anggotacabang.destroy');
    Route::get('/export', [AnggotaCabangController::class, 'export'])->name('anggotacabang.export');
});

Route::prefix('anggotaranting')->middleware(['auth'])->group(function () {
    Route::get('/', [AnggotaRantingController::class, 'index'])->name('anggotaranting.index');
    Route::post('/store', [AnggotaRantingController::class, 'store'])->name('anggotaranting.store');
    Route::get('/edit/{id}', [AnggotaRantingController::class, 'edit'])->name('anggotaranting.edit');
    Route::put('/update/{id}', [AnggotaRantingController::class, 'update'])->name('anggotaranting.update');
    Route::delete('/delete/{id}', [AnggotaRantingController::class, 'destroy'])->name('anggotaranting.destroy');
    Route::get('/export', [AnggotaRantingController::class, 'export'])->name('anggotaranting.export');
});

Route::get('/biodata-autocomplete', [BiodataController::class, 'autocomplete'])->name('biodata.autocomplete');
Route::get('/demografi-chart', [DashboardController::class, 'chartData'])->name('chartData');

require __DIR__.'/auth.php';
