@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Ganti Role User</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Mengganti role akan menonaktifkan role lama dan mengaktifkan role baru yang dipilih. 
                        User hanya boleh memiliki <strong>1 role aktif</strong>.
                    </div>

                    <!-- User Information Card -->
                    <div class="card mb-4 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.5rem;">
                                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $user->nama }}</h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Current Active Role -->
                            <div class="mt-3">
                                <label class="form-label fw-bold">Role Aktif Saat Ini:</label>
                                <div>
                                    @php
                                        $activeRole = $user->userRole->where('status', 1)->first();
                                    @endphp
                                    @if($activeRole)
                                        <span class="badge bg-success p-2 fs-6">
                                            <i class="fas fa-check-circle me-1"></i>
                                            {{ $activeRole->role->nama_role }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark p-2 fs-6">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Tidak ada role aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.user-role.update', $user->iduser) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="idrole" class="form-label">Pilih Role Baru <span class="text-danger">*</span></label>
                            <select class="form-select @error('idrole') is-invalid @enderror" 
                                    id="idrole" name="idrole" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->idrole }}" 
                                            {{ old('idrole', $activeRole?->idrole) == $role->idrole ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                        @if($activeRole && $activeRole->idrole == $role->idrole)
                                            (Role Aktif Saat Ini)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('idrole')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Role yang dipilih akan menjadi role aktif untuk user ini</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-exchange-alt me-1"></i> Ganti Role
                            </button>
                            <a href="{{ route('admin.user-role.index') }}" class="btn btn-secondary">
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
