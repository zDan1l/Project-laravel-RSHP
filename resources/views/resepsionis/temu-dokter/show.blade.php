@extends('layouts.resepsionis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Detail Temu Dokter</h4>
                    <p class="text-muted mb-0">Informasi lengkap appointment</p>
                </div>
                <div>
                    <a href="{{ route('resepsionis.temu-dokter.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-medical me-2"></i>Informasi Appointment
                        </h5>
                        <span class="badge bg-light text-dark fs-5">
                            #{{ str_pad($temuDokter->no_urut, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Status:</div>
                        <div class="col-md-8">
                            @if($temuDokter->status == 'A')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($temuDokter->status == 'P')
                                <span class="badge bg-info">Sedang Konsultasi</span>
                            @elseif($temuDokter->status == 'S')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($temuDokter->status == 'C')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Waktu Daftar:</div>
                        <div class="col-md-8">
                            {{ $temuDokter->waktu_daftar->format('d F Y, H:i') }} WIB
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Informasi Pemilik</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Nama Pemilik:</div>
                        <div class="col-md-8">{{ $temuDokter->pet->pemilik->user->nama ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">No. WhatsApp:</div>
                        <div class="col-md-8">{{ $temuDokter->pet->pemilik->no_wa ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Alamat:</div>
                        <div class="col-md-8">{{ $temuDokter->pet->pemilik->alamat ?? '-' }}</div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Informasi Pet</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Nama Pet:</div>
                        <div class="col-md-8">{{ $temuDokter->pet->nama ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Ras:</div>
                        <div class="col-md-8">{{ $temuDokter->pet->ras->nama_ras ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Jenis Hewan:</div>
                        <div class="col-md-8">{{ $temuDokter->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }}</div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Dokter yang Memeriksa</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-semibold">Nama Dokter:</div>
                        <div class="col-md-8">{{ $temuDokter->roleUser->user->nama ?? '-' }}</div>
                    </div>
                </div>

                @if($temuDokter->status == 'A')
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <form action="{{ route('resepsionis.temu-dokter.checkin', $temuDokter->idreservasi_dokter) }}" 
                              method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('Check-in pasien ini?')">
                                <i class="fas fa-check me-1"></i>Check-in Pasien
                            </button>
                        </form>

                        <form action="{{ route('resepsionis.temu-dokter.cancel', $temuDokter->idreservasi_dokter) }}" 
                              method="POST" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Batalkan appointment ini?')">
                                <i class="fas fa-times me-1"></i>Batalkan Appointment
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Keterangan Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-warning me-2">Menunggu</span>
                        <small>Pasien terdaftar dan menunggu giliran</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-info me-2">Konsultasi</span>
                        <small>Pasien sedang diperiksa dokter</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-success me-2">Selesai</span>
                        <small>Pemeriksaan telah selesai</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-danger me-2">Dibatalkan</span>
                        <small>Appointment dibatalkan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
