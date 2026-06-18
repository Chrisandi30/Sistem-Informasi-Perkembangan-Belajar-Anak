<?php

use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PerkembanganController as AdminPerkembanganController;
use App\Http\Controllers\Admin\PerkembanganNonAkademisController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Guru\PerkembanganController;
use App\Http\Controllers\Guru\SiswaController as GuruSiswaController;
use App\Http\Controllers\KepalaSekolah\ReviewPerkembanganController as KepalaSekolahReviewPerkembanganController;
use App\Http\Controllers\OrangTua\LaporanController;
use App\Http\Controllers\OrangTua\PengumumanController as OrangTuaPengumumanController;
use App\Http\Controllers\OrangTua\PortalController;
use App\Http\Controllers\PublicMediaController;
use Illuminate\Support\Facades\Route;

Route::get('/media/{path}', [PublicMediaController::class, 'show'])
    ->where('path', '.*')
    ->name('media.public');

Route::redirect('/', '/dashboard');
Route::middleware('guest:admin,guru,kepala_sekolah,orang_tua')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:admin,guru,kepala_sekolah,orang_tua');

Route::middleware('auth:admin,guru,kepala_sekolah,orang_tua')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/home', '/');

    Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'role:admin'])->group(function () {
        Route::resource('kelas', KelasController::class)->except('show');
        Route::resource('guru', GuruController::class)->except('show');
        Route::resource('siswa', SiswaController::class);
        Route::resource('mata-pelajaran', MataPelajaranController::class)->except('show');
        Route::resource('perkembangan-non-akademis', PerkembanganNonAkademisController::class)->except(['show', 'store', 'update', 'destroy']);
        Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['show', 'store', 'update', 'destroy']);
        Route::resource('pengumuman', PengumumanController::class)->except('show');
        Route::resource('akun', UserController::class)->except('show');

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/kelas', [AdminLaporanController::class, 'kelas'])->name('kelas');
            Route::get('/kelas/cetak', [AdminLaporanController::class, 'printKelas'])->name('kelas.print');
            Route::get('/kelas/export-pdf', [AdminLaporanController::class, 'exportKelasPdf'])->name('kelas.export-pdf');
            Route::get('/perkembangan', [AdminLaporanController::class, 'perkembangan'])->name('perkembangan');
        });

        Route::get('/perkembangan', [AdminPerkembanganController::class, 'index'])->name('perkembangan.index');
        Route::get('/perkembangan/{perkembangan}/cetak', [AdminPerkembanganController::class, 'printByPerkembangan'])->name('perkembangan.print');
        Route::get('/perkembangan/{perkembangan}/export-pdf', [AdminPerkembanganController::class, 'exportByPerkembangan'])->name('perkembangan.export-single');
    });

    Route::prefix('guru')->name('guru.')->middleware(['auth:guru', 'role:guru'])->group(function () {
        Route::get('/siswa', [GuruSiswaController::class, 'index'])->name('siswa.index');
        Route::get('/perkembangan', [PerkembanganController::class, 'index'])->name('perkembangan.index');
        Route::get('/perkembangan/create', [PerkembanganController::class, 'create'])->name('perkembangan.create');
        Route::get('/perkembangan/{perkembangan}', [PerkembanganController::class, 'show'])->name('perkembangan.show');
        Route::get('/perkembangan/{perkembangan}/edit', [PerkembanganController::class, 'edit'])->name('perkembangan.edit');
        Route::delete('/perkembangan/{perkembangan}', [PerkembanganController::class, 'destroy'])->name('perkembangan.destroy');
    });

    Route::prefix('kepala-sekolah')->name('kepala-sekolah.')->middleware(['auth:kepala_sekolah', 'role:kepala_sekolah'])->group(function () {
        Route::get('/review', [KepalaSekolahReviewPerkembanganController::class, 'index'])->name('review.index');
        Route::get('/review/{perkembangan}', [KepalaSekolahReviewPerkembanganController::class, 'show'])->name('review.show');
        Route::post('/review/{perkembangan}/approve', [KepalaSekolahReviewPerkembanganController::class, 'approve'])->name('review.approve');
        Route::post('/review/{perkembangan}/reject', [KepalaSekolahReviewPerkembanganController::class, 'reject'])->name('review.reject');
    });

    Route::prefix('orang-tua')->name('orang-tua.')->middleware(['auth:orang_tua', 'role:orang_tua'])->group(function () {
        Route::get('/profil', [PortalController::class, 'index'])->name('profil.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/pengumuman', [OrangTuaPengumumanController::class, 'index'])->name('pengumuman.index');
    });
});
