@extends('layouts.perawat')

@section('title', 'Daftar Pasien')

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
                            <td class="text-center">
                                @if($pasien->rekamMedis)
                                    <a href="{{ route('perawat.rekam-medis.show', $pasien->rekamMedis->idrekam_medis) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Lihat Rekam Medis">
                                        <i class="fas fa-file-medical"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary" title="Belum ada rekam medis">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                @endif
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
                <i class="fas fa-info-circle text-info me-2"></i>Informasi
            </h6>
            <ul class="mb-0 small text-muted">
                <li>Klik tombol <i class="fas fa-file-medical text-info"></i> untuk melihat rekam medis pasien</li>
                <li>Anda dapat menambahkan tindakan sederhana pada rekam medis yang telah dibuat dokter</li>
                <li>Anda hanya dapat mengedit atau menghapus tindakan yang Anda input sendiri</li>
            </ul>
        </div>
    </div>
</div>
@endsection