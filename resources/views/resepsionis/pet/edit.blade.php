@extends('layouts.resepsionis')

@section('title', 'Edit Pet')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Data Pet</h1>
            <p class="text-muted mb-0">Perbarui informasi hewan peliharaan</p>
        </div>
        <a href="{{ route('resepsionis.pet.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Form Edit Pet
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

                    <form action="{{ route('resepsionis.pet.update', $pet->idpet) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_pet" class="form-label">
                                    Nama Pet <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nama_pet') is-invalid @enderror" 
                                       id="nama_pet" 
                                       name="nama_pet" 
                                       value="{{ old('nama_pet', $pet->nama_pet) }}"
                                       placeholder="Masukkan nama pet"
                                       required>
                                @error('nama_pet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="idpemilik" class="form-label">
                                    Pemilik <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('idpemilik') is-invalid @enderror" 
                                        id="idpemilik" 
                                        name="idpemilik" 
                                        required>
                                    <option value="">-- Pilih Pemilik --</option>
                                    @foreach($pemiliks as $pemilik)
                                        <option value="{{ $pemilik->idpemilik }}" 
                                                {{ old('idpemilik', $pet->idpemilik) == $pemilik->idpemilik ? 'selected' : '' }}>
                                            {{ $pemilik->user->nama ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idpemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="idjenis_hewan" class="form-label">
                                    Jenis Hewan <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('idjenis_hewan') is-invalid @enderror" 
                                        id="idjenis_hewan" 
                                        name="idjenis_hewan" 
                                        required>
                                    <option value="">-- Pilih Jenis Hewan --</option>
                                    @foreach($jenisHewans as $jenis)
                                        <option value="{{ $jenis->idjenis_hewan }}"
                                                {{ old('idjenis_hewan', $pet->idjenis_hewan) == $jenis->idjenis_hewan ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis_hewan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idjenis_hewan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" 
                                       class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       id="tanggal_lahir" 
                                       name="tanggal_lahir" 
                                       value="{{ old('tanggal_lahir', $pet->tanggal_lahir) }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Jantan" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'Jantan' ? 'selected' : '' }}>
                                        <i class="fas fa-mars"></i> Jantan
                                    </option>
                                    <option value="Betina" {{ old('jenis_kelamin', $pet->jenis_kelamin) == 'Betina' ? 'selected' : '' }}>
                                        <i class="fas fa-venus"></i> Betina
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="warna" class="form-label">Warna</label>
                                <input type="text" 
                                       class="form-control @error('warna') is-invalid @enderror" 
                                       id="warna" 
                                       name="warna" 
                                       value="{{ old('warna', $pet->warna) }}"
                                       placeholder="Contoh: Putih, Hitam, Belang">
                                @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="ciri_khusus" class="form-label">Ciri Khusus</label>
                            <textarea class="form-control @error('ciri_khusus') is-invalid @enderror" 
                                      id="ciri_khusus" 
                                      name="ciri_khusus" 
                                      rows="3" 
                                      placeholder="Contoh: Tahi lalat di kaki kiri, bekas luka di telinga kanan, dll.">{{ old('ciri_khusus', $pet->ciri_khusus) }}</textarea>
                            @error('ciri_khusus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('resepsionis.pet.index') }}" class="btn btn-light">
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
                                Pastikan data yang dimasukkan sudah benar. Perubahan data pet tidak akan mempengaruhi riwayat medis yang sudah ada.
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
