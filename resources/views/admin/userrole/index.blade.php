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
                            <h5 class="card-title mb-0">Users with Their Roles</h5>
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
                                        <th width="30%">User Information</th>
                                        <th width="45%">Assigned Roles</th>
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
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @forelse($user->userRole as $userRole)
                                                    <div class="d-flex align-items-center border rounded px-3 py-2 {{ $userRole->status == 1 ? 'bg-success bg-opacity-10 border-success' : 'bg-light border-secondary' }}">
                                                        <div class="me-2">
                                                            <span class="badge bg-{{ $userRole->status == 1 ? 'success' : 'secondary' }}">
                                                                {{ $userRole->role->nama_role }}
                                                            </span>
                                                        </div>
                                                        <div class="me-2">
                                                            @if($userRole->status == 1)
                                                                <i class="fas fa-check-circle text-success" title="Active"></i>
                                                                <small class="text-success fw-bold">Aktif</small>
                                                            @else
                                                                <i class="fas fa-times-circle text-muted" title="Inactive"></i>
                                                                <small class="text-muted">Tidak Aktif</small>
                                                            @endif
                                                        </div>
                                                        <div class="btn-group btn-group-sm">
                                                            @if($userRole->status == 0)
                                                                <form action="{{ route('admin.user-role.activate', [$user->iduser, $userRole->idrole]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Aktifkan role ini">
                                                                        <i class="fas fa-toggle-on"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <form action="{{ route('admin.user-role.destroy', [$user->iduser, $userRole->idrole]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus role {{ $userRole->role->nama_role }} dari {{ $user->name }}?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus role">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        No roles assigned
                                                    </span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.user-role.create') }}?user={{ $user->iduser }}" class="btn btn-sm btn-outline-primary" title="Add/Change role">
                                                <i class="fas fa-user-tag me-1"></i>
                                                Manage Role
                                            </a>
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
