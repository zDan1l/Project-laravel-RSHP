<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;

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
     * Show specific user with their roles and pivot data
     */
    public function show($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        
        return view('admin.userrole.show', compact('user'));
    }

    /**
     * Attach role to user dengan data pivot
     */
    public function attachRole(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        
        // Attach role dengan data pivot
        $user->roles()->attach($request->role_id, [
            'status' => $request->status ?? 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Role berhasil ditambahkan!');
    }

    /**
     * Update pivot data
     */
    public function updatePivot(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        
        // Update data pivot
        $user->roles()->updateExistingPivot($request->role_id, [
            'status' => $request->status,
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Status role berhasil diupdate!');
    }

    /**
     * Detach role from user
     */
    public function detachRole(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        
        // Detach specific role
        $user->roles()->detach($request->role_id);
        
        return redirect()->back()->with('success', 'Role berhasil dihapus!');
    }

    /**
     * Sync roles with user (replace all existing roles)
     */
    public function syncRoles(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        
        // Sync roles dengan data pivot
        $roleData = [];
        foreach ($request->roles as $roleId) {
            $roleData[$roleId] = [
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        $user->roles()->sync($roleData);
        
        return redirect()->back()->with('success', 'Roles berhasil disync!');
    }

    /**
     * Query examples untuk berbagai kebutuhan
     */
    public function queryExamples()
    {
        // 1. Ambil semua user yang memiliki role tertentu
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'Admin');
        })->get();

        // 2. Ambil user dengan role dan filter berdasarkan status pivot
        $activeUsers = User::whereHas('roles', function($query) {
            $query->wherePivot('status', 'active');
        })->with(['roles' => function($query) {
            $query->wherePivot('status', 'active');
        }])->get();

        // 3. Count berapa banyak user per role
        $roleCounts = Role::withCount('users')->get();

        // 4. Ambil data pivot dengan kondisi tertentu
        $specificPivotData = UserRole::where('status', 'active')
                                   ->with(['user', 'role'])
                                   ->get();

        // 5. User dengan multiple roles
        $usersWithMultipleRoles = User::has('roles', '>', 1)->get();

        return response()->json([
            'admins' => $admins,
            'activeUsers' => $activeUsers,
            'roleCounts' => $roleCounts,
            'specificPivotData' => $specificPivotData,
            'usersWithMultipleRoles' => $usersWithMultipleRoles
        ]);
    }
}
