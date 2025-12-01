@extends('layouts.resepsionis')

@section('title', 'Detail Pet')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Detail Pet</h1>
            <p class="text-muted mb-0">Informasi lengkap hewan peliharaan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('resepsionis.pet.edit', $pet->idpet) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('resepsionis.pet.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Pet -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-pink text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-paw me-2"></i>Informasi Pet
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="pet-avatar-large mx-auto mb-3">
                        {{ strtoupper(substr($pet->nama, 0, 2)) }}
                    </div>
                    <h3 class="mb-1">{{ $pet->nama }}</h3>
                    <p class="text-muted mb-0">
                        <i class="fas fa-id-badge me-1"></i>ID: {{ $pet->idpet }}
                    </p>
                    @if($pet->jenis_kelamin == 'Jantan')
                        <span class="badge bg-primary mt-2">
                            <i class="fas fa-mars me-1"></i>Jantan
                        </span>
                    @else
                        <span class="badge bg-danger mt-2">
                            <i class="fas fa-venus me-1"></i>Betina
                        </span>
                    @endif
                </div>
                <div class="card-body border-top">
                    <div class="info-item mb-3">
                        <label class="text-muted small mb-1">
                            <i class="fas fa-dna me-2"></i>Ras Hewan
                        </label>
                        <div class="fw-semibold">{{ $pet->ras->nama_ras ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item mb-3">
                        <label class="text-muted small mb-1">
                            <i class="fas fa-tag me-2"></i>Jenis Hewan
                        </label>
                        <div class="fw-semibold">{{ $pet->ras->jenisHewan->nama_jenis_hewan ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item mb-3">
                        <label class="text-muted small mb-1">
                            <i class="fas fa-palette me-2"></i>Warna/Tanda
                        </label>
                        <div class="fw-semibold">{{ $pet->warna_tanda ?: '-' }}</div>
                    </div>

                    <div class="info-item mb-3">
                        <label class="text-muted small mb-1">
                            <i class="fas fa-birthday-cake me-2"></i>Tanggal Lahir
                        </label>
                        <div class="fw-semibold">
                            @if($pet->tanggal_lahir)
                                {{ \Carbon\Carbon::parse($pet->tanggal_lahir)->isoFormat('dddd, D MMMM YYYY') }}
                                <span class="badge bg-info ms-2">
                                    {{ \Carbon\Carbon::parse($pet->tanggal_lahir)->age }} tahun
                                </span>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-calendar-check me-2"></i>Buat Janji Temu
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fas fa-file-medical me-2"></i>Lihat Riwayat Medis
                        </a>
                        <a href="{{ route('resepsionis.pet.edit', $pet->idpet) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Edit Data Pet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pemilik -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Pemilik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="owner-avatar me-3">
                            {{ strtoupper(substr($pet->pemilik->user->nama ?? 'N', 0, 2)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $pet->pemilik->user->nama ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-id-badge me-1"></i>ID: {{ $pet->pemilik->idpemilik }}
                            </p>
                        </div>
                        <a href="{{ route('resepsionis.pemilik.show', $pet->pemilik->idpemilik) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="contact-item">
                                <i class="fas fa-phone text-success me-2"></i>
                                <div>
                                    <small class="text-muted d-block">No. WhatsApp</small>
                                    <strong>{{ $pet->pemilik->no_wa }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="contact-item">
                                <i class="fas fa-envelope text-info me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <strong>{{ $pet->pemilik->email ?: '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Alamat</small>
                                    <strong>{{ $pet->pemilik->alamat }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pet Lainnya dari Pemilik Ini -->
            @if($pet->pemilik->pet->where('idpet', '!=', $pet->idpet)->count() > 0)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-paw me-2"></i>Pet Lainnya dari Pemilik Ini
                            <span class="badge bg-white text-success ms-2">
                                {{ $pet->pemilik->pet->where('idpet', '!=', $pet->idpet)->count() }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($pet->pemilik->pet->where('idpet', '!=', $pet->idpet) as $otherPet)
                                <div class="col-md-6">
                                    <div class="card border h-100 hover-shadow">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="pet-avatar-small me-3">
                                                    {{ strtoupper(substr($otherPet->nama, 0, 1)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $otherPet->nama }}</h6>
                                                    <div class="small text-muted mb-2">
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $otherPet->ras->nama_ras ?? 'N/A' }}
                                                        </span>
                                                        @if($otherPet->jenis_kelamin == 'Jantan')
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-mars me-1"></i>Jantan
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-venus me-1"></i>Betina
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('resepsionis.pet.show', $otherPet->idpet) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye me-1"></i>Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline / Riwayat (Placeholder) -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada riwayat medis</p>
                        <small class="text-muted">Riwayat pemeriksaan dan perawatan akan tampil di sini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-pink {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
}

.pet-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
    font-weight: bold;
}

.owner-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    flex-shrink: 0;
}

.pet-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    font-weight: bold;
    flex-shrink: 0;
}

.info-item label {
    display: block;
    font-size: 0.875rem;
}

.contact-item {
    display: flex;
    align-items: start;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.contact-item i {
    font-size: 1.25rem;
    margin-top: 0.25rem;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
</style>
@endsection
