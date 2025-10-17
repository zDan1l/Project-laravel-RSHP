<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'home'])->name('home');



Route::get('/tentang-kami', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
Route::get('/struktur', [HomeController::class, 'struktur'])->name('struktur');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::get('/login', [LoginController::class, 'index'])->name('login');































