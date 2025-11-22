@extends('layouts.resepsionis')

@section('title', 'Registrasi Pet Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Registrasi Pet Baru</h4>
                    <p class="text-muted mb-0">Tambahkan data hewan peliharaan baru</p>
                </div>
                <a href="{{ route('resepsionis.pet.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-dog me-2"></i>Form Registrasi Pet
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('resepsionis.pet.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_pet" class="form-label">
                                Nama Pet <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_pet') is-invalid @enderror" 
                                   id="nama_pet" 
                                   name="nama_pet" 
                                   value="{{ old('nama_pet') }}" 
                                   placeholder="Masukkan nama pet"
                                   required>
                            @error('nama_pet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="idpemilik" class="form-label">
                                Pemilik <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('idpemilik') is-invalid @enderror" 
                                    id="idpemilik" 
                                    name="idpemilik" 
                                    required>
                                <option value="">-- Pilih Pemilik --</option>
                                @foreach($pemiliks as $pemilik)
                                    <option value="{{ $pemilik->idpemilik }}" {{ old('idpemilik') == $pemilik->idpemilik ? 'selected' : '' }}>
                                        {{ $pemilik->user->nama ?? 'N/A' }} - {{ $pemilik->no_wa }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idpemilik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Belum ada pemilik? 
                                <a href="{{ route('resepsionis.pemilik.create') }}" target="_blank" class="text-primary">
                                    Daftar pemilik baru
                                </a>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="idjenis_hewan" class="form-label">
                                Jenis Hewan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('idjenis_hewan') is-invalid @enderror" 
                                    id="idjenis_hewan" 
                                    name="idjenis_hewan" 
                                    required>
                                <option value="">-- Pilih Jenis Hewan --</option>
                                @foreach($jenisHewans as $jenis)
                                    <option value="{{ $jenis->idjenis_hewan }}" {{ old('idjenis_hewan') == $jenis->idjenis_hewan ? 'selected' : '' }}>
                                        {{ $jenis->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idjenis_hewan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">
                                    Jenis Kelamin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                        id="jenis_kelamin" 
                                        name="jenis_kelamin" 
                                        required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Jantan" {{ old('jenis_kelamin') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                                    <option value="Betina" {{ old('jenis_kelamin') == 'Betina' ? 'selected' : '' }}>Betina</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">
                                    Tanggal Lahir <span class="text-muted">(Opsional)</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       id="tanggal_lahir" 
                                       name="tanggal_lahir" 
                                       value="{{ old('tanggal_lahir') }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="warna" class="form-label">
                                Warna <span class="text-muted">(Opsional)</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('warna') is-invalid @enderror" 
                                   id="warna" 
                                   name="warna" 
                                   value="{{ old('warna') }}" 
                                   placeholder="Contoh: Putih, Coklat, Belang">
                            @error('warna')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ciri_khusus" class="form-label">
                                Ciri Khusus <span class="text-muted">(Opsional)</span>
                            </label>
                            <textarea class="form-control @error('ciri_khusus') is-invalid @enderror" 
                                      id="ciri_khusus" 
                                      name="ciri_khusus" 
                                      rows="3" 
                                      placeholder="Contoh: Tanda lahir di kaki kiri, mata biru, dll">{{ old('ciri_khusus') }}</textarea>
                            @error('ciri_khusus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('resepsionis.pet.index') }}" class="btn btn-light">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 500;
        color: #334155;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endsection