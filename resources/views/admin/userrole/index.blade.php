@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">User Roles Management</h4>
                        <p class="text-muted mb-0">Manage user role assignments</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
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
                                        <th width="25%">User Information</th>
                                        <th width="50%">Assigned Roles</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
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
                                                    <div class="d-flex align-items-center bg-light rounded px-2 py-1">
                                                        <span class="badge bg-{{ $userRole->status == 1 ? 'success' : 'warning' }} me-2">
                                                            {{ $userRole->role->nama_role }}
                                                        </span>
                                                        <small class="text-muted me-2">
                                                            {{ ucfirst($userRole->status == 1 ? 'Aktif' : 'Tidak Aktif') }}
                                                        </small>
                                                        <button class="btn btn-outline-danger btn-sm p-1" 
                                                                onclick="removeRole({{ $user->iduser }}, {{ $userRole->idrole }})"
                                                                title="Remove role">
                                                                Hapus
                                                            <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                                                        </button>
                                                    </div>
                                                @empty
                                                    <span class="text-muted fst-italic">No roles assigned</span>
                                                @endforelse
                                            </div>
                                        </td>   
                                        <td>
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="addRole({{ $user->iduser }})"
                                                    title="Add new role">
                                                    Tambah
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
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
@endsection
