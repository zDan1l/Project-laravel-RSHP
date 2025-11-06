@extends('layouts.admin')

@section('title', 'Pet Management')

@section('page-title', 'Pet Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Pet Management</h4>
                    <p class="text-muted mb-0">Manage all registered pets in the system</p>
                </div>
                <div>
                    <a href="{{ route('admin.pets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Register New Pet
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Pets Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Registered Pets</h5>
                        <small class="text-muted">Total: 324 pets</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Warna Tanda</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Ras</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pets as $pet)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <i class="fas fa-dog"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $pet->nama }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $pet->tanggal_lahir }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $pet->warna_tanda }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $pet->jenis_kelamin }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $pet->ras->nama_ras }}</div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">Showing 1 to 3 of 324 entries</small>
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
