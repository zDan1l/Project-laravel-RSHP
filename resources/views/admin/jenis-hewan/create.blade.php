@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Jenis Hewan Baru</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.jenis-hewan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_jenis" class="form-label">Nama Jenis Hewan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror" 
                                   id="nama_jenis" name="nama_jenis" value="{{ old('nama_jenis') }}" 
                                   placeholder="Contoh: Anjing, Kucing, Kelinci, Burung" required>
                            @error('nama_jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.jenis-hewan.index') }}" class="btn btn-secondary">
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
