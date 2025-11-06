@extends('layouts.resepsionis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Temu Dokter</h4>
                    <p class="text-muted mb-0">Kelola jadwal appointment dengan dokter hewan</p>
                </div>
                <div>
                    <a href="{{ route('resepsionis.temu-dokter.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Daftar Temu Dokter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Hari Ini</h6>
                    <h3 class="mb-0">{{ $stats['hari_ini'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Menunggu</h6>
                    <h3 class="mb-0">{{ $stats['menunggu'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Selesai</h6>
                    <h3 class="mb-0">{{ $stats['selesai'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Dibatalkan</h6>
                    <h3 class="mb-0">{{ $stats['dibatalkan'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('resepsionis.temu-dokter.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama pemilik atau pet...">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="A">Menunggu</option>
                            <option value="P">Konsultasi</option>
                            <option value="S">Selesai</option>
                            <option value="C">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar Appointment Hari Ini</h5>
        </div>
        
        <div class="card-body">
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

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Antrian</th>
                            <th>Pemilik</th>
                            <th>Pet</th>
                            <th>Dokter</th>
                            <th>Waktu Daftar</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($temuDokter as $item)
                        <tr>
                            <td>
                                <span class="badge bg-primary fs-6">#{{ str_pad($item->no_urut, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $item->pet->pemilik->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $item->pet->pemilik->no_wa ?? '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $item->pet->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $item->pet->jenisHewan->nama ?? '-' }}</small>
                            </td>
                            <td>{{ $item->roleUser->user->nama ?? '-' }}</td>
                            <td>
                                {{ $item->waktu_daftar->format('d M Y') }}<br>
                                <small class="text-muted">{{ $item->waktu_daftar->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($item->status == 'A')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($item->status == 'P')
                                    <span class="badge bg-info">Konsultasi</span>
                                @elseif($item->status == 'S')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($item->status == 'C')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('resepsionis.temu-dokter.show', $item->idreservasi_dokter) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($item->status == 'A')
                                <form action="{{ route('resepsionis.temu-dokter.checkin', $item->idreservasi_dokter) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Check-in"
                                            onclick="return confirm('Check-in pasien ini?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <form action="{{ route('resepsionis.temu-dokter.cancel', $item->idreservasi_dokter) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan"
                                            onclick="return confirm('Batalkan appointment ini?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada appointment hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
