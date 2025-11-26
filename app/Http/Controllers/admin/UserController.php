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
        // Perbaikan: Ubah 'nama' menjadi 'name' jika sesuai dengan konvensi kolom di tabel users
        $validated = $request->validate([
            'name' => 'required|string|max:100', // Asumsi kolom adalah 'name'
            'email' => 'required|email|unique:users,email|max:100', // Perbaikan: Gunakan 'users' bukan 'user' jika tabel default
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();

            $validated['password'] = Hash::make($validated['password']);
            // Perbaikan: Ganti key 'nama' menjadi 'name' di array $validated
            $validated['nama'] = $validated['name']; 
            unset($validated['name']);

            User::create($validated);

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
        
        // Perbaikan: Gunakan Rule::unique() untuk validasi unik yang lebih rapi
        // Perbaikan: Ubah 'nama' menjadi 'name' jika sesuai dengan konvensi kolom
        $validated = $request->validate([
            'name' => 'required|string|max:100', // Asumsi kolom adalah 'name'
            // Perbaikan: Gunakan Rule::unique untuk email, menyesuaikan dengan id saat ini dan nama kolom primary key
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id, $user->getKeyName()),
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            DB::beginTransaction();
            
            // Perbaikan: Pisahkan 'nama' dan 'email' dari $validated untuk update
            $updateData = [
                'nama' => $validated['name'], // Asumsi kolom di DB adalah 'nama'
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
     * Menghapus pengguna tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Gunakan find() daripada findOrFail() sebelum transaksi,
            // atau pastikan findOrFail() di luar try/catch jika ingin Laravel menangani 404
            $user = User::findOrFail($id);
            
            // Cek apakah user sedang login
            // Perbaikan: Gunakan helper Auth::id() atau Auth::user()->getKey()
            if (Auth::id() && $user->getKey() == Auth::id()) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus user yang sedang login');
            }
            
            // Check if user has related data (Asumsi: Anda punya relasi 'pemilik')
            // Harap pastikan relasi 'pemilik' ada di model User.
            if ($user->pemilik()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'User tidak dapat dihapus karena memiliki data pemilik terkait');
            }

            DB::beginTransaction();

            // Hapus relasi user_role terlebih dahulu (Asumsi: Relasi 'userRole' ada)
            // Jika relasi 'userRole' menggunakan Foreign Key dengan ON DELETE CASCADE, 
            // baris ini mungkin tidak diperlukan. Namun, jika ini tabel pivot, ini benar.
            if ($user->userRole()->exists()) {
                $user->userRole()->delete();
            }
            
            $user->delete();

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            // Perbaikan: Tangkap ModelNotFoundException secara terpisah jika ingin error 404 yang standar
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return redirect()
                    ->back()
                    ->with('error', 'User tidak ditemukan');
            }
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}