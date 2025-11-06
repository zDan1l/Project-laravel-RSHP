@extends('layouts.perawat')

@section('title', 'Edit Tindakan Terapi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Edit Tindakan Terapi</h4>
                    <p class="text-muted mb-0">Perbarui informasi tindakan terapi</p>
                </div>
                <a href="{{ route('perawat.tindakan-terapi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="{{ route('perawat.tindakan-terapi.update', $tindakan->idkode_tindakan_terapi) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label for="kode" class="form-label">
                                            Kode Tindakan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                                               id="kode" name="kode" maxlength="5" placeholder="Contoh: T001" 
                                               value="{{ old('kode', $tindakan->kode) }}" required>
                                        <small class="text-muted">Maksimal 5 karakter</small>
                                        @error('kode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi_tindakan_terapi" class="form-label">
                                            Deskripsi Tindakan <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                                                  id="deskripsi_tindakan_terapi" name="deskripsi_tindakan_terapi" 
                                                  rows="4" maxlength="1000" 
                                                  placeholder="Masukkan deskripsi lengkap tindakan terapi..." 
                                                  required>{{ old('deskripsi_tindakan_terapi', $tindakan->deskripsi_tindakan_terapi) }}</textarea>
                                        <small class="text-muted">Maksimal 1000 karakter</small>
                                        @error('deskripsi_tindakan_terapi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="idkategori" class="form-label">
                                            Kategori <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('idkategori') is-invalid @enderror" 
                                                id="idkategori" name="idkategori" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($kategoriList as $kat)
                                                <option value="{{ $kat->idkategori }}" 
                                                        {{ old('idkategori', $tindakan->idkategori) == $kat->idkategori ? 'selected' : '' }}>
                                                    {{ $kat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('idkategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="idkategori_klinis" class="form-label">
                                            Kategori Klinis <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('idkategori_klinis') is-invalid @enderror" 
                                                id="idkategori_klinis" name="idkategori_klinis" required>
                                            <option value="">-- Pilih Kategori Klinis --</option>
                                            @foreach($kategoriKlinisList as $katKlinis)
                                                <option value="{{ $katKlinis->idkategori_klinis }}" 
                                                        {{ old('idkategori_klinis', $tindakan->idkategori_klinis) == $katKlinis->idkategori_klinis ? 'selected' : '' }}>
                                                    {{ $katKlinis->nama_kategori_klinis }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('idkategori_klinis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Update
                                        </button>
                                        <a href="{{ route('perawat.tindakan-terapi.index') }}" class="btn btn-secondary">
                                            Batal
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>Petunjuk Pengisian:</strong></p>
                                <ul class="small mb-0">
                                    <li>Kode tindakan harus unik dan maksimal 5 karakter</li>
                                    <li>Deskripsi tindakan harus jelas dan lengkap</li>
                                    <li>Pilih kategori dan kategori klinis yang sesuai</li>
                                    <li>Semua field wajib diisi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
