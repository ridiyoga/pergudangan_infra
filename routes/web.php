<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterAreaController;
use App\Http\Controllers\MasterJabatanController;
use App\Http\Controllers\MasterMenuController;
use App\Http\Controllers\MasterProyekController;
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

// Master Menu
Route::get('/proyek', [MasterProyekController::class, 'index'])->name('proyek');
Route::post('/proyek_create', [MasterProyekController::class, 'create'])->name('proyek_create');
Route::get('/proyek_dt', [MasterProyekController::class, 'proyek_dt'])->name('proyek_dt');
Route::post('/proyek_delete', [MasterProyekController::class, 'delete'])->name('proyek_delete');
Route::post('/proyek_update', [MasterProyekController::class, 'update'])->name('proyek_update');

// Master Menu
Route::get('/jabatan', [MasterJabatanController::class, 'index'])->name('jabatan');
Route::post('/jabatan_create', [MasterJabatanController::class, 'create'])->name('jabatan_create');
Route::get('/jabatan_dt', [MasterJabatanController::class, 'jabatan_dt'])->name('jabatan_dt');
Route::post('/jabatan_delete', [MasterJabatanController::class, 'delete'])->name('jabatan_delete');
Route::post('/jabatan_update', [MasterJabatanController::class, 'update'])->name('jabatan_update');

// Master Menu
Route::get('/area', [MasterAreaController::class, 'index'])->name('area');
Route::post('/area_create', [MasterAreaController::class, 'create'])->name('area_create');
Route::get('/area_dt', [MasterAreaController::class, 'area_dt'])->name('area_dt');
Route::post('/area_delete', [MasterAreaController::class, 'delete'])->name('area_delete');
Route::post('/area_update', [MasterAreaController::class, 'update'])->name('area_update');

// Karyawan
Route::get('/karyawan', [MasterAreaController::class, 'index'])->name('karyawan');
Route::post('/karyawan_create', [MasterAreaController::class, 'create'])->name('karyawan_create');
Route::get('/karyawan_dt', [MasterAreaController::class, 'karyawan_dt'])->name('karyawan_dt');
Route::post('/karyawan_delete', [MasterAreaController::class, 'delete'])->name('karyawan_delete');
Route::post('/karyawan_update', [MasterAreaController::class, 'update'])->name('karyawan_update');
