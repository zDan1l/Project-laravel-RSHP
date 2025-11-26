@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Jenis Hewan</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.jenis-hewan.update', $item->idjenis_hewan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama_jenis_hewan" class="form-label">Nama Jenis Hewan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_jenis_hewan') is-invalid @enderror" 
                                   id="nama_jenis_hewan" name="nama_jenis_hewan" value="{{ old('nama_jenis_hewan', $item->nama_jenis_hewan) }}" required>
                            @error('nama_jenis_hewan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Anjing, Kucing, Kelinci, dll</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
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
