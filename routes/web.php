<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('pengguna/data', [UserController::class, 'data'])->name('pengguna.data');
    Route::resource('pengguna', UserController::class);

    Route::get('master-periode/data', [App\Http\Controllers\MasterPeriodeController::class, 'data'])->name('master-periode.data');
    Route::get('master-periode/active-info', [App\Http\Controllers\MasterPeriodeController::class, 'activeInfo'])->name('master-periode.active-info');
    Route::resource('master-periode', App\Http\Controllers\MasterPeriodeController::class)->except(['create', 'show', 'destroy']);

    Route::get('lembaga/data', [App\Http\Controllers\LembagaController::class, 'data'])->name('lembaga.data');
    Route::resource('lembaga', App\Http\Controllers\LembagaController::class);

    Route::get('kota/data', [App\Http\Controllers\KotaController::class, 'data'])->name('kota.data');
    Route::get('/kota', [App\Http\Controllers\KotaController::class, 'index'])->name('kota.index');

    Route::get('provinsi/data', [App\Http\Controllers\ProvinsiController::class, 'data'])->name('provinsi.data');
    Route::get('/provinsi', [App\Http\Controllers\ProvinsiController::class, 'index'])->name('provinsi.index');

    Route::get('kecamatan/data', [App\Http\Controllers\KecamatanController::class, 'data'])->name('kecamatan.data');
    Route::get('/kecamatan', [App\Http\Controllers\KecamatanController::class, 'index'])->name('kecamatan.index');

    Route::get('desa/data', [App\Http\Controllers\DesaController::class, 'data'])->name('desa.data');
    Route::get('/desa', [App\Http\Controllers\DesaController::class, 'index'])->name('desa.index');

    Route::get('/santri', [App\Http\Controllers\SantriController::class, 'index'])->name('santri.index');
    Route::get('santri/data', [App\Http\Controllers\SantriController::class, 'data'])->name('santri.data');
    Route::post('santri/sync', [App\Http\Controllers\SantriController::class, 'sync'])->name('santri.sync');

    Route::get('/beasiswa', [App\Http\Controllers\BeasiswaController::class, 'index'])->name('beasiswa.index');
    Route::get('/beasiswa/create', [App\Http\Controllers\BeasiswaController::class, 'create'])->name('beasiswa.create');
    Route::post('/beasiswa', [App\Http\Controllers\BeasiswaController::class, 'store'])->name('beasiswa.store');
    Route::get('/beasiswa/{id}/edit', [App\Http\Controllers\BeasiswaController::class, 'edit'])->name('beasiswa.edit');
    Route::get('/beasiswa/{id}/pdf', [App\Http\Controllers\BeasiswaController::class, 'cetakPdf'])->name('beasiswa.pdf');
    Route::put('/beasiswa/{id}', [App\Http\Controllers\BeasiswaController::class, 'update'])->name('beasiswa.update');
    Route::get('/beasiswa/search-santri', [App\Http\Controllers\BeasiswaController::class, 'searchSantri'])->name('beasiswa.search-santri');
    Route::get('/beasiswa/santri/{nis}', [App\Http\Controllers\BeasiswaController::class, 'getSantriDetail']);
    
    Route::get('beasiswa/data', [App\Http\Controllers\BeasiswaController::class, 'data'])->name('beasiswa.data');
    Route::get('beasiswa/{id}/dokumen', [App\Http\Controllers\BeasiswaController::class, 'getDokumen'])->name('beasiswa.dokumen.list');
    Route::post('beasiswa/{id}/dokumen', [App\Http\Controllers\BeasiswaController::class, 'uploadDokumen'])->name('beasiswa.dokumen.upload');
    Route::get('berkas-lampiran/{id}', [App\Http\Controllers\BeasiswaController::class, 'lihatDokumen'])->name('dokumen.lihat');
    Route::get('/beasiswa/{id}', [App\Http\Controllers\BeasiswaController::class, 'show'])->name('beasiswa.show');
    
    // Status & Histori
    Route::get('beasiswa/{id}/histori-status', [App\Http\Controllers\BeasiswaController::class, 'getHistoriStatus'])->name('beasiswa.histori');
    Route::post('beasiswa/{id}/status', [App\Http\Controllers\BeasiswaController::class, 'updateStatus'])->name('beasiswa.status.update');

    Route::get('histori-status/data', [App\Http\Controllers\HistoriStatusController::class, 'data'])->name('histori-status.data');
    Route::resource('histori-status', App\Http\Controllers\HistoriStatusController::class)->except(['create', 'store', 'show']);

    // Laporan & Rekapitulasi
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data', [App\Http\Controllers\LaporanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/summary', [App\Http\Controllers\LaporanController::class, 'summary'])->name('laporan.summary');
    Route::get('/laporan/export-excel', [App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/cetak-pdf', [App\Http\Controllers\LaporanController::class, 'cetakPdf'])->name('laporan.cetak.pdf');

    // Region APIs
    Route::get('/api/regions/provinsi', [App\Http\Controllers\RegionController::class, 'getProvinsi']);
    Route::get('/api/regions/kota/{provinsi_id}', [App\Http\Controllers\RegionController::class, 'getKota']);
    Route::get('/api/regions/kecamatan/{kota_id}', [App\Http\Controllers\RegionController::class, 'getKecamatan']);
    Route::get('/api/regions/desa/{kecamatan_id}', [App\Http\Controllers\RegionController::class, 'getDesa']);
});
