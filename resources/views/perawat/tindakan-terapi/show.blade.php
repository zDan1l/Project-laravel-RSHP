@extends('layouts.perawat')

@section('title', 'Detail Tindakan Terapi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Detail Tindakan Terapi</h4>
                    <p class="text-muted mb-0">Informasi lengkap tindakan terapi</p>
                </div>
                <div>
                    <a href="{{ route('perawat.tindakan-terapi.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <a href="{{ route('perawat.tindakan-terapi.edit', $tindakan->idkode_tindakan_terapi) }}" class="btn btn-warning text-white">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-medical-alt me-2"></i>Informasi Tindakan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block mb-1">Kode Tindakan</small>
                                        <h4><span class="badge bg-primary">{{ $tindakan->kode }}</span></h4>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block mb-1">ID Tindakan</small>
                                        <strong class="fs-5">{{ $tindakan->idkode_tindakan_terapi }}</strong>
                                    </div>
                                </div>

                                <hr>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fas fa-align-left me-1"></i> Deskripsi Tindakan
                                    </label>
                                    <div class="border rounded p-3 bg-light">
                                        <p class="mb-0">{{ $tindakan->deskripsi_tindakan_terapi }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-tags me-1"></i> Kategori
                                        </small>
                                        <h6 class="mb-0">
                                            <span class="badge bg-success">{{ $tindakan->kategori->nama_kategori ?? '-' }}</span>
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-stethoscope me-1"></i> Kategori Klinis
                                        </small>
                                        <h6 class="mb-0">
                                            <span class="badge bg-info">{{ $tindakan->kategoriKlinis->nama_kategori_klinis ?? '-' }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Action Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi</h6>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('perawat.tindakan-terapi.edit', $tindakan->idkode_tindakan_terapi) }}" class="btn btn-warning w-100 mb-2 text-white">
                                    <i class="fas fa-edit me-1"></i> Edit Tindakan
                                </a>
                                <form action="{{ route('perawat.tindakan-terapi.destroy', $tindakan->idkode_tindakan_terapi) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus tindakan terapi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-1"></i> Hapus Tindakan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 small">
                                    Data tindakan terapi ini dapat digunakan untuk pencatatan 
                                    rekam medis dan tindakan yang dilakukan pada pasien hewan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
