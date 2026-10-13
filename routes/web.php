<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterMenuController;
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
