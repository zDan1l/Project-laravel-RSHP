@extends('layouts.dokter')

@section('title', 'Edit Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Edit Rekam Medis</h4>
                    <p class="text-muted mb-0">Perbarui data pemeriksaan pasien</p>
                </div>
                <div>
                    <a href="{{ route('dokter.rekam-medis.show', $rekamMedis->idrekam_medis) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Informasi Pasien -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pasien
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nama Pet</label>
                        <p class="mb-0 fw-bold fs-5">{{ $rekamMedis->temuDokter->pet->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Ras / Jenis</label>
                        <p class="mb-0">
                            {{ $rekamMedis->temuDokter->pet->ras->nama_ras ?? '-' }} 
                            <br><small class="text-muted">({{ $rekamMedis->temuDokter->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }})</small>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Jenis Kelamin</label>
                        <p class="mb-0">{{ $rekamMedis->temuDokter->pet->jenis_kelamin == 'L' ? 'Jantan' : 'Betina' }}</p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Pemilik</label>
                        <p class="mb-0 fw-semibold">{{ $rekamMedis->temuDokter->pet->pemilik->user->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">No. WhatsApp</label>
                        <p class="mb-0">{{ $rekamMedis->temuDokter->pet->pemilik->no_wa ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Rekam Medis -->
        <div class="col-lg-8">
            <form action="{{ route('dokter.rekam-medis.update', $rekamMedis->idrekam_medis) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-notes-medical me-2"></i>Data Pemeriksaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">ID Rekam Medis</small>
                                <strong>{{ $rekamMedis->idrekam_medis }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Tanggal Pemeriksaan</small>
                                <strong>{{ $rekamMedis->created_at ? $rekamMedis->created_at->format('d F Y, H:i') : '-' }}</strong>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('anamnesa') is-invalid @enderror" 
                                      id="anamnesa" name="anamnesa" rows="3" 
                                      placeholder="Keluhan yang disampaikan pemilik...">{{ old('anamnesa', $rekamMedis->anamnesa) }}</textarea>
                            @error('anamnesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="temuan_klinis" class="form-label">Temuan Klinis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('temuan_klinis') is-invalid @enderror" 
                                      id="temuan_klinis" name="temuan_klinis" rows="3"
                                      placeholder="Hasil pemeriksaan fisik...">{{ old('temuan_klinis', $rekamMedis->temuan_klinis) }}</textarea>
                            @error('temuan_klinis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('diagnosa') is-invalid @enderror" 
                                      id="diagnosa" name="diagnosa" rows="3"
                                      placeholder="Diagnosa penyakit...">{{ old('diagnosa', $rekamMedis->diagnosa) }}</textarea>
                            @error('diagnosa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('dokter.rekam-medis.show', $rekamMedis->idrekam_medis) }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
