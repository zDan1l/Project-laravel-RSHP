@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Kode Tindakan Terapi</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.kodentindakan.update', $item->idkode_tindakan_terapi) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Tindakan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                                   id="kode" name="kode" value="{{ old('kode', $item->kode) }}" required>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: KT001, OP002</small>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_tindakan_terapi" class="form-label">Deskripsi Tindakan Terapi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                                      id="deskripsi_tindakan_terapi" name="deskripsi_tindakan_terapi" rows="3" required>{{ old('deskripsi_tindakan_terapi', $item->deskripsi_tindakan_terapi) }}</textarea>
                            @error('deskripsi_tindakan_terapi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('idkategori') is-invalid @enderror" 
                                            id="idkategori" name="idkategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->idkategori }}" {{ old('idkategori', $item->idkategori) == $k->idkategori ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idkategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idkategori_klinis" class="form-label">Kategori Klinis <span class="text-danger">*</span></label>
                                    <select class="form-select @error('idkategori_klinis') is-invalid @enderror" 
                                            id="idkategori_klinis" name="idkategori_klinis" required>
                                        <option value="">-- Pilih Kategori Klinis --</option>
                                        @foreach($kategoriKlinis as $kk)
                                            <option value="{{ $kk->idkategori_klinis }}" {{ old('idkategori_klinis', $item->idkategori_klinis) == $kk->idkategori_klinis ? 'selected' : '' }}>
                                                {{ $kk->nama_kategori_klinis }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idkategori_klinis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.kodentindakan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
