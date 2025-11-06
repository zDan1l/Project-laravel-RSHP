<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    public function create(){
        return view('admin.role.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role',
        ], [
            'nama_role.required' => 'Nama role harus diisi',
            'nama_role.max' => 'Nama role maksimal 100 karakter',
            'nama_role.unique' => 'Nama role sudah digunakan',
        ]);

        try {
            DB::beginTransaction();

            Role::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role,' . $id . ',idrole',
        ], [
            'nama_role.required' => 'Nama role harus diisi',
            'nama_role.max' => 'Nama role maksimal 100 karakter',
            'nama_role.unique' => 'Nama role sudah digunakan',
        ]);

        try {
            DB::beginTransaction();

            $role->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate role: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Check if role is being used
            if ($role->userRoles()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Role tidak dapat dihapus karena sedang digunakan oleh user');
            }

            $role->delete();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}
