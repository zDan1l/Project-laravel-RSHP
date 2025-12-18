<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Tambahkan ini untuk validasi unik yang lebih baik
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk penggunaan auth() yang lebih eksplisit

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Gunakan paginate() jika jumlah pengguna banyak
        $users = User::with('userRole')->get();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Menyimpan pengguna baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama harus diisi',
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

            // Generate iduser (ambil max id + 1)
            $lastUserId = User::max('iduser');
            $newUserId = $lastUserId ? $lastUserId + 1 : 1;

            User::create([
                'iduser' => $newUserId,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir untuk mengedit pengguna tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Memperbarui pengguna tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user', 'email')->ignore($id, 'iduser'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama harus diisi',
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

            // Only update password if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pengguna tertentu
     * 
     * Logika:
     * 1. Validasi user exists dan bukan user yang sedang login
     * 2. Hapus semua UserRole (cascade)
     * 3. Hapus User
     * 4. Jika error SQL constraint → berarti user masih dipakai
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // 1. Validasi: User exists
            $user = User::findOrFail($id);
            
            // 2. Validasi: Bukan user yang sedang login
            if (session('user_id') && $user->iduser == session('user_id')) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus user yang sedang login');
            }

            DB::beginTransaction();

            $userName = $user->nama;
            $userEmail = $user->email;
            
            // 3. Cascade delete: Hapus semua UserRole terlebih dahulu
            $userRoleCount = $user->userRole()->count();
            if ($userRoleCount > 0) {
                $user->userRole()->delete();
            }
            
            // 4. Hapus User - jika masih dipakai, SQL akan throw error
            $user->delete();

            DB::commit();

            $message = "User '{$userName}' ({$userEmail}) berhasil dihapus";
            if ($userRoleCount > 0) {
                $message .= " beserta {$userRoleCount} role";
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', $message);
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->with('error', 'User tidak ditemukan');
                
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            // SQL Constraint error - user masih digunakan
            if ($e->getCode() == '23000') {
                return redirect()
                    ->back()
                    ->with('error', "User '{$user->nama}' tidak dapat dihapus karena masih digunakan oleh data lain (pemilik, rekam medis, atau temu dokter). Silakan hapus data terkait terlebih dahulu.");
            }
            
            // Error SQL lainnya
            \Log::error('User Delete SQL Error: ' . $e->getMessage(), [
                'iduser' => $id,
                'code' => $e->getCode()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus user karena kesalahan database');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk debugging
            \Log::error('User Delete Error: ' . $e->getMessage(), [
                'iduser' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}