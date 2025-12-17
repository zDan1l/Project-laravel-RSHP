@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Assign Role ke User</h4>
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
                        <strong>Catatan:</strong> Setiap user hanya boleh memiliki <strong>1 role aktif</strong>. 
                        Ketika Anda assign role baru, role lama akan otomatis dinonaktifkan.
                    </div>

                    <form action="{{ route('admin.user-role.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="iduser" class="form-label">Pilih User <span class="text-danger">*</span></label>
                            <select class="form-select @error('iduser') is-invalid @enderror" 
                                    id="iduser" name="iduser" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                    @php
                                        $activeRole = $user->userRole->where('status', 1)->first();
                                        $currentRole = $activeRole ? ' - Current: ' . $activeRole->role->nama_role : ' - No Active Role';
                                    @endphp
                                    <option value="{{ $user->iduser }}" {{ old('iduser', request('user')) == $user->iduser ? 'selected' : '' }}>
                                        {{ $user->nama }} ({{ $user->email }}){{ $currentRole }}
                                    </option>
                                @endforeach
                            </select>
                            @error('iduser')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih user yang akan di-assign role</small>
                        </div>

                        <div class="mb-4">
                            <label for="idrole" class="form-label">Pilih Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('idrole') is-invalid @enderror" 
                                    id="idrole" name="idrole" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->idrole }}" {{ old('idrole') == $role->idrole ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idrole')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Role yang dipilih akan menjadi role aktif untuk user tersebut</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Assign Role
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
