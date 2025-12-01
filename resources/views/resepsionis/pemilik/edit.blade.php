@extends('layouts.resepsionis')

@section('title', 'Edit Pemilik')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Data Pemilik</h1>
            <p class="text-muted mb-0">Perbarui informasi pemilik hewan</p>
        </div>
        <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Form Edit Pemilik
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-circle me-2"></i>Terdapat kesalahan:
                            </h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('resepsionis.pemilik.update', $pemilik->idpemilik) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="iduser" class="form-label">
                                User <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('iduser') is-invalid @enderror" 
                                    id="iduser" 
                                    name="iduser" 
                                    required>
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->iduser }}" {{ old('iduser', $pemilik->iduser) == $user->iduser ? 'selected' : '' }}>
                                        {{ $user->nama }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('iduser')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_wa" class="form-label">
                                No. WhatsApp <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('no_wa') is-invalid @enderror" 
                                   id="no_wa" 
                                   name="no_wa" 
                                   value="{{ old('no_wa', $pemilik->no_wa) }}"
                                   placeholder="08xxxxxxxxxx"
                                   required>
                            @error('no_wa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Format: 08xxxxxxxxxx atau +62xxxxxxxxxx</small>
                        </div>

                        <div class="mb-4">
                            <label for="alamat" class="form-label">
                                Alamat <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="3" 
                                      placeholder="Masukkan alamat lengkap"
                                      required>{{ old('alamat', $pemilik->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-light">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Perbarui Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card border-info mt-3">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Informasi Penting</h6>
                            <small class="text-muted">
                                Pastikan data yang dimasukkan sudah benar. Perubahan data pemilik tidak akan mempengaruhi data hewan peliharaan yang sudah terdaftar.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
}
</style>
@endsection
