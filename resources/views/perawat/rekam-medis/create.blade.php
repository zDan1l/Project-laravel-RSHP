@extends('layouts.perawat')

@section('title', 'Buat Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Buat Rekam Medis</h4>
                    <p class="text-muted mb-0">Input data pemeriksaan awal pasien</p>
                </div>
                <div>
                    <a href="{{ route('perawat.daftar-pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pasien
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

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
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
                        <p class="mb-0 fw-bold fs-5">{{ $temuDokter->pet->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Ras / Jenis</label>
                        <p class="mb-0">
                            {{ $temuDokter->pet->ras->nama_ras ?? '-' }} 
                            <br><small class="text-muted">({{ $temuDokter->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }})</small>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Jenis Kelamin</label>
                        <p class="mb-0">{{ $temuDokter->pet->jenis_kelamin == 'L' ? 'Jantan' : 'Betina' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Tanggal Lahir</label>
                        <p class="mb-0">{{ $temuDokter->pet->tanggal_lahir ? \Carbon\Carbon::parse($temuDokter->pet->tanggal_lahir)->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Warna/Tanda</label>
                        <p class="mb-0">{{ $temuDokter->pet->warna_tanda ?? '-' }}</p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Pemilik</label>
                        <p class="mb-0 fw-semibold">{{ $temuDokter->pet->pemilik->user->nama ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">No. WhatsApp</label>
                        <p class="mb-0">{{ $temuDokter->pet->pemilik->no_wa ?? '-' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">Alamat</label>
                        <p class="mb-0">{{ $temuDokter->pet->pemilik->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Rekam Medis -->
        <div class="col-lg-8">
            <form action="{{ route('perawat.rekam-medis.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idreservasi_dokter" value="{{ $temuDokter->idreservasi_dokter }}">

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-notes-medical me-2"></i>Data Pemeriksaan Awal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Perawat mengisi data pemeriksaan awal. Dokter akan melanjutkan dengan detail tindakan terapi.
                        </div>

                        <div class="mb-3">
                            <label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('anamnesa') is-invalid @enderror" 
                                      id="anamnesa" name="anamnesa" rows="3" 
                                      placeholder="Keluhan yang disampaikan pemilik, riwayat penyakit, dll...">{{ old('anamnesa') }}</textarea>
                            @error('anamnesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Hewan mengalami diare sejak 2 hari yang lalu, nafsu makan menurun</small>
                        </div>

                        <div class="mb-3">
                            <label for="temuan_klinis" class="form-label">Temuan Klinis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('temuan_klinis') is-invalid @enderror" 
                                      id="temuan_klinis" name="temuan_klinis" rows="4" 
                                      placeholder="Hasil pemeriksaan fisik awal (suhu, nadi, respirasi, dll)...">{{ old('temuan_klinis') }}</textarea>
                            @error('temuan_klinis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Suhu: 39.5°C, Nadi: 120x/menit, Respirasi: 30x/menit, Dehidrasi sedang</small>
                        </div>

                        <div class="mb-3">
                            <label for="diagnosa" class="form-label">Diagnosa Awal <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('diagnosa') is-invalid @enderror" 
                                      id="diagnosa" name="diagnosa" rows="3" 
                                      placeholder="Diagnosa atau dugaan sementara berdasarkan pemeriksaan awal...">{{ old('diagnosa') }}</textarea>
                            @error('diagnosa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Suspect Gastroenteritis, perlu pemeriksaan lebih lanjut</small>
                        </div>

                        <hr>

                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Catatan:</strong> Setelah data rekam medis disimpan, dokter akan menambahkan detail tindakan terapi yang diperlukan.
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perawat.daftar-pasien.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Rekam Medis
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
