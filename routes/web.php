<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MasterAreaController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterMenuController;
use App\Http\Controllers\MasterProyekController;
use App\Http\Controllers\MasterUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Master Menu
Route::get('/menu', [MasterMenuController::class, 'index'])->name('menu');
Route::post('/menu_create', [MasterMenuController::class, 'create'])->name('menu_create');
Route::get('/menu_dt', [MasterMenuController::class, 'menu_dt'])->name('menu_dt');
Route::post('/menu_delete', [MasterMenuController::class, 'delete'])->name('menu_delete');
Route::post('/menu_update', [MasterMenuController::class, 'update'])->name('menu_update');

// Master Proyek
Route::get('/proyek', [MasterProyekController::class, 'index'])->name('proyek');
Route::post('/proyek_create', [MasterProyekController::class, 'create'])->name('proyek_create');
Route::get('/proyek_dt', [MasterProyekController::class, 'proyek_dt'])->name('proyek_dt');
Route::post('/proyek_delete', [MasterProyekController::class, 'delete'])->name('proyek_delete');
Route::post('/proyek_update', [MasterProyekController::class, 'update'])->name('proyek_update');

// Master Jabatan
Route::get('/jabatan', [MasterJabatanController::class, 'index'])->name('jabatan');
Route::post('/jabatan_create', [MasterJabatanController::class, 'create'])->name('jabatan_create');
Route::get('/jabatan_dt', [MasterJabatanController::class, 'jabatan_dt'])->name('jabatan_dt');
Route::post('/jabatan_delete', [MasterJabatanController::class, 'delete'])->name('jabatan_delete');
Route::post('/jabatan_update', [MasterJabatanController::class, 'update'])->name('jabatan_update');

// Master Area
Route::get('/area', [MasterAreaController::class, 'index'])->name('area');
Route::post('/area_create', [MasterAreaController::class, 'create'])->name('area_create');
Route::get('/area_dt', [MasterAreaController::class, 'area_dt'])->name('area_dt');
Route::post('/area_delete', [MasterAreaController::class, 'delete'])->name('area_delete');
Route::post('/area_update', [MasterAreaController::class, 'update'])->name('area_update');

// Karyawan
Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan');
Route::post('/karyawan_create', [KaryawanController::class, 'create'])->name('karyawan_create');
Route::get('/karyawan_dt', [KaryawanController::class, 'karyawan_dt'])->name('karyawan_dt');
Route::post('/karyawan_delete', [KaryawanController::class, 'delete'])->name('karyawan_delete');
Route::post('/karyawan_update', [KaryawanController::class, 'update'])->name('karyawan_update');

// Master User
Route::get('/user', [MasterUserController::class, 'index'])->name('user');
Route::post('/user_create', [MasterUserController::class, 'create'])->name('user_create');
Route::get('/user_dt', [MasterUserController::class, 'user_dt'])->name('user_dt');
Route::post('/user_delete', [MasterUserController::class, 'delete'])->name('user_delete');
Route::post('/user_update', [MasterUserController::class, 'update'])->name('user_update');
