@extends('layouts.pemilik')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="dashboard-header mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                    <div class="card-body text-white py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2">Selamat Datang, {{ auth()->user()->nama }}! 👋</h3>
                                <p class="mb-0 opacity-75">Dashboard informasi hewan peliharaan Anda, {{ $tanggalHariIni }}.</p>
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
            <!-- Total Pet -->
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shield-dog text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                <h2 class="mb-0 fw-bold">{{ $stats['total_pet'] }}</h2>
                                <p class="text-muted mb-0 small">Total Pet Saya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Janji Mendatang -->
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-check text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                <h2 class="mb-0 fw-bold">{{ $stats['janji_mendatang'] }}</h2>
                                <p class="text-muted mb-0 small">Janji Temu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Rekam Medis -->
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file-medical text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                <h2 class="mb-0 fw-bold">{{ $stats['total_rekam_medis'] }}</h2>
                                <p class="text-muted mb-0 small">Rekam Medis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kunjungan Terakhir -->
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock text-white" style="font-size: 1.8rem;"></i>
                            </div>
                            <div class="stat-info">
                                @if($stats['kunjungan_terakhir'])
                                    <h6 class="mb-0 fw-bold small">{{ \Carbon\Carbon::parse($stats['kunjungan_terakhir'])->format('d M Y') }}</h6>
                                @else
                                    <h6 class="mb-0 fw-bold small">Belum ada</h6>
                                @endif
                                <p class="text-muted mb-0 small">Kunjungan Terakhir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    @if($upcomingAppointments->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Jadwal Temu Dokter Aktif
                    </h5>
                    <span class="badge bg-primary">{{ $upcomingAppointments->count() }} Antrian</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Urut</th>
                                    <th>Pet</th>
                                    <th>Waktu Daftar</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark fs-6">{{ $appointment->no_urut }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $appointment->nama_pet }}</strong><br>
                                                <small class="text-muted">{{ $appointment->nama_ras }} - {{ $appointment->nama_jenis_hewan }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar me-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($appointment->waktu_daftar)->format('d M Y, H:i') }}
                                        </td>
                                        <td>
                                            @if($appointment->nama_dokter)
                                                <i class="fas fa-user-md me-1 text-primary"></i>
                                                {{ $appointment->nama_dokter }}
                                            @else
                                                <span class="text-muted">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->status == 'A')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Menunggu Antrian
                                                </span>
                                            @elseif($appointment->status == 'P')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-stethoscope me-1"></i>Sedang Diperiksa
                                                </span>
                                            @elseif($appointment->status == 'S')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Selesai
                                                </span>
                                            @elseif($appointment->status == 'C')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- My Pets List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-shield-dog text-success me-2"></i>
                        Daftar Pet Saya
                    </h5>
                </div>
                <div class="card-body">
                    @if($pets->count() > 0)
                        <div class="row g-4">
                            @foreach($pets as $pet)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border h-100 hover-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="pet-avatar me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-paw text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $pet->nama }}</h6>
                                                    <span class="badge bg-success-subtle text-success small">{{ $pet->jenis_kelamin }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="pet-info small">
                                                <div class="info-row mb-2">
                                                    <i class="fas fa-dog text-muted me-2" style="width: 20px;"></i>
                                                    <span class="text-muted">Ras:</span>
                                                    <span class="fw-medium ms-1">{{ $pet->ras->nama_ras ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row mb-2">
                                                    <i class="fas fa-tag text-muted me-2" style="width: 20px;"></i>
                                                    <span class="text-muted">Jenis:</span>
                                                    <span class="fw-medium ms-1">{{ $pet->ras->jenisHewan->nama_jenis_hewan ?? 'N/A' }}</span>
                                                </div>
                                                @if($pet->tanggal_lahir)
                                                    <div class="info-row mb-2">
                                                        <i class="fas fa-birthday-cake text-muted me-2" style="width: 20px;"></i>
                                                        <span class="text-muted">Lahir:</span>
                                                        <span class="fw-medium ms-1">{{ \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d M Y') }}</span>
                                                    </div>
                                                @endif
                                                @if($pet->warna_tanda)
                                                    <div class="info-row">
                                                        <i class="fas fa-palette text-muted me-2" style="width: 20px;"></i>
                                                        <span class="text-muted">Warna:</span>
                                                        <span class="fw-medium ms-1">{{ $pet->warna_tanda }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Pet Appointment Status -->
                                            @php
                                                $petActiveAppointment = $pet->temuDokter->whereIn('status', ['A', 'P'])->first();
                                            @endphp
                                            
                                            @if($petActiveAppointment)
                                                <div class="mt-3 pt-3 border-top">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                                        <small class="text-primary fw-medium">
                                                            Status: 
                                                            @if($petActiveAppointment->status == 'A')
                                                                Menunggu Antrian
                                                            @elseif($petActiveAppointment->status == 'P')
                                                                Sedang Diperiksa
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-hashtag"></i> No. Urut: {{ $petActiveAppointment->no_urut }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-light border-top-0">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-success flex-grow-1" disabled>
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </button>
                                                <button class="btn btn-sm btn-outline-primary" disabled>
                                                    <i class="fas fa-file-medical"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shield-dog text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Belum ada data pet yang terdaftar.</p>
                            <p class="text-muted small">Silakan hubungi resepsionis untuk mendaftarkan hewan peliharaan Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading mb-2">Informasi</h6>
                        <p class="mb-0 small">
                            Untuk membuat janji temu atau melihat rekam medis, silakan hubungi resepsionis kami di <strong>0812-3456-7890</strong> 
                            atau kunjungi klinik langsung. Fitur pemesanan online akan segera hadir!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.pet-info .info-row {
    display: flex;
    align-items: center;
}

.bg-success-subtle {
    background-color: rgba(5, 150, 105, 0.1) !important;
}

.text-success {
    color: #059669 !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(5, 150, 105, 0.05);
}
</style>
@endsection
