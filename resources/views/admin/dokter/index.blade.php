@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Data Dokter</h4>
                    <p class="text-muted mb-0">Kelola data dokter di sistem</p>
                </div>
                <div>
                    <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Dokter Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokter Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Dokter</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokter</th>
                                    <th>Email</th>
                                    <th>Terdaftar Sejak</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dokters as $dokter)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i class="fas fa-user-md"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $dokter->nama }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $dokter->email }}</td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($dokter->created_at)->translatedFormat('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.dokter.edit', $dokter->iduser) }}" class="btn btn-outline-warning" title="Edit dokter">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.dokter.destroy', $dokter->iduser) }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ HAPUS DOKTER?\n\nNama: {{ $dokter->nama }}\nEmail: {{ $dokter->email }}\n\nCatatan: Jika dokter masih memiliki data temu dokter atau rekam medis, proses akan dibatalkan.\n\nLanjutkan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus dokter">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-user-md fa-2x mb-2"></i>
                                            <p>Belum ada data dokter</p>
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
@endsection
