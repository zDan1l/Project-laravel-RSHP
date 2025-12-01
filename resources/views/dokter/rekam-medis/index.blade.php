@extends('layouts.dokter')

@section('title', 'Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Rekam Medis Saya</h4>
                    <p class="text-muted mb-0">Daftar rekam medis yang saya buat</p>
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
            <form action="{{ route('dokter.rekam-medis.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Nama pet, pemilik, atau diagnosa..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
                           value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
                           value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Rekam Medis</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Nama Pet</th>
                            <th width="15%">Pemilik</th>
                            <th width="25%">Diagnosa</th>
                            <th width="10%">Reservasi</th>
                            <th width="18%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekamMedis as $index => $rm)
                        <tr>
                            <td>{{ $rekamMedis->firstItem() + $index }}</td>
                            <td>{{ $rm->created_at ? $rm->created_at->format('d M Y') : '-' }}</td>
                            <td>{{ $rm->temuDokter->pet->nama ?? '-' }}</td>
                            <td>{{ $rm->temuDokter->pet->pemilik->user->nama ?? '-' }}</td>
                            <td>{{ Str::limit($rm->diagnosa ?? '-', 40) }}</td>
                            <td>#{{ str_pad($rm->temuDokter->no_urut ?? '0', 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="text-center">
                                <a href="{{ route('dokter.rekam-medis.show', $rm->idrekam_medis) }}" 
                                   class="btn btn-sm btn-info text-white" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dokter.rekam-medis.edit', $rm->idrekam_medis) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">Belum ada data rekam medis</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rekamMedis->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Menampilkan {{ $rekamMedis->firstItem() }} - {{ $rekamMedis->lastItem() }} dari {{ $rekamMedis->total() }} data
                </div>
                <div>
                    {{ $rekamMedis->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
