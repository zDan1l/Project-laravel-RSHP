<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\PetController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PemilikController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\admin\UserRoleController;
use App\Http\Controllers\admin\JenisHewanController;
use App\Http\Controllers\admin\KategoriKlinisController;
use App\Http\Controllers\admin\KodeTindakanTerapiController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\resepsionis\ResepsionisPemilikController;
use App\Http\Controllers\resepsionis\ResepsionisPetController;
use App\Http\Controllers\resepsionis\ResepsionisTemuController;

Auth::routes();

// Custom Logout Route (Override Laravel UI logout)
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/tentang-kami', [LandingController::class, 'tentang'])->name('tentang');
Route::get('/layanan', [LandingController::class, 'layanan'])->name('layanan');
Route::get('/struktur', [LandingController::class, 'struktur'])->name('struktur');
Route::get('/kontak', [LandingController::class, 'kontak'])->name('kontak');

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


// resepsionis routes
Route::prefix('resepsionis')->name('resepsionis.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('resepsionis.dashboard.index');
    })->name('dashboard');
    
    // Pet Management
    Route::get('/pemilik', [ResepsionisPemilikController::class, 'index'])->name('pemilik.index');
    Route::get('/pets', [ResepsionisPetController::class, 'index'])->name('pet.index');
    
    // Temu Dokter Management
    Route::get('/temu-dokter', [ResepsionisTemuController::class, 'index'])->name('temu-dokter.index');
    Route::get('/temu-dokter/create', [ResepsionisTemuController::class, 'create'])->name('temu-dokter.create');
    Route::post('/temu-dokter', [ResepsionisTemuController::class, 'store'])->name('temu-dokter.store');
    Route::get('/temu-dokter/{id}', [ResepsionisTemuController::class, 'show'])->name('temu-dokter.show');
    Route::post('/temu-dokter/{id}/cancel', [ResepsionisTemuController::class, 'cancel'])->name('temu-dokter.cancel');
    Route::post('/temu-dokter/{id}/checkin', [ResepsionisTemuController::class, 'checkin'])->name('temu-dokter.checkin');
});





