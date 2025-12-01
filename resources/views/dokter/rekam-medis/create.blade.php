@extends('layouts.dokter')

@section('title', 'Buat Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Buat Rekam Medis</h4>
                    <p class="text-muted mb-0">Isi data pemeriksaan pasien</p>
                </div>
                <div>
                    <a href="{{ route('dokter.antrian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Antrian
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
                        <p class="mb-0">{{ $temuDokter->pet->tanggal_lahir ?? '-' }}</p>
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
            <form action="{{ route('dokter.rekam-medis.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idreservasi_dokter" value="{{ $temuDokter->idreservasi_dokter }}">

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-notes-medical me-2"></i>Data Pemeriksaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('anamnesa') is-invalid @enderror" 
                                      id="anamnesa" name="anamnesa" rows="3" 
                                      placeholder="Keluhan yang disampaikan pemilik...">{{ old('anamnesa') }}</textarea>
                            @error('anamnesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Catatan keluhan dari pemilik hewan</small>
                        </div>

                        <div class="mb-3">
                            <label for="temuan_klinis" class="form-label">Temuan Klinis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('temuan_klinis') is-invalid @enderror" 
                                      id="temuan_klinis" name="temuan_klinis" rows="3"
                                      placeholder="Hasil pemeriksaan fisik...">{{ old('temuan_klinis') }}</textarea>
                            @error('temuan_klinis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hasil pemeriksaan fisik (suhu, berat badan, kondisi umum, dll)</small>
                        </div>

                        <div class="mb-3">
                            <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('diagnosa') is-invalid @enderror" 
                                      id="diagnosa" name="diagnosa" rows="3"
                                      placeholder="Diagnosa penyakit...">{{ old('diagnosa') }}</textarea>
                            @error('diagnosa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kesimpulan diagnosa berdasarkan pemeriksaan</small>
                        </div>
                    </div>
                </div>

                <!-- Tindakan Terapi -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-syringe me-2"></i>Tindakan / Terapi
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" id="addTindakan">
                            <i class="fas fa-plus me-1"></i>Tambah Tindakan
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tindakanContainer">
                            <!-- Tindakan items will be added here -->
                        </div>
                        <div class="text-center text-muted py-3" id="noTindakan">
                            <i class="fas fa-info-circle me-1"></i>
                            Belum ada tindakan. Klik "Tambah Tindakan" untuk menambahkan.
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill">
                        <i class="fas fa-save me-2"></i>Simpan Rekam Medis
                    </button>
                    <a href="{{ route('dokter.antrian.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template untuk tindakan -->
<template id="tindakanTemplate">
    <div class="tindakan-item card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="form-label">Kode Tindakan</label>
                    <select class="form-select" name="tindakan[INDEX][idkode_tindakan_terapi]" required>
                        <option value="">-- Pilih Tindakan --</option>
                        @foreach($tindakanTerapi as $tindakan)
                            <option value="{{ $tindakan->idkode_tindakan_terapi }}">
                                {{ $tindakan->kode }} - {{ $tindakan->deskripsi_tindakan_terapi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 mb-2">
                    <label class="form-label">Detail/Catatan</label>
                    <input type="text" class="form-control" name="tindakan[INDEX][detail]" 
                           placeholder="Catatan tambahan...">
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 remove-tindakan">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let tindakanIndex = 0;
    const container = document.getElementById('tindakanContainer');
    const template = document.getElementById('tindakanTemplate');
    const noTindakan = document.getElementById('noTindakan');
    const addBtn = document.getElementById('addTindakan');

    function updateNoTindakan() {
        noTindakan.style.display = container.children.length === 0 ? 'block' : 'none';
    }

    addBtn.addEventListener('click', function() {
        const clone = template.content.cloneNode(true);
        const html = clone.querySelector('.tindakan-item').outerHTML.replace(/INDEX/g, tindakanIndex);
        container.insertAdjacentHTML('beforeend', html);
        tindakanIndex++;
        updateNoTindakan();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-tindakan')) {
            e.target.closest('.tindakan-item').remove();
            updateNoTindakan();
        }
    });
});
</script>
@endpush
@endsection
