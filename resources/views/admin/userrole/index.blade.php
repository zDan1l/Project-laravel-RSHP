@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">User Roles Management</h4>
                        <p class="text-muted mb-0">Manage user role assignments (1 active role per user)</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.user-role.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Assign Role
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Users & Active Roles</h5>
                            <span class="badge bg-info">{{ $users->count() }} Users</span>
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
                                        <th width="35%">User</th>
                                        <th width="25%">Role Aktif</th>
                                        <th width="15%">Status</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $activeRole = $user->userRole->first();
                                            @endphp
                                            @if($activeRole)
                                                <span class="badge bg-success p-2">
                                                    <i class="fas fa-user-shield me-1"></i>
                                                    {{ $activeRole->role->nama_role }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark p-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    No Active Role
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activeRole)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times-circle me-1"></i>Tidak Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.user-role.edit', $user->iduser) }}" 
                                                   class="btn btn-outline-warning" 
                                                   title="Ganti role">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </a>
                                                @if($activeRole)
                                                <form action="{{ route('admin.user-role.destroy', [$user->iduser, $activeRole->idrole]) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('⚠️ HAPUS PERMANENT?\\n\\nRole {{ $activeRole->role->nama_role }} akan dihapus dari {{ $user->nama }}.\\n\\nCatatan: Jika role ini masih digunakan oleh data lain (misal: Temu Dokter), maka proses akan dibatalkan.\\n\\nLanjutkan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus role (permanent)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3"></i>
                                                <h5>No Users Found</h5>
                                                <p>There are no users in the system yet.</p>
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
        .user-avatar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .card {
            border: none;
        }
        
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }
        
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
    </style>
@endsection
