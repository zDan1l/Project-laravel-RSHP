<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    /**
     * Display a listing of dokter.
     */
    public function index()
    {
        // Ambil role dokter
        $roleDokter = Role::where('nama_role', 'dokter')->first();
        
        if (!$roleDokter) {
            return redirect()->back()->with('error', 'Role dokter tidak ditemukan');
        }

        // Ambil semua user yang memiliki role dokter
        $dokters = UserRole::with(['user', 'role'])
            ->where('idrole', $roleDokter->idrole)
            ->get()
            ->map(function($userRole) {
                return $userRole->user;
            });

        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new dokter.
     */
    public function create()
    {
        return view('admin.dokter.create');
    }

    /**
     * Store a newly created dokter in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ], [
            'nama.required' => 'Nama dokter harus diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            // Generate iduser
            $lastUserId = User::max('iduser');
            $newUserId = $lastUserId ? $lastUserId + 1 : 1;

            // Create user
            $user = User::create([
                'iduser' => $newUserId,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Get role dokter
            $roleDokter = Role::where('nama_role', 'dokter')->first();

            if (!$roleDokter) {
                throw new \Exception('Role dokter tidak ditemukan di sistem');
            }

            // Generate idrole_user
            $lastRoleUserId = UserRole::max('idrole_user');
            $newRoleUserId = $lastRoleUserId ? $lastRoleUserId + 1 : 1;

            // Assign role dokter to user
            UserRole::create([
                'idrole_user' => $newRoleUserId,
                'iduser' => $user->iduser,
                'idrole' => $roleDokter->idrole,
                'status' => 1, // Active
            ]);

            DB::commit();

            return redirect()
                ->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan dokter: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified dokter.
     */
    public function edit($id)
    {
        $dokter = User::findOrFail($id);
        
        // Verify user is dokter
        $hasDokterRole = UserRole::where('iduser', $id)
            ->whereHas('role', function($query) {
                $query->where('nama_role', 'dokter');
            })
            ->exists();

        if (!$hasDokterRole) {
            return redirect()->back()->with('error', 'User bukan dokter');
        }

        return view('admin.dokter.edit', compact('dokter'));
    }

    /**
     * Update the specified dokter in storage.
     */
    public function update(Request $request, $id)
    {
        $dokter = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . $id . ',iduser',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama dokter harus diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'nama' => $validated['nama'],
                'email' => $validated['email'],
            ];

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $dokter->update($updateData);

            DB::commit();

            return redirect()
                ->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate dokter: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified dokter from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Verify user is dokter
            $hasDokterRole = UserRole::where('iduser', $id)
                ->whereHas('role', function($query) {
                    $query->where('nama_role', 'dokter');
                })
                ->exists();

            if (!$hasDokterRole) {
                throw new \Exception('User bukan dokter');
            }

            // Delete user roles
            UserRole::where('iduser', $id)->delete();

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()
                ->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus dokter: ' . $e->getMessage());
        }
    }
}
