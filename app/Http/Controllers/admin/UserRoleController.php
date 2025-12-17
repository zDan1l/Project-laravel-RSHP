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
     * Remove role from user (hanya hapus role, bukan deactivate)
     */
    public function destroy($iduser, $idrole)
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
            if ($userRole->status == 1) {
                $totalActiveRoles = UserRole::where('iduser', $iduser)
                                           ->where('status', 1)
                                           ->count();
                
                if ($totalActiveRoles <= 1) {
                    return redirect()
                        ->back()
                        ->with('error', 'Tidak dapat menghapus role aktif terakhir. User harus memiliki minimal 1 role aktif.');
                }
            }

            $user = User::find($iduser);
            $role = Role::find($idrole);

            // Hapus role dari user
            $userRole->delete();

            DB::commit();

            return redirect()
                ->route('admin.user-role.index')
                ->with('success', "Role '{$role->nama_role}' berhasil dihapus dari user {$user->nama}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}
