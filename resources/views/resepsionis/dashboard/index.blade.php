@extends('layouts.resepsionis')

{{-- @section('title', 'Dashboard')

@section('page-title', 'Dashboard') --}}

@section('content')
<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="dashboard-header mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                                <p class="mb-0 opacity-75">Berikut adalah ringkasan aktivitas di klinik hari ini, {{ $tanggalHariIni }}.</p>
                            </div>
                            <div class="col-md-4 text-end d-none d-md-block">
                                <i class="fas fa-paw" style="font-size: 5rem; opacity: 0.2;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="row g-3">
            <!-- Janji Hari Ini -->
            <div class="col-md-4">
                <div class="stat-card info">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                <h2 class="mb-0 fw-bold">{{ $stats['janji_hari_ini'] }}</h2>
                                <p class="text-muted mb-0 small">Janji Temu Hari Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pasien Baru Bulan Ini -->
            <div class="col-md-4">
                <div class="stat-card success">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-paw text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                <h2 class="mb-0 fw-bold">{{ $stats['pasien_baru_bulan_ini'] }}</h2>
                                <p class="text-muted mb-0 small">Pasien Baru Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pasien -->
            <div class="col-md-4">
                <div class="stat-card warning">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                <h2 class="mb-0 fw-bold">{{ $stats['total_pasien'] }}</h2>
                                <p class="text-muted mb-0 small">Total Pasien Terdaftar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Akses Cepat
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Registrasi Section -->
                        <div class="col-md-6">
                            <div class="quick-action-section">
                                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                                    <i class="fas fa-user-plus me-2"></i>Registrasi
                                </h6>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <a href="{{ route('resepsionis.pemilik.create') }}" class="action-button">
                                            <div class="action-icon">
                                                <i class="fas fa-user-plus"></i>
                                            </div>
                                            <span class="action-text">Registrasi Pemilik</span>
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="{{ route('resepsionis.pet.create') }}" class="action-button">
                                            <div class="action-icon">
                                                <i class="fas fa-shield-dog"></i>
                                            </div>
                                            <span class="action-text">Registrasi Pet Baru</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Temu Dokter Section -->
                        <div class="col-md-6">
                            <div class="quick-action-section">
                                <h6 class="text-muted mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                                    <i class="fas fa-user-md me-2"></i>Temu Dokter
                                </h6>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <a href="{{ route('resepsionis.temu-dokter.create') }}" class="action-button">
                                            <div class="action-icon">
                                                <i class="fas fa-plus-circle"></i>
                                            </div>
                                            <span class="action-text">Atur Pertemuan</span>
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="{{ route('resepsionis.temu-dokter.index') }}" class="action-button">
                                            <div class="action-icon">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <span class="action-text">Lihat Jadwal</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .action-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        text-decoration: none;
        color: #334155;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;
        min-height: 120px;
    }

    .action-button:hover {
        background: #fff;
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .action-button:hover .action-icon {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .action-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .action-text {
        font-weight: 500;
        font-size: 0.95rem;
        text-align: center;
    }

    .quick-action-section {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        height: 100%;
    }
</style>
@endsection
