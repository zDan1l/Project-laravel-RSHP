@extends('layouts.resepsionis')

@section('title', 'Registrasi Pemilik Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Registrasi Pemilik Baru</h4>
                    <p class="text-muted mb-0">Tambahkan data pemilik hewan baru</p>
                </div>
                <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-user-plus me-2"></i>Form Registrasi Pemilik
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('resepsionis.pemilik.store') }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Info:</strong> Nama pemilik akan menggunakan nama dari akun yang sedang login ({{ auth()->user()->nama }})
                        </div>

                        <div class="mb-3">
                            <label for="no_wa" class="form-label">
                                No. WhatsApp <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('no_wa') is-invalid @enderror" 
                                   id="no_wa" 
                                   name="no_wa" 
                                   value="{{ old('no_wa') }}" 
                                   placeholder="08xxxxxxxxxx"
                                   required>
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: 08xxxxxxxxxx atau +62xxxxxxxxxx</small>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">
                                Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="4" 
                                      placeholder="Masukkan alamat lengkap dengan RT/RW, Kelurahan, Kecamatan, Kota"
                                      required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-light">
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
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endsection