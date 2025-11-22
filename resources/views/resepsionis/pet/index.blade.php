@extends('layouts.resepsionis')

@section('title', 'Data Pet')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Data Pet</h4>
                    <p class="text-muted mb-0">Kelola data hewan peliharaan</p>
                </div>
                <div>
                    <a href="{{ route('resepsionis.pet.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Registrasi Pet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-paw me-2"></i>Daftar Pet
                        </h5>
                        <span class="badge bg-primary">{{ $pets->count() }} Pet</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Nama Pet</th>
                                    <th width="20%">Pemilik</th>
                                    <th width="15%">Jenis Hewan</th>
                                    <th width="10%">Kelamin</th>
                                    <th width="15%">Warna</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pets as $pet)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="pet-avatar me-2">
                                                <i class="fas fa-paw"></i>
                                            </div>
                                            <strong>{{ $pet->nama_pet }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($pet->pemilik)
                                            <i class="fas fa-user text-primary me-1"></i>
                                            {{ $pet->pemilik->user->nama ?? 'N/A' }}
                                            <br><small class="text-muted">{{ $pet->pemilik->no_wa }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pet->jenisHewan)
                                            <span class="badge bg-info">{{ $pet->jenisHewan->nama_jenis }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pet->jenis_kelamin == 'Jantan')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-mars me-1"></i>Jantan
                                            </span>
                                        @elseif($pet->jenis_kelamin == 'Betina')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-venus me-1"></i>Betina
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $pet->warna ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('resepsionis.pet.show', $pet->idpet) }}" 
                                               class="btn btn-info"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('resepsionis.pet.edit', $pet->idpet) }}" 
                                               class="btn btn-warning"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('resepsionis.pet.destroy', $pet->idpet) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus data pet ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-paw fa-3x mb-3"></i>
                                            <h5>Belum Ada Data Pet</h5>
                                            <p>Klik tombol "Registrasi Pet" untuk menambahkan data</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pet-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
@endsection
