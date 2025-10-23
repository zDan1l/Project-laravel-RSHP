<?php

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
    Route::get('/pemilik', [RoleController::class, 'index'])->name('pemilik.index');
    
    
    Route::get('/roles/create', function () {
        return view('admin.role.create');
    })->name('role.create');
    
    
    // Pet Owners
    Route::get('/pemilik', function () {
        return view('admin.pemilik.index');
    })->name('pemilik.index');
    
    Route::get('/pemilik/create', function () {
        return view('admin.pemilik.create');
    })->name('pemilik.create');
    
    // Pets
    Route::get('/pets', function () {
        return view('admin.pet.index');
    })->name('pet.index');
    
    Route::get('/pets/create', function () {
        return view('admin.pet.create');
    })->name('pet.create');
    
    // Animal Types
    Route::get('/jenis-hewan', function () {
        return view('admin.jenishewan.index');
    })->name('jenishewan.index');
    
    Route::get('/jenis-hewan/create', function () {
        return view('admin.jenishewan.create');
    })->name('jenishewan.create');
    
    // Categories
    Route::get('/kategori', function () {
        return view('admin.kategori.index');
    })->name('kategori.index');
    
    Route::get('/kategori/create', function () {
        return view('admin.kategori.create');
    })->name('kategori.create');
    
    // Clinical Categories
    Route::get('/kategori-klinis', function () {
        return view('admin.kategoriklinis.index');
    })->name('kategoriklinis.index');
    
    Route::get('/kategori-klinis/create', function () {
        return view('admin.kategoriklinis.create');
    })->name('kategoriklinis.create');
    
    // Treatment Codes
    Route::get('/kode-tindakan', function () {
        return view('admin.kodentindakan.index');
    })->name('kodentindakan.index');
    
    Route::get('/kode-tindakan/create', function () {
        return view('admin.kodentindakan.create');
    })->name('kodentindakan.create');
});

// Logout Route
Route::post('/logout', function () {
    // Add logout logic here
    return redirect()->route('home');
})->name('logout');































