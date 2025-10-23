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
                    <a href="{{ route('admin.pet.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Register New Pet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">324</h3>
                            <p class="mb-0">Total Pets</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-paw fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">156</h3>
                            <p class="mb-0">Dogs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dog fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">98</h3>
                            <p class="mb-0">Cats</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cat fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-1">70</h3>
                            <p class="mb-0">Others</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dove fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by pet name or owner...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Animal Type</label>
                                <select class="form-select" name="type">
                                    <option value="">All Types</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="bird">Bird</option>
                                    <option value="rabbit">Rabbit</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Age Range</label>
                                <select class="form-select" name="age">
                                    <option value="">All Ages</option>
                                    <option value="puppy">Puppy/Kitten (0-1)</option>
                                    <option value="young">Young (1-3)</option>
                                    <option value="adult">Adult (3-7)</option>
                                    <option value="senior">Senior (7+)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender">
                                    <option value="">All Genders</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.pet.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
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
                                    <th>#</th>
                                    <th>Pet Info</th>
                                    <th>Owner</th>
                                    <th>Type & Breed</th>
                                    <th>Age & Gender</th>
                                    <th>Status</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i class="fas fa-dog"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">Buddy</div>
                                                <small class="text-muted">ID: PET001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">John Smith</div>
                                            <small class="text-muted">john@email.com</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">Dog</span><br>
                                        <small class="text-muted">Golden Retriever</small>
                                    </td>
                                    <td>
                                        3 years<br>
                                        <small class="text-muted">Male</small>
                                    </td>
                                    <td><span class="badge bg-success">Healthy</span></td>
                                    <td>2024-10-20</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" title="Medical Record">
                                                <i class="fas fa-file-medical"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i class="fas fa-cat"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">Whiskers</div>
                                                <small class="text-muted">ID: PET002</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">Sarah Johnson</div>
                                            <small class="text-muted">sarah@email.com</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Cat</span><br>
                                        <small class="text-muted">Persian</small>
                                    </td>
                                    <td>
                                        2 years<br>
                                        <small class="text-muted">Female</small>
                                    </td>
                                    <td><span class="badge bg-warning">Under Treatment</span></td>
                                    <td>2024-10-22</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" title="Medical Record">
                                                <i class="fas fa-file-medical"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                    <i class="fas fa-dove"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">Tweety</div>
                                                <small class="text-muted">ID: PET003</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">Mike Wilson</div>
                                            <small class="text-muted">mike@email.com</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Bird</span><br>
                                        <small class="text-muted">Canary</small>
                                    </td>
                                    <td>
                                        1 year<br>
                                        <small class="text-muted">Female</small>
                                    </td>
                                    <td><span class="badge bg-success">Healthy</span></td>
                                    <td>2024-10-15</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-info" title="Medical Record">
                                                <i class="fas fa-file-medical"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
