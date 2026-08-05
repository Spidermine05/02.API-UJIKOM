<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

//admin
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin')->group(function (){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    //CRUD Alat
    Route::get('/alat', [AdminController::class], 'indexAlat')->name('alat.index');
    Route::get('/alat', [AdminController::class], 'storeAlat')->name('alat.index');

    //CRUD User
    Route::get('/users', [AdminController::class, 'indexUser'])->name('user.index');
});

//petugas
Route::middleware(['auth', 'role:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function (){
    //Peminjaman & Persetujuan
    Route::get('/peminjaman', [PetugasController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/setujui', [PetugasController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');

    //Pengembalian & denda
    Route::post('/peminjaman/{id}', [PetugasController::class, 'prosesPengembalian'])->name('pengembalian.proses');

});

//peminjam
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group( function (){
    //katalog & pengajuan
    Route::get('/katalog', [PeminjamController::class, 'katalogAlat'])->name('katalog');
    Route::post('/peminjaman/ajukan', [PeminjamController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');
    Route::get('/riwayat', [PeminjamController::class, 'riwayatPeminjam'])->name('riwayat');

});

//Route tamu (Belum Login)
Route::middleware('guest')->group(function (){
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

//Route Logout Harus sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
