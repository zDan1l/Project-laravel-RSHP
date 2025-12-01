@extends('layouts.resepsionis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Daftar Temu Dokter</h4>
                    <p class="text-muted mb-0">Form pendaftaran appointment dengan dokter hewan</p>
                </div>
                <div>
                    <a href="{{ route('resepsionis.temu-dokter.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Form Pendaftaran
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('resepsionis.temu-dokter.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Pet -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Pilih Pet / Hewan Peliharaan <span class="text-danger">*</span>
                            </label>
                            <select name="idpet" class="form-select @error('idpet') is-invalid @enderror" required>
                                <option value="">-- Pilih Pet --</option>
                                @foreach($pets as $pet)
                                <option value="{{ $pet->idpet }}" {{ old('idpet') == $pet->idpet ? 'selected' : '' }}>
                                    {{ $pet->nama }} - {{ $pet->ras->nama_ras ?? '-' }} 
                                    (Pemilik: {{ $pet->pemilik->user->nama ?? '-' }})
                                </option>
                                @endforeach
                            </select>
                            @error('idpet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih hewan peliharaan yang akan diperiksa</small>
                        </div>

                        <!-- Pilih Dokter -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Pilih Dokter <span class="text-danger">*</span>
                            </label>
                            <select name="idrole_user" class="form-select @error('idrole_user') is-invalid @enderror" required>
                                <option value="">-- Pilih Dokter --</option>
                                @foreach($dokters as $dokter)
                                <option value="{{ $dokter->idrole_user }}" {{ old('idrole_user') == $dokter->idrole_user ? 'selected' : '' }}>
                                    {{ $dokter->user->nama ?? '-' }}
                                </option>
                                @endforeach
                            </select>
                            @error('idrole_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <small class="text-muted">Pilih dokter yang akan memeriksa</small>
                        </div>

                        <!-- Info Waktu -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong><br>
                            - Waktu pendaftaran akan dicatat otomatis saat ini<br>
                            - Nomor antrian akan digenerate otomatis berdasarkan urutan pendaftaran<br>
                            - Status awal: <span class="badge bg-warning">Menunggu</span>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('resepsionis.temu-dokter.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
