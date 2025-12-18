@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Users Management</h4>
                    <p class="text-muted mb-0">Manage system users and their permissions</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Users List</h5>
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

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    @php
                                        $activeRole = $user->getActiveRole();
                                        $totalRoles = $user->userRole()->count();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->nama }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($activeRole)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-user-shield me-1"></i>{{ $activeRole->role->nama_role }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No Role</span>
                                            @endif
                                            @if($totalRoles > 1)
                                                <small class="text-muted d-block mt-1">+{{ $totalRoles - 1 }} more</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.users.edit', $user->iduser) }}" class="btn btn-outline-warning" title="Edit user">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($totalRoles > 0)
                                                    <a href="{{ route('admin.user-role.index') }}" class="btn btn-outline-info" title="Manage roles">
                                                        <i class="fas fa-user-shield"></i>
                                                    </a>
                                                    <form action="{{ route('admin.user-role.delete-all', $user->iduser) }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ HAPUS SEMUA ROLE?\n\nUser: {{ $user->nama }}\nTotal role: {{ $totalRoles }}\n\nSemua role akan dihapus dari user ini.\nSetelah itu, user dapat dihapus jika tidak terikat data lain.\n\nLanjutkan?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-secondary" title="Hapus semua role">
                                                            <i class="fas fa-user-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.users.destroy', $user->iduser) }}" method="POST" class="d-inline" onsubmit="return confirm('⚠️ HAPUS USER?\n\nUser: {{ $user->nama }}\nEmail: {{ $user->email }}\nRole: {{ $totalRoles }} role\n\n{{ $totalRoles > 0 ? "Semua role akan ikut terhapus.\n\n" : "" }}Catatan: Jika user masih digunakan oleh data lain (pemilik, rekam medis, temu dokter), proses akan dibatalkan.\n\nLanjutkan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus user{{ $totalRoles > 0 ? ' (beserta role-nya)' : '' }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <p>No users found</p>
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