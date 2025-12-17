@extends('layouts.resepsionis')

@section('title', 'Data Pemilik')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Data Pemilik</h4>
                    <p class="text-muted mb-0">Kelola data pemilik hewan peliharaan</p>
                </div>
                <div>
                    <a href="{{ route('resepsionis.pemilik.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Pemilik
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
                            <i class="fas fa-users me-2"></i>Daftar Pemilik
                        </h5>
                        <span class="badge bg-primary">{{ $pemiliks->count() }} Pemilik</span>
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
                                    <th width="20%">Nama Pemilik</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">No. WhatsApp</th>
                                    <th width="20%">Alamat</th>
                                    <th width="10%">Role</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemiliks as $pemilik)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ strtoupper(substr($pemilik->user->nama ?? 'N', 0, 2)) }}
                                            </div>
                                            <strong>{{ $pemilik->user->nama ?? 'N/A' }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $pemilik->user->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-success me-1"></i>
                                        {{ $pemilik->no_wa }}
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($pemilik->alamat, 40) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-user-tag me-1"></i>Pemilik
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('resepsionis.pemilik.show', $pemilik->idpemilik) }}" 
                                               class="btn btn-info"
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('resepsionis.pemilik.edit', $pemilik->idpemilik) }}" 
                                               class="btn btn-warning"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('resepsionis.pemilik.destroy', $pemilik->idpemilik) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus data pemilik ini?')">
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
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <h5>Belum Ada Data Pemilik</h5>
                                            <p>Klik tombol "Tambah Pemilik" untuk menambahkan data</p>
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
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.85rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
@endsection
