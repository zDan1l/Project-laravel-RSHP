@extends('layouts.resepsionis')

@section('title', 'Detail Pemilik')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Detail Pemilik</h1>
            <p class="text-muted mb-0">Informasi lengkap pemilik hewan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('resepsionis.pemilik.edit', $pemilik->idpemilik) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Pemilik -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Pemilik
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="avatar-circle mx-auto mb-3">
                        {{ strtoupper(substr($pemilik->user->nama ?? 'N', 0, 2)) }}
                    </div>
                    <h4 class="mb-1">{{ $pemilik->user->nama ?? 'N/A' }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-id-badge me-1"></i>ID: {{ $pemilik->idpemilik }}
                    </p>
                </div>
                <div class="card-body border-top">
                    <div class="info-item mb-3">
                        <label class="text-muted small mb-1">
                            <i class="fas fa-phone me-2"></i>No. WhatsApp
                        </label>
                        <div class="fw-semibold">{{ $pemilik->no_wa }}</div>
                    </div>

                    <div class="info-item">
                        <label class="text-muted small mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>Alamat
                        </label>
                        <div class="fw-semibold">{{ $pemilik->alamat }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Hewan Peliharaan -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-success text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-paw me-2"></i>Daftar Hewan Peliharaan
                        <span class="badge bg-white text-success ms-2">{{ $pemilik->pet->count() }}</span>
                    </h5>
                    <a href="{{ route('resepsionis.pet.create') }}?idpemilik={{ $pemilik->idpemilik }}" 
                       class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Tambah Pet
                    </a>
                </div>
                <div class="card-body">
                    @if($pemilik->pet->count() > 0)
                        <div class="row g-3">
                            @foreach($pemilik->pet as $pet)
                                <div class="col-md-6">
                                    <div class="card border h-100 hover-shadow">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="pet-avatar me-3">
                                                    {{ strtoupper(substr($pet->nama, 0, 1)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $pet->nama }}</h6>
                                                    <div class="small text-muted mb-2">
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fas fa-dna me-1"></i>
                                                            {{ $pet->ras->nama_ras ?? 'N/A' }}
                                                        </span>
                                                        @if($pet->jenis_kelamin == 'Jantan')
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-mars me-1"></i>Jantan
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-venus me-1"></i>Betina
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($pet->warna_tanda)
                                                        <div class="small mb-1">
                                                            <i class="fas fa-palette me-1 text-muted"></i>
                                                            <span class="text-muted">Warna:</span> {{ $pet->warna_tanda }}
                                                        </div>
                                                    @endif
                                                    @if($pet->tanggal_lahir)
                                                        <div class="small mb-2">
                                                            <i class="fas fa-birthday-cake me-1 text-muted"></i>
                                                            <span class="text-muted">Lahir:</span> 
                                                            {{ \Carbon\Carbon::parse($pet->tanggal_lahir)->isoFormat('D MMMM YYYY') }}
                                                        </div>
                                                    @endif
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('resepsionis.pet.show', $pet->idpet) }}" 
                                                           class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('resepsionis.pet.edit', $pet->idpet) }}" 
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-paw fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Hewan Peliharaan</h5>
                            <p class="text-muted mb-3">Pemilik ini belum mendaftarkan hewan peliharaan</p>
                            <a href="{{ route('resepsionis.pet.create') }}?idpemilik={{ $pemilik->idpemilik }}" 
                               class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Daftarkan Pet
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistik -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-box">
                                <i class="fas fa-paw fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $pemilik->pet->count() }}</h4>
                                <small class="text-muted">Total Pet</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <i class="fas fa-mars fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $pemilik->pet->where('jenis_kelamin', 'Jantan')->count() }}</h4>
                                <small class="text-muted">Jantan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <i class="fas fa-venus fa-2x text-danger mb-2"></i>
                                <h4 class="mb-0">{{ $pemilik->pet->where('jenis_kelamin', 'Betina')->count() }}</h4>
                                <small class="text-muted">Betina</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3494e6 0%, #ec6ead 100%);
}

.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    font-weight: bold;
}

.pet-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    font-weight: bold;
    flex-shrink: 0;
}

.info-item label {
    display: block;
    font-size: 0.875rem;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.stat-box {
    padding: 1rem;
}
</style>
@endsection
