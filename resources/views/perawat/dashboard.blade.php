@extends('layouts.perawat')

@section('title', 'Dashboard Perawat')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Dashboard Perawat</h4>
                        <p class="text-muted mb-0">Selamat datang, <strong>{{ session('user_name') }}</strong>!</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
                        <small class="text-muted">{{ now()->format('H:i') }} WIB</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small">Total Pasien Hari Ini</p>
                                <h2 class="mb-0 fw-bold">{{ $totalPasienHariIni }}</h2>
                                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>Aktif</small>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small">Pasien Menunggu</p>
                                <h2 class="mb-0 fw-bold">{{ $pasienMenunggu }}</h2>
                                <small class="text-warning"><i class="fas fa-clock me-1"></i>Antrian</small>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-clock fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small">Sedang Diproses</p>
                                <h2 class="mb-0 fw-bold">{{ $pasienDiproses }}</h2>
                                <small class="text-purple"><i class="fas fa-stethoscope me-1"></i>Dalam Perawatan</small>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 60px; height: 60px; background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-stethoscope fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 small">Selesai Hari Ini</p>
                                <h2 class="mb-0 fw-bold">{{ $pasienSelesai }}</h2>
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Selesai</small>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-check-circle fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-3">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Welcome Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-4" 
                                style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-user-nurse fa-3x text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">Selamat Bekerja, {{ session('user_name') }}!</h5>
                                <p class="text-muted mb-3">
                                    Anda login sebagai <span class="badge bg-success">{{ session('user_role_name') }}</span>. 
                                    Pastikan untuk selalu memperhatikan pasien dengan cermat dan mencatat setiap tindakan dengan detail yang jelas.
                                </p>
                                <a href="{{ route('perawat.daftar-pasien.index') }}" class="btn btn-success">
                                    <i class="fas fa-users me-2"></i>Lihat Daftar Pasien
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Tugas & Tanggung Jawab
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" 
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Mendampingi Pemeriksaan</h6>
                                        <p class="text-muted mb-0 small">Membantu dokter saat memeriksa pasien</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" 
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Membaca Rekam Medis</h6>
                                        <p class="text-muted mb-0 small">Melihat dan memahami riwayat pasien</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" 
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Menambah Tindakan</h6>
                                        <p class="text-muted mb-0 small">Input tindakan sederhana seperti injeksi, grooming</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" 
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Kelola Tindakan Sendiri</h6>
                                        <p class="text-muted mb-0 small">Edit/hapus tindakan yang Anda input</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center" 
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-times text-danger"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Batasan Akses</h6>
                                <p class="text-muted mb-0 small">Tidak dapat mengubah diagnosa atau membuat rekam medis baru</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Quick Actions Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('perawat.daftar-pasien.index') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-users me-2"></i>Daftar Pasien</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-lightbulb text-warning me-2"></i>Tips & Panduan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0 mb-3">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Cara Kerja
                            </h6>
                            <p class="mb-0 small">
                                Klik pada pasien di daftar untuk melihat detail rekam medis. 
                                Anda dapat menambahkan tindakan sederhana pada rekam medis yang dibuat oleh dokter.
                            </p>
                        </div>

                        <div class="alert alert-warning border-0 mb-0">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Perhatian
                            </h6>
                            <p class="mb-0 small">
                                Pastikan setiap tindakan dicatat dengan detail yang lengkap dan akurat untuk kepentingan medis pasien.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
