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
        // Ambil users dengan roles dan data pivot
        $users = User::with('userRole.role')->get();
        
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

            // Set all existing roles for this user to inactive (status = 0)
            UserRole::where('iduser', $validated['iduser'])
                    ->update(['status' => 0]);

            // Check if this role already exists for the user
            $existingRole = UserRole::where('iduser', $validated['iduser'])
                                   ->where('idrole', $validated['idrole'])
                                   ->first();

            if ($existingRole) {
                // Just activate the existing role
                $existingRole->update(['status' => 1]);
            } else {
                // Create new role assignment as active
                UserRole::create([
                    'iduser' => $validated['iduser'],
                    'idrole' => $validated['idrole'],
                    'status' => 1, // Active
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.user-role.index')
                ->with('success', 'Role berhasil ditambahkan dan diaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan role: ' . $e->getMessage());
        }
    }

    /**
     * Activate a specific role (deactivate others)
     */
    public function activate($iduser, $idrole)
    {
        try {
            DB::beginTransaction();

            // Deactivate all roles for this user
            UserRole::where('iduser', $iduser)
                    ->update(['status' => 0]);

            // Activate selected role
            $userRole = UserRole::where('iduser', $iduser)
                               ->where('idrole', $idrole)
                               ->first();

            if (!$userRole) {
                throw new \Exception('Role tidak ditemukan untuk user ini');
            }

            $userRole->update(['status' => 1]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Role berhasil diaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal mengaktifkan role: ' . $e->getMessage());
        }
    }

    /**
     * Remove role from user
     */
    public function destroy($iduser, $idrole)
    {
        try {
            $userRole = UserRole::where('iduser', $iduser)
                               ->where('idrole', $idrole)
                               ->first();
            // dd($userRole);
            if (!$userRole) {
                throw new \Exception('Role tidak ditemukan');
            }

            // Check if this is the only active role
            $activeRolesCount = UserRole::where('iduser', $iduser)
                                       ->where('status', 1)
                                       ->count();
            // dd($activeRolesCount);

            if ($activeRolesCount == 1 && $userRole->status == 1) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus role aktif terakhir. User harus memiliki minimal 1 role aktif.');
            }

            $userRole->delete();

            return redirect()
                ->back()
                ->with('success', 'Role berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}
