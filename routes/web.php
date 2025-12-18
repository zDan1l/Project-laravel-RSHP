<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\admin\PetController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PemilikController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\admin\UserRoleController;
use App\Http\Controllers\admin\JenisHewanController;
use App\Http\Controllers\admin\KategoriKlinisController;
use App\Http\Controllers\admin\KodeTindakanTerapiController;
use App\Http\Controllers\admin\RasController;
use App\Http\Controllers\dokter\DokterRekamMedisController;
use App\Http\Controllers\resepsionis\ResepsionisPetController;
use App\Http\Controllers\resepsionis\ResepsionisTemuController;
use App\Http\Controllers\resepsionis\ResepsionisPemilikController;

Auth::routes();

// Custom Logout Route (Override Laravel UI logout)
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/tentang-kami', [LandingController::class, 'tentang'])->name('tentang');
Route::get('/layanan', [LandingController::class, 'layanan'])->name('layanan');
Route::get('/struktur', [LandingController::class, 'struktur'])->name('struktur');
Route::get('/kontak', [LandingController::class, 'kontak'])->name('kontak');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdministrator'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');
    
    // Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/users-roles', [UserRoleController::class, 'index'])->name('user-role.index');
    Route::get('/users-roles/create', [UserRoleController::class, 'create'])->name('user-role.create');
    Route::post('/users-roles', [UserRoleController::class, 'store'])->name('user-role.store');
    Route::get('/users-roles/{iduser}/edit', [UserRoleController::class, 'edit'])->name('user-role.edit');
    Route::put('/users-roles/{iduser}', [UserRoleController::class, 'update'])->name('user-role.update');
    // PENTING: Route dengan literal path harus didefinisikan SEBELUM route dengan parameter dinamis
    Route::delete('/users-roles/{iduser}/delete-all', [UserRoleController::class, 'deleteAllRoles'])->name('user-role.delete-all');
    Route::delete('/users-roles/{iduser}/{idrole}', [UserRoleController::class, 'destroy'])->name('user-role.destroy');
    Route::post('/users-roles/{iduser}/{idrole}/deactivate', [UserRoleController::class, 'deactivate'])->name('user-role.deactivate');
    
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    
    // Dokter Management
    Route::get('/dokter', [App\Http\Controllers\admin\DokterController::class, 'index'])->name('dokter.index');
    Route::get('/dokter/create', [App\Http\Controllers\admin\DokterController::class, 'create'])->name('dokter.create');
    Route::post('/dokter', [App\Http\Controllers\admin\DokterController::class, 'store'])->name('dokter.store');
    Route::get('/dokter/{id}/edit', [App\Http\Controllers\admin\DokterController::class, 'edit'])->name('dokter.edit');
    Route::put('/dokter/{id}', [App\Http\Controllers\admin\DokterController::class, 'update'])->name('dokter.update');
    Route::delete('/dokter/{id}', [App\Http\Controllers\admin\DokterController::class, 'destroy'])->name('dokter.destroy');
    
    // Pet Management
    Route::get('/pemilik', [PemilikController::class, 'index'])->name('pemilik.index');
    Route::get('/pemilik/create', [PemilikController::class, 'create'])->name('pemilik.create');
    Route::post('/pemilik', [PemilikController::class, 'store'])->name('pemilik.store');
    Route::get('/pemilik/{id}/edit', [PemilikController::class, 'edit'])->name('pemilik.edit');
    Route::put('/pemilik/{id}', [PemilikController::class, 'update'])->name('pemilik.update');
    Route::delete('/pemilik/{id}', [PemilikController::class, 'destroy'])->name('pemilik.destroy');
    
    Route::get('/pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{id}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('/pets/{id}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy');
    
    Route::get('/jenis-hewan', [JenisHewanController::class, 'index'])->name('jenis-hewan.index');
    Route::get('/jenis-hewan/create', [JenisHewanController::class, 'create'])->name('jenis-hewan.create');
    Route::post('/jenis-hewan', [JenisHewanController::class, 'store'])->name('jenis-hewan.store');
    Route::get('/jenis-hewan/{id}/edit', [JenisHewanController::class, 'edit'])->name('jenis-hewan.edit');
    Route::put('/jenis-hewan/{id}', [JenisHewanController::class, 'update'])->name('jenis-hewan.update');
    Route::delete('/jenis-hewan/{id}', [JenisHewanController::class, 'destroy'])->name('jenis-hewan.destroy');
    
    // Categories
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Clinical Categories
    Route::get('/kategori-klinis', [KategoriKlinisController::class, 'index'])->name('kategoriklinis.index');
    Route::get('/kategori-klinis/create', [KategoriKlinisController::class, 'create'])->name('kategoriklinis.create');
    Route::post('/kategori-klinis', [KategoriKlinisController::class, 'store'])->name('kategoriklinis.store');
    Route::get('/kategori-klinis/{id}/edit', [KategoriKlinisController::class, 'edit'])->name('kategoriklinis.edit');
    Route::put('/kategori-klinis/{id}', [KategoriKlinisController::class, 'update'])->name('kategoriklinis.update');
    Route::delete('/kategori-klinis/{id}', [KategoriKlinisController::class, 'destroy'])->name('kategoriklinis.destroy');

    // Treatment Codes
    Route::get('/kode-tindakan', [KodeTindakanTerapiController::class, 'index'])->name('kodentindakan.index');
    Route::get('/kode-tindakan/create', [KodeTindakanTerapiController::class, 'create'])->name('kodentindakan.create');
    Route::post('/kode-tindakan', [KodeTindakanTerapiController::class, 'store'])->name('kodentindakan.store');
    Route::get('/kode-tindakan/{id}/edit', [KodeTindakanTerapiController::class, 'edit'])->name('kodentindakan.edit');
    Route::put('/kode-tindakan/{id}', [KodeTindakanTerapiController::class, 'update'])->name('kodentindakan.update');
    Route::delete('/kode-tindakan/{id}', [KodeTindakanTerapiController::class, 'destroy'])->name('kodentindakan.destroy');

    // Ras Hewan
    Route::get('/ras', [RasController::class, 'index'])->name('ras.index');
    Route::get('/ras/create', [RasController::class, 'create'])->name('ras.create');
    Route::post('/ras', [RasController::class, 'store'])->name('ras.store');
    Route::get('/ras/{id}/edit', [RasController::class, 'edit'])->name('ras.edit');
    Route::put('/ras/{id}', [RasController::class, 'update'])->name('ras.update');
    Route::delete('/ras/{id}', [RasController::class, 'destroy'])->name('ras.destroy');
});

// resepsionis routes
Route::prefix('resepsionis')->name('resepsionis.')->middleware(['auth', 'isResepsionis'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\resepsionis\ResepsionisDashboardController::class, 'index'])->name('dashboard');
    
    // Pemilik Management
    Route::get('/pemilik', [ResepsionisPemilikController::class, 'index'])->name('pemilik.index');
    Route::get('/pemilik/create', [ResepsionisPemilikController::class, 'create'])->name('pemilik.create');
    Route::post('/pemilik', [ResepsionisPemilikController::class, 'store'])->name('pemilik.store');
    Route::get('/pemilik/{id}', [ResepsionisPemilikController::class, 'show'])->name('pemilik.show');
    Route::get('/pemilik/{id}/edit', [ResepsionisPemilikController::class, 'edit'])->name('pemilik.edit');
    Route::put('/pemilik/{id}', [ResepsionisPemilikController::class, 'update'])->name('pemilik.update');
    Route::delete('/pemilik/{id}', [ResepsionisPemilikController::class, 'destroy'])->name('pemilik.destroy');
    
    // Pet Management
    Route::get('/pets', [ResepsionisPetController::class, 'index'])->name('pet.index');
    Route::get('/pets/create', [ResepsionisPetController::class, 'create'])->name('pet.create');
    Route::post('/pets', [ResepsionisPetController::class, 'store'])->name('pet.store');
    Route::get('/pets/{id}', [ResepsionisPetController::class, 'show'])->name('pet.show');
    Route::get('/pets/{id}/edit', [ResepsionisPetController::class, 'edit'])->name('pet.edit');
    Route::put('/pets/{id}', [ResepsionisPetController::class, 'update'])->name('pet.update');
    Route::delete('/pets/{id}', [ResepsionisPetController::class, 'destroy'])->name('pet.destroy');
    
    // Temu Dokter Management
    Route::get('/temu-dokter', [ResepsionisTemuController::class, 'index'])->name('temu-dokter.index');
    Route::get('/temu-dokter/create', [ResepsionisTemuController::class, 'create'])->name('temu-dokter.create');
    Route::post('/temu-dokter', [ResepsionisTemuController::class, 'store'])->name('temu-dokter.store');
    Route::get('/temu-dokter/{id}', [ResepsionisTemuController::class, 'show'])->name('temu-dokter.show');
    Route::post('/temu-dokter/{id}/cancel', [ResepsionisTemuController::class, 'cancel'])->name('temu-dokter.cancel');
    Route::post('/temu-dokter/{id}/checkin', [ResepsionisTemuController::class, 'checkin'])->name('temu-dokter.checkin');
});

// perawat router
Route::prefix('perawat')->name('perawat.')->middleware(['auth', 'isPerawat'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\PerawatController::class, 'dashboard'])->name('dashboard');
    
    // Daftar Pasien
    Route::get('/daftar-pasien', [App\Http\Controllers\PerawatController::class, 'daftarPasien'])->name('daftar-pasien.index');
    
    // Rekam Medis (Perawat hanya bisa CREATE rekam medis utama - READ ONLY untuk detail tindakan)
    Route::get('/rekam-medis', [App\Http\Controllers\PerawatController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/create/{idTemuDokter}', [App\Http\Controllers\PerawatController::class, 'create'])->name('rekam-medis.create');
    Route::post('/rekam-medis', [App\Http\Controllers\PerawatController::class, 'store'])->name('rekam-medis.store');
    Route::get('/rekam-medis/{id}', [App\Http\Controllers\PerawatController::class, 'show'])->name('rekam-medis.show');
    
});

// dokter router
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'isDokter'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dokter.dashboard.index');
    })->name('dashboard');
    
    // Antrian Pasien
    Route::get('/antrian', [DokterRekamMedisController::class, 'antrian'])->name('antrian.index');
    Route::post('/antrian/{id}/mulai', [DokterRekamMedisController::class, 'mulaiPemeriksaan'])->name('antrian.mulai');
    
    // Rekam Medis (Dokter hanya bisa LIHAT rekam medis yang dibuat perawat)
    Route::get('/rekam-medis', [DokterRekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/create/{idTemuDokter}', [DokterRekamMedisController::class, 'create'])->name('rekam-medis.create'); // Redirect ke show
    Route::post('/rekam-medis', [DokterRekamMedisController::class, 'store'])->name('rekam-medis.store'); // Disabled
    Route::get('/rekam-medis/{id}', [DokterRekamMedisController::class, 'show'])->name('rekam-medis.show');
    Route::get('/rekam-medis/{id}/edit', [DokterRekamMedisController::class, 'edit'])->name('rekam-medis.edit'); // Disabled
    Route::put('/rekam-medis/{id}', [DokterRekamMedisController::class, 'update'])->name('rekam-medis.update'); // Disabled
    
    // Detail Rekam Medis (Tindakan Terapi) - FOKUS DOKTER DI SINI
    Route::post('/rekam-medis/{id}/detail', [DokterRekamMedisController::class, 'storeDetail'])->name('rekam-medis.detail.store');
    Route::put('/rekam-medis/{id}/detail/{idDetail}', [DokterRekamMedisController::class, 'updateDetail'])->name('rekam-medis.detail.update');
    Route::delete('/rekam-medis/{id}/detail/{idDetail}', [DokterRekamMedisController::class, 'deleteDetail'])->name('rekam-medis.detail.delete');
});

// Pemilik Routes
Route::prefix('pemilik')->name('pemilik.')->middleware(['auth', 'isPemilik'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\pemilik\PemilikDashboardController::class, 'index'])->name('dashboard');
    
    // View My Pets
    Route::get('/my-pets', [App\Http\Controllers\pemilik\PemilikDashboardController::class, 'index'])->name('my-pets');
    
   });



