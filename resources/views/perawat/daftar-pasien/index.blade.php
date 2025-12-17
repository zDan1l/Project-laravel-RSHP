@extends('layouts.perawat')

@section('title', 'Daftar Pasien')

@section('styles')
<style>
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.25rem;
        text-align: center;
        min-width: 80px;
    }
    .status-waiting {
        background-color: #6c757d;
        color: white;
    }
    .status-queue {
        background-color: #ffc107;
        color: #000;
    }
    .status-process {
        background-color: #0dcaf0;
        color: #000;
    }
    .status-done {
        background-color: #198754;
        color: white;
    }
    .status-cancelled {
        background-color: #dc3545;
        color: white;
    }
    .btn-group {
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h4>Daftar Pasien</h4>
        <p>Daftar pasien yang perlu didampingi</p>
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

    <!-- Patients Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Daftar Pasien</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">No. Urut</th>
                            <th width="15%">Nama Pet</th>
                            <th width="12%">Jenis/Ras</th>
                            <th width="15%">Pemilik</th>
                            <th width="12%">Dokter</th>
                            <th width="13%">Waktu Daftar</th>
                            <th width="10%">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pasienList as $index => $pasien)
                        <tr>
                            <td>{{ $pasienList->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    #{{ str_pad($pasien->no_urut ?? 0, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $pasien->pet->nama ?? '-' }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ $pasien->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }}</small><br>
                                <strong>{{ $pasien->pet->ras->nama_ras ?? '-' }}</strong>
                            </td>
                            <td>
                                {{ $pasien->pet->pemilik->user->nama ?? '-' }}
                            </td>
                            <td>
                                {{ $pasien->dokter->nama ?? '-' }}
                            </td>
                            <td>
                                {{ $pasien->waktu_daftar ? $pasien->waktu_daftar->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'W' => ['class' => 'status-waiting', 'text' => 'Waiting'],
                                        'A' => ['class' => 'status-queue', 'text' => 'Antri'],
                                        'P' => ['class' => 'status-process', 'text' => 'Proses'],
                                        'S' => ['class' => 'status-done', 'text' => 'Selesai'],
                                        'C' => ['class' => 'status-cancelled', 'text' => 'Batal']
                                    ];
                                    $status = $statusMap[$pasien->status] ?? ['class' => 'bg-secondary', 'text' => 'Unknown'];
                                @endphp
                                <span class="status-badge {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                @php
                                    // Cari rekam medis dari pasien ini
                                    $rekamMedis = \App\Models\RekamMedis::where('idreservasi_dokter', $pasien->idreservasi_dokter)->first();
                                @endphp
                                
                                <div class="d-flex justify-content-center gap-1">
                                    @if($rekamMedis)
                                        {{-- TOMBOL LIHAT: Sudah ada rekam medis --}}
                                        <a href="{{ route('perawat.rekam-medis.show', $rekamMedis->idrekam_medis) }}" 
                                           class="btn btn-sm btn-info text-white fw-bold"
                                           style="min-width: 100px; padding: 6px 12px;">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </a>
                                    @else
                                        {{-- TOMBOL BUAT: Belum ada rekam medis --}}
                                        @if($pasien->status == 'W' || $pasien->status == 'A' || $pasien->status == 'P')
                                            <a href="{{ route('perawat.rekam-medis.create', $pasien->idreservasi_dokter) }}" 
                                               class="btn btn-sm btn-success text-white fw-bold"
                                               style="min-width: 100px; padding: 6px 12px;">
                                                <i class="fas fa-plus-circle me-1"></i>Buat RM
                                            </a>
                                        @elseif($pasien->status == 'S')
                                            <span class="badge bg-success fs-6" style="min-width: 100px; padding: 6px 12px;">
                                                <i class="fas fa-check me-1"></i>Selesai
                                            </span>
                                        @elseif($pasien->status == 'C')
                                            <span class="badge bg-danger fs-6" style="min-width: 100px; padding: 6px 12px;">
                                                <i class="fas fa-times me-1"></i>Batal
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6" style="min-width: 100px; padding: 6px 12px;">
                                                <i class="fas fa-question me-1"></i>{{ $pasien->status ?? 'N/A' }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                <p class="text-muted mb-0">Belum ada pasien</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pasienList->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $pasienList->firstItem() }} - {{ $pasienList->lastItem() }} dari {{ $pasienList->total() }} pasien
                </div>
                <div>
                    {{ $pasienList->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Info Card -->
    <div class="card mt-4 border-start border-5 border-info">
        <div class="card-body">
            <h6 class="fw-bold mb-2">
                <i class="fas fa-info-circle text-info me-2"></i>Informasi & Panduan
            </h6>
            <ul class="mb-0 small text-muted">
                <li><strong>Tombol Aksi:</strong>
                    <ul>
                        <li><span class="badge bg-success"><i class="fas fa-plus-circle"></i> Buat RM</span> - Buat rekam medis baru untuk pasien</li>
                        <li><span class="badge bg-info"><i class="fas fa-eye"></i> Lihat</span> - Lihat detail rekam medis yang sudah dibuat</li>
                    </ul>
                </li>
                <li><strong>Alur Kerja:</strong>
                    <ol class="mb-0">
                        <li>Perawat membuat rekam medis awal (anamnesa, temuan klinis, diagnosa)</li>
                        <li>Dokter melanjutkan dengan menambahkan detail tindakan terapi</li>
                        <li>Status pasien: W (Waiting) → A (Antri) → P (Proses) → S (Selesai)</li>
                    </ol>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection