@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Assign/Change User Role</h4>
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
                        <strong>Catatan:</strong> Setiap user hanya bisa memiliki 1 role aktif. Ketika Anda assign role baru, role lama akan dinonaktifkan.
                    </div>

                    <form action="{{ route('admin.user-role.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="iduser" class="form-label">Pilih User <span class="text-danger">*</span></label>
                            <select class="form-select @error('iduser') is-invalid @enderror" 
                                    id="iduser" name="iduser" required onchange="loadUserRoles(this.value)">
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->iduser }}" {{ old('iduser', request('user')) == $user->iduser ? 'selected' : '' }}>
                                        {{ $user->nama }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('iduser')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Display current roles -->
                        <div id="currentRoles" class="mb-3" style="display: none;">
                            <label class="form-label">Current Roles:</label>
                            <div id="rolesContainer" class="d-flex flex-wrap gap-2">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="idrole" class="form-label">Pilih Role Baru <span class="text-danger">*</span></label>
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
                            <small class="text-muted">Role yang dipilih akan menjadi role aktif, role lain akan dinonaktifkan</small>
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

<script>
    // Data roles untuk setiap user (dari server)
    const userRolesData = @json($users->mapWithKeys(function($user) {
        return [
            $user->iduser => $user->userRole->map(function($ur) {
                return [
                    'role_name' => $ur->role->nama_role,
                    'status' => $ur->status
                ];
            })
        ];
    }));

    function loadUserRoles(userId) {
        const currentRolesDiv = document.getElementById('currentRoles');
        const rolesContainer = document.getElementById('rolesContainer');
        
        if (!userId || !userRolesData[userId] || userRolesData[userId].length === 0) {
            currentRolesDiv.style.display = 'none';
            return;
        }

        const roles = userRolesData[userId];
        let rolesHtml = '';
        
        roles.forEach(role => {
            const badgeClass = role.status == 1 ? 'bg-success' : 'bg-secondary';
            const statusText = role.status == 1 ? 'Aktif' : 'Tidak Aktif';
            rolesHtml += `
                <div class="badge ${badgeClass} p-2">
                    ${role.role_name} <small>(${statusText})</small>
                </div>
            `;
        });

        rolesContainer.innerHTML = rolesHtml;
        currentRolesDiv.style.display = 'block';
    }

    // Load roles on page load if user is pre-selected
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('iduser');
        if (userSelect.value) {
            loadUserRoles(userSelect.value);
        }
    });
</script>
@endsection
