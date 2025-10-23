<?php

use App\Http\Controllers\admin\JenisHewanController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\admin\KategoriKlinisController;
use App\Http\Controllers\admin\KodeTindakanTerapiController;
use App\Http\Controllers\admin\PemilikController;
use App\Http\Controllers\admin\PetController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\UserRoleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'home'])->name('home');



Route::get('/tentang-kami', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
Route::get('/struktur', [HomeController::class, 'struktur'])->name('struktur');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');
    
    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users-roles', [UserRoleController::class, 'index'])->name('user-role.index');
    Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
    
    // Pet Management
    Route::get('/pemilik', [PemilikController::class, 'index'])->name('pemilik.index');
    Route::get('/pets', [PetController::class, 'index'])->name('pet.index');
    Route::get('/jenis-hewan', [JenisHewanController::class, 'index'])->name('jenis-hewan.index');
    
    
    
    // Categories
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');

    // Clinical Categories
    Route::get('/kategori-klinis', [KategoriKlinisController::class, 'index'])->name('kategoriklinis.index');
    Route::get('/kategori-klinis/create', [KategoriKlinisController::class, 'create'])->name('kategoriklinis.create');

    // Treatment Codes
    Route::get('/kode-tindakan', [KodeTindakanTerapiController::class, 'index'])->name('kodentindakan.index');
    Route::get('/kode-tindakan/create', [KodeTindakanTerapiController::class, 'create'])->name('kodentindakan.create');
});

// Logout Route
Route::post('/logout', function () {
    // Add logout logic here
    return redirect()->route('home');
})->name('logout');































