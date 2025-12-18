<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    /**
     * Display a listing of user roles from pivot table.
     */
    public function index()
    {
        // Ambil users dengan role aktif saja untuk tampilan lebih clean
        $users = User::with(['userRole' => function($query) {
            $query->where('status', 1)->with('role');
        }])->get();
        
        return view('admin.userrole.index', compact('users'));
    }

    /**
     * Show form to assign role to user
     */
    public function create()
    {
        // Get users without active role or all users
        $users = User::all();
        $roles = Role::all();
        
        return view('admin.userrole.create', compact('users', 'roles'));
    }

    /**
     * Store new role assignment (only 1 active role per user)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser',
            'idrole' => 'required|exists:role,idrole',
        ], [
            'iduser.required' => 'User harus dipilih',
            'iduser.exists' => 'User tidak valid',
            'idrole.required' => 'Role harus dipilih',
            'idrole.exists' => 'Role tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Deactivate all existing roles for this user
            UserRole::where('iduser', $validated['iduser'])
                    ->update(['status' => 0]);

            // Check if this role already exists for the user
            $existingRole = UserRole::where('iduser', $validated['iduser'])
                                   ->where('idrole', $validated['idrole'])
                                   ->first();

            if ($existingRole) {
                // Just activate the existing role using where clause
                UserRole::where('iduser', $validated['iduser'])
                       ->where('idrole', $validated['idrole'])
                       ->update(['status' => 1]);
            } else {
                // Create new role assignment as active
                UserRole::create([
                    'iduser' => $validated['iduser'],
                    'idrole' => $validated['idrole'],
                    'status' => 1, // Active
                ]);
            }

            DB::commit();

            $user = User::find($validated['iduser']);
            $role = Role::find($validated['idrole']);

            return redirect()
                ->route('admin.user-role.index')
                ->with('success', "Role '{$role->nama_role}' berhasil di-assign ke user {$user->nama}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan role: ' . $e->getMessage());
        }
    }

    /**
     * Show form to change/switch user role
     */
    public function edit($iduser)
    {
        $user = User::with('userRole.role')->findOrFail($iduser);
        $roles = Role::all();
        
        return view('admin.userrole.edit', compact('user', 'roles'));
    }

    /**
     * Update/ganti role user (ubah role aktif ke role lain)
     */
    public function update(Request $request, $iduser)
    {
        $validated = $request->validate([
            'idrole' => 'required|exists:role,idrole',
        ], [
            'idrole.required' => 'Role baru harus dipilih',
            'idrole.exists' => 'Role tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Deactivate all roles for this user
            UserRole::where('iduser', $iduser)
                    ->update(['status' => 0]);

            // Check if this role already exists for the user
            $existingRole = UserRole::where('iduser', $iduser)
                                   ->where('idrole', $validated['idrole'])
                                   ->first();

            if ($existingRole) {
                // Just activate the existing role using where clause
                UserRole::where('iduser', $iduser)
                       ->where('idrole', $validated['idrole'])
                       ->update(['status' => 1]);
            } else {
                // Create new role assignment as active
                UserRole::create([
                    'iduser' => $iduser,
                    'idrole' => $validated['idrole'],
                    'status' => 1,
                ]);
            }

            DB::commit();

            $user = User::find($iduser);
            $role = Role::find($validated['idrole']);

            return redirect()
                ->route('admin.user-role.index')
                ->with('success', "Role user {$user->nama} berhasil diganti menjadi '{$role->nama_role}'");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengganti role: ' . $e->getMessage());
        }
    }

    /**
     * Remove role from user dengan validasi lengkap (Best Practice)
     * 
     * Validasi:
     * 1. UserRole exists
     * 2. Tidak boleh hapus role aktif terakhir
     * 3. Cek foreign key constraint (TemuDokter, dll)
     * 4. Soft delete jika masih digunakan, hard delete jika aman
     */
    public function destroy($iduser, $idrole)
    {
        try {
            DB::beginTransaction();

            // 1. Validasi: UserRole exists
            $userRole = UserRole::where('iduser', $iduser)
                               ->where('idrole', $idrole)
                               ->first();

            if (!$userRole) {
                return redirect()
                    ->back()
                    ->with('error', 'Role tidak ditemukan untuk user ini');
            }

            $user = User::find($iduser);
            $role = Role::find($idrole);

            // 2. Info: Check jika ini role aktif terakhir (tapi tetap allow delete)
            $isLastActiveRole = false;
            if ($userRole->status == 1) {
                $totalActiveRoles = UserRole::where('iduser', $iduser)
                                           ->where('status', 1)
                                           ->count();
                
                $isLastActiveRole = ($totalActiveRoles <= 1);
                // CATATAN: Tidak lagi block delete role terakhir
                // Ini memungkinkan admin untuk menghapus semua role sebelum delete user
            }

            // 3. Validasi: Cek foreign key constraint
            if ($userRole->isUsedByOtherEntities()) {
                $usageDetails = $userRole->getUsageDetails();
                $message = "Tidak dapat menghapus role '{$role->nama_role}' dari user {$user->nama}. ";
                $message .= "Role ini masih digunakan oleh: " . implode(', ', $usageDetails) . ". ";
                $message .= "Silakan hapus data terkait terlebih dahulu atau nonaktifkan role ini.";
                
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with('error', $message);
            }

            // 4. Safe to delete - Hard delete karena tidak ada dependency
            $userRole->delete();

            DB::commit();

            $message = "Role '{$role->nama_role}' berhasil dihapus dari user {$user->nama}";
            if ($isLastActiveRole) {
                $message .= ". User sekarang tidak memiliki role aktif dan dapat dihapus jika diperlukan.";
            }

            return redirect()
                ->route('admin.user-role.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk debugging
            \Log::error('UserRole Delete Error: ' . $e->getMessage(), [
                'iduser' => $iduser,
                'idrole' => $idrole,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate role instead of delete (Alternative to destroy)
     * Untuk kasus dimana role masih digunakan tapi ingin dinonaktifkan
     */
    public function deactivate($iduser, $idrole)
    {
        try {
            DB::beginTransaction();

            $userRole = UserRole::where('iduser', $iduser)
                               ->where('idrole', $idrole)
                               ->first();

            if (!$userRole) {
                return redirect()
                    ->back()
                    ->with('error', 'Role tidak ditemukan untuk user ini');
            }

            // Cek jika ini satu-satunya role aktif
            $totalActiveRoles = UserRole::where('iduser', $iduser)
                                       ->where('status', 1)
                                       ->count();
            
            if ($totalActiveRoles <= 1 && $userRole->status == 1) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menonaktifkan role aktif terakhir.');
            }

            $user = User::find($iduser);
            $role = Role::find($idrole);

            // Soft delete - set status to 0
            UserRole::where('iduser', $iduser)
                   ->where('idrole', $idrole)
                   ->update(['status' => 0]);

            DB::commit();

            return redirect()
                ->route('admin.user-role.index')
                ->with('success', "Role '{$role->nama_role}' berhasil dinonaktifkan untuk user {$user->nama}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menonaktifkan role: ' . $e->getMessage());
        }
    }

    /**
     * Delete ALL roles from a user
     * Digunakan untuk "membersihkan" user sebelum dihapus
     * 
     * WARNING: Ini akan menghapus SEMUA role dari user
     */
    public function deleteAllRoles($iduser)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($iduser);
            $userRoles = UserRole::where('iduser', $iduser)->get();

            if ($userRoles->isEmpty()) {
                return redirect()
                    ->back()
                    ->with('info', "User {$user->nama} tidak memiliki role.");
            }

            // Cek apakah ada role yang masih digunakan
            foreach ($userRoles as $userRole) {
                if ($userRole->isUsedByOtherEntities()) {
                    $role = Role::find($userRole->idrole);
                    $usageDetails = $userRole->getUsageDetails();
                    $message = "Tidak dapat menghapus semua role. ";
                    $message .= "Role '{$role->nama_role}' masih digunakan oleh: " . implode(', ', $usageDetails) . ". ";
                    $message .= "Silakan hapus data terkait terlebih dahulu.";
                    
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with('error', $message);
                }
            }

            // Safe to delete all roles
            $roleCount = $userRoles->count();
            UserRole::where('iduser', $iduser)->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', "Semua role ({$roleCount} role) berhasil dihapus dari user {$user->nama}. User sekarang dapat dihapus.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Delete All Roles Error: ' . $e->getMessage(), [
                'iduser' => $iduser,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus semua role: ' . $e->getMessage());
        }
    }
}
