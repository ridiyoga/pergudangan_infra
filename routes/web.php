<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterAreaController;
use App\Http\Controllers\MasterHakAksesController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterLogController;
use App\Http\Controllers\MasterMenuController;
use App\Http\Controllers\MasterProyekController;
use App\Http\Controllers\MasterUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Master Menu
    Route::get('/menu/{menu_id}', [MasterMenuController::class, 'index'])->name('menu');
    Route::post('/menu_create', [MasterMenuController::class, 'create'])->name('menu_create');
    Route::get('/menu_dt', [MasterMenuController::class, 'menu_dt'])->name('menu_dt');
    Route::post('/menu_delete', [MasterMenuController::class, 'delete'])->name('menu_delete');
    Route::post('/menu_update', [MasterMenuController::class, 'update'])->name('menu_update');

    // Master Proyek
    Route::get('/proyek/{menu_id}', [MasterProyekController::class, 'index'])->name('proyek');
    Route::post('/proyek_create', [MasterProyekController::class, 'create'])->name('proyek_create');
    Route::get('/proyek_dt', [MasterProyekController::class, 'proyek_dt'])->name('proyek_dt');
    Route::post('/proyek_delete', [MasterProyekController::class, 'delete'])->name('proyek_delete');
    Route::post('/proyek_update', [MasterProyekController::class, 'update'])->name('proyek_update');

    // Master Jabatan
    Route::get('/jabatan/{menu_id}', [MasterJabatanController::class, 'index'])->name('jabatan');
    Route::post('/jabatan_create', [MasterJabatanController::class, 'create'])->name('jabatan_create');
    Route::get('/jabatan_dt', [MasterJabatanController::class, 'jabatan_dt'])->name('jabatan_dt');
    Route::post('/jabatan_delete', [MasterJabatanController::class, 'delete'])->name('jabatan_delete');
    Route::post('/jabatan_update', [MasterJabatanController::class, 'update'])->name('jabatan_update');

    // Master Area
    Route::get('/area/{menu_id}', [MasterAreaController::class, 'index'])->name('area');
    Route::post('/area_create', [MasterAreaController::class, 'create'])->name('area_create');
    Route::get('/area_dt', [MasterAreaController::class, 'area_dt'])->name('area_dt');
    Route::post('/area_delete', [MasterAreaController::class, 'delete'])->name('area_delete');
    Route::post('/area_update', [MasterAreaController::class, 'update'])->name('area_update');

    // Karyawan
    Route::get('/karyawan/{menu_id}', [KaryawanController::class, 'index'])->name('karyawan');
    Route::post('/karyawan_create', [KaryawanController::class, 'create'])->name('karyawan_create');
    Route::get('/karyawan_dt', [KaryawanController::class, 'karyawan_dt'])->name('karyawan_dt');
    Route::post('/karyawan_delete', [KaryawanController::class, 'delete'])->name('karyawan_delete');
    Route::post('/karyawan_update', [KaryawanController::class, 'update'])->name('karyawan_update');

    // Hak Akses
    Route::get('/hak_akses/{menu_id}', [MasterHakAksesController::class, 'index'])->name('hak_akses');
    Route::post('/hak_akses_create', [MasterHakAksesController::class, 'create'])->name('hak_akses_create');
    Route::get('/hak_akses_dt', [MasterHakAksesController::class, 'hak_akses_dt'])->name('hak_akses_dt');
    Route::get('/hak_akses_menu_dt', [MasterHakAksesController::class, 'hak_akses_menu_dt'])->name('hak_akses_menu_dt');
    Route::post('/hak_akses_delete', [MasterHakAksesController::class, 'delete'])->name('hak_akses_delete');
    Route::post('/hak_akses_update', [MasterHakAksesController::class, 'update'])->name('hak_akses_update');

    // Master User
    Route::get('/user/{menu_id}', [MasterUserController::class, 'index'])->name('user');
    Route::post('/user_create', [MasterUserController::class, 'create'])->name('user_create');
    Route::post('/user_password', [MasterUserController::class, 'password'])->name('user_password');
    Route::get('/user_dt', [MasterUserController::class, 'user_dt'])->name('user_dt');
    Route::post('/user_delete', [MasterUserController::class, 'delete'])->name('user_delete');
    Route::post('/user_update', [MasterUserController::class, 'update'])->name('user_update');

    // Master Barang
    Route::get('/barang/{menu_id}', [BarangController::class, 'index'])->name('barang');
    Route::post('/barang_create', [BarangController::class, 'create'])->name('barang_create');
    Route::get('/barang_dt', [BarangController::class, 'barang_dt'])->name('barang_dt');
    Route::post('/barang_delete', [BarangController::class, 'delete'])->name('barang_delete');
    Route::post('/barang_update', [BarangController::class, 'update'])->name('barang_update');

    // Master User
    Route::get('/log/{menu_id}', [MasterLogController::class, 'index'])->name('log');
    Route::get('/log_dt', [MasterLogController::class, 'log_dt'])->name('log_dt');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/proses_login', [LoginController::class, 'proses_login'])->name('proses_login');
});
