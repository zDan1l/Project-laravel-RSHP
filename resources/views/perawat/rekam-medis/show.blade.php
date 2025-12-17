@extends('layouts.perawat')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Detail Rekam Medis</h4>
                    <p class="text-muted mb-0">Informasi lengkap rekam medis pasien</p>
                </div>
                <a href="{{ route('perawat.daftar-pasien.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
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

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Rekam Medis Info - READ ONLY -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2"></i>Informasi Rekam Medis
                        <span class="badge bg-light text-dark ms-2">Read Only</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">ID Rekam Medis</small>
                            <strong>{{ $rekamMedis->idrekam_medis }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">No. Reservasi</small>
                            <strong>#{{ str_pad($rekamMedis->temuDokter->no_urut ?? '-', 3, '0', STR_PAD_LEFT) }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Tanggal Pemeriksaan</small>
                            <strong>{{ $rekamMedis->created_at ? $rekamMedis->created_at->format('d F Y, H:i') : '-' }}</strong>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Anamnesa</label>
                        <p class="border rounded p-3 bg-light mb-0">
                            {{ $rekamMedis->anamnesa ?? 'Tidak ada catatan' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Temuan Klinis</label>
                        <p class="border rounded p-3 bg-light mb-0">
                            {{ $rekamMedis->temuan_klinis ?? 'Tidak ada catatan' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Diagnosa</label>
                        <p class="border rounded p-3 bg-light mb-0">
                            {{ $rekamMedis->diagnosa ?? 'Tidak ada diagnosa' }}
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Dokter Pemeriksa</small>
                            <strong>{{ $rekamMedis->dokter->nama ?? 'Tidak diketahui' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Tindakan Terapi - READ ONLY -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-syringe me-2"></i>Detail Tindakan Terapi
                        <span class="badge bg-dark ms-2">Read Only - Dikelola Dokter</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning border-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Perawat hanya dapat <strong>melihat</strong> detail tindakan terapi. Penambahan, pengeditan, dan penghapusan tindakan hanya dapat dilakukan oleh <strong>Dokter</strong>.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Kode</th>
                                    <th width="30%">Tindakan Terapi</th>
                                    <th width="35%">Detail Rincian</th>
                                    <th width="18%">Input Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekamMedis->detailRekamMedis as $index => $detail)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td><span class="badge bg-primary">{{ $detail->kodeTindakanTerapi->kode ?? '-' }}</span></td>
                                    <td>{{ $detail->kodeTindakanTerapi->deskripsi_tindakan_terapi ?? '-' }}</td>
                                    <td>{{ $detail->detail ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($detail->tipe_petugas == 'perawat')
                                            <span class="badge bg-success"><i class="fas fa-user-nurse me-1"></i>Perawat</span>
                                        @elseif($detail->tipe_petugas == 'dokter')
                                            <span class="badge bg-info"><i class="fas fa-user-md me-1"></i>Dokter</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-user me-1"></i>Sistem</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted mb-0">Belum ada tindakan terapi yang dicatat</p>
                                        <small class="text-muted">Dokter akan menambahkan detail tindakan terapi</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Pet Info -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-paw me-2"></i>Informasi Pet</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Nama Pet</small>
                                    <strong class="fs-5">{{ $rekamMedis->temuDokter->pet->nama ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Ras</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->ras->nama_ras ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Jenis Hewan</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Jenis Kelamin</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->jenis_kelamin == 'L' ? 'Jantan' : 'Betina' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Tanggal Lahir</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->tanggal_lahir ?? '-' }}</strong>
                                </div>
                                <div class="mb-0">
                                    <small class="text-muted d-block">Warna/Tanda</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->warna_tanda ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Info -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pemilik</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Nama Pemilik</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->pemilik->user->nama ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">No. WhatsApp</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->pemilik->no_wa ?? '-' }}</strong>
                                </div>
                                <div class="mb-0">
                                    <small class="text-muted d-block">Alamat</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->pemilik->alamat ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
