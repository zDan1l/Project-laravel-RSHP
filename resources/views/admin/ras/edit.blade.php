@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Ras Hewan</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.ras.update', $item->idras_hewan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="idjenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                            <select class="form-select @error('idjenis_hewan') is-invalid @enderror" 
                                    id="idjenis_hewan" name="idjenis_hewan" required>
                                <option value="">-- Pilih Jenis Hewan --</option>
                                @foreach($jenisHewans as $jenisHewan)
                                    <option value="{{ $jenisHewan->idjenis_hewan }}" {{ old('idjenis_hewan', $item->idjenis_hewan) == $jenisHewan->idjenis_hewan ? 'selected' : '' }}>
                                        {{ $jenisHewan->nama_jenis_hewan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idjenis_hewan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_ras" class="form-label">Nama Ras <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_ras') is-invalid @enderror" 
                                   id="nama_ras" name="nama_ras" value="{{ old('nama_ras', $item->nama_ras) }}" required>
                            @error('nama_ras')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Contoh: Golden Retriever, Persia, Anggora, dll</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.ras.index') }}" class="btn btn-secondary">
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
