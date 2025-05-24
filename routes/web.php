<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\AgendaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\KlasifikasiSuratController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\TimKerjaController;
use App\Models\TimKerja;
use App\Models\KlasifikasiSurat;
use App\Http\Controllers\SuratKeluarController;


Route::get('/', function () { return redirect('/login'); });

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Surat Keluar
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::get('surat-keluar/export/pdf', [SuratKeluarController::class, 'exportPdf'])->name('surat-keluar.export.pdf');
    Route::get('surat-keluar/export/excel', [SuratKeluarController::class, 'exportExcel'])->name('surat-keluar.export.excel');
    Route::post('/surat-keluar/{id}/setujui', [SuratKeluarController::class, 'setujuiSurat'])->name('surat-keluar.setujui');
    Route::post('/surat-keluar/{id}/tolak', [SuratKeluarController::class, 'tolakSurat'])->name('surat-keluar.tolak');
    Route::post('/surat-keluar/{id}/kirim-ulang', [SuratKeluarController::class, 'kirimUlang'])->name('surat-keluar.kirim-ulang');

    
    //Route::get('/surat-keluar/preview/{filename}', [SuratKeluarController::class, 'preview'])->name('surat-keluar.preview');

    //Route::get('/surat-keluar/cetak-pdf', [SuratKeluarController::class, 'cetakPDF'])->name('surat-keluar.cetak-pdf');
    //Route::get('/surat-keluar/export-excel', [SuratKeluarController::class, 'exportExcel'])->name('surat-keluar.export-excel');
    
    // Rute yang hanya bisa diakses oleh admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::resource('surat', SuratController::class);
        Route::get('/surat/disposisi/{id}', [SuratController::class, 'disposisi'])->name('surat.disposisi');
        Route::get('/surat/cari', [SuratController::class, 'search'])->name('surat.search');
        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::get('/agenda/cetak', [AgendaController::class, 'cetak'])->name('agenda.cetak');
        Route::resource('users', UserController::class); //atur user
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::resource('klasifikasi', KlasifikasiSuratController::class);
        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::get('/agenda/export-pdf', [AgendaController::class, 'exportPDF'])->name('agenda.export.pdf');
        Route::get('/agenda/export-excel', [AgendaController::class, 'exportExcel'])->name('agenda.export.excel');
        Route::get('/agenda/surat-keluar', [App\Http\Controllers\AgendaController::class, 'suratKeluar'])->name('agenda.surat.keluar');
        Route::get('/agenda/surat-keluar/pdf', [App\Http\Controllers\AgendaController::class, 'exportSuratKeluarPdf'])->name('agenda.surat.keluar.pdf');
        Route::get('/agenda/surat-keluar/excel', [App\Http\Controllers\AgendaController::class, 'exportSuratKeluarExcel'])->name('agenda.surat.keluar.excel');


        // Routes untuk Bidang
        Route::get('/bidang', [BidangController::class, 'index'])->name('bidang.index');
        Route::post('/bidang', [BidangController::class, 'store'])->name('bidang.store');
        Route::get('/bidang/{id}/edit', [BidangController::class, 'edit'])->name('bidang.edit');
        Route::put('/bidang/{id}', [BidangController::class, 'update'])->name('bidang.update');
        Route::delete('/bidang/{id}', [BidangController::class, 'destroy'])->name('bidang.destroy');

        // Routes untuk Tim Kerja
        Route::get('/timkerja', [TimKerjaController::class, 'index'])->name('timkerja.index');
        Route::post('/timkerja', [TimKerjaController::class, 'store'])->name('timkerja.store');
        Route::get('/timkerja/{id}/edit', [TimKerjaController::class, 'edit'])->name('timkerja.edit');
        Route::put('/timkerja/{id}', [TimKerjaController::class, 'update'])->name('timkerja.update');
        Route::delete('/timkerja/{id}', [TimKerjaController::class, 'destroy'])->name('timkerja.destroy');

        Route::get('/get-bidang', [BidangController::class, 'search'])->name('get.bidang');
        Route::get('/get-tim-kerja/{bidang_id}', [TimKerjaController::class, 'getByBidang']);
        Route::get('/get-klasifikasi/{tim_kerja_id}', [KlasifikasiSuratController::class, 'getByTimKerja']);
        //Route::post('/klasifikasi/store', [KlasifikasiSuratController::class, 'store'])->name('klasifikasi.store');
        Route::get('/get-users-by-klasifikasi/{id}', [SuratController::class, 'getUsersByKlasifikasi']);
        // Cetak excel surat masuk Untuk admin
        Route::get('/surat-masuk/export-excel', [SuratController::class, 'exportExcel'])->name('surat-masuk.export-excel');
        Route::get('surat-masuk/export/pdf', [SuratController::class, 'exportPdf'])->name('surat-masuk.export.pdf');
        Route::get('/pemberi-disposisi', [SuratController::class, 'indexPemberiDisposisi'])->name('surat.pemberi-disposisi');
        Route::post('/disposisi/{id}', [SuratController::class, 'disposisiSurat'])->name('surat.disposisi');    
    
    });

    Route::middleware(['checkrole:staff'])->group(function () {
        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
        Route::get('/staff/surat', [SuratController::class, 'index'])->name('staff.surat.index');
        Route::get('/staff/surat/{id}/edit', [SuratController::class, 'edit'])->name('staff.surat.edit');
        Route::put('/staff/surat/{id}', [SuratController::class, 'update'])->name('staff.surat.update');
        Route::post('/staff/surat/{id}/terima', [SuratController::class, 'terimaSurat']);
        Route::post('/staff/surat/{id}/tindaklanjuti', [SuratController::class, 'tindaklanjutiSurat']);
        Route::get('/staff/surat-masuk', [StaffDashboardController::class, 'suratMasuk'])->name('staff.surat-masuk');
        //route ubah password
        Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('/change-password', [UserController::class, 'updatePassword'])->name('password.update');
        Route::get('/staff/export-excel', [StaffDashboardController::class, 'exportExcel'])->name('staff.export.excel');
        
    });

    Route::middleware(['checkrole:kepala_bidang'])->group(function () {
        
        Route::get('/kepala/dashboard', [DashboardController::class, 'kepalaBidangDashboard'])->name('kepala.dashboard');
        Route::post('/kepala-bidang/disposisi', [App\Http\Controllers\DisposisiController::class, 'disposisiOlehKepala'])->name('disposisi.kepala');
        
    });

});