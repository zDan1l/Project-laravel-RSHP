@extends('layouts.dokter')

@section('title', 'Antrian Pasien')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Antrian Pasien</h4>
                    <p class="text-muted mb-0">Daftar pasien yang menunggu pemeriksaan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('dokter.antrian.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                           value="{{ request('tanggal', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Antrian Cards -->
    <div class="row">
        @forelse($antrian as $item)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 {{ $item->status == 'P' ? 'border-warning' : '' }}">
                <div class="card-header {{ $item->status == 'P' ? 'bg-warning text-dark' : 'bg-primary text-white' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>No. {{ str_pad($item->no_urut, 3, '0', STR_PAD_LEFT) }}
                        </h5>
                        @if($item->status == 'A')
                            <span class="badge bg-light text-dark">Menunggu</span>
                        @elseif($item->status == 'P')
                            <span class="badge bg-dark">Sedang Diperiksa</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary">
                            <i class="fas fa-paw me-2"></i>{{ $item->pet->nama ?? '-' }}
                        </h6>
                        <small class="text-muted">
                            {{ $item->pet->ras->nama_ras ?? '-' }} 
                            ({{ $item->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }})
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Pemilik:</small>
                        <span class="fw-semibold">{{ $item->pet->pemilik->user->nama ?? '-' }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">No. WhatsApp:</small>
                        <span>{{ $item->pet->pemilik->no_wa ?? '-' }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Waktu Daftar:</small>
                        <span>{{ $item->waktu_daftar->format('H:i') }} WIB</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    @if($item->status == 'A')
                        <form action="{{ route('dokter.antrian.mulai', $item->idreservasi_dokter) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-stethoscope me-2"></i>Mulai Pemeriksaan
                            </button>
                        </form>
                    @elseif($item->status == 'P')
                        <a href="{{ route('dokter.rekam-medis.create', $item->idreservasi_dokter) }}" 
                           class="btn btn-warning w-100">
                            <i class="fas fa-file-medical me-2"></i>Lanjutkan Pemeriksaan
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada antrian</h5>
                    <p class="text-muted">Belum ada pasien yang menunggu pemeriksaan.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
