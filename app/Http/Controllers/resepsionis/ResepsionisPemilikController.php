<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pemilik;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResepsionisPemilikController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik', 'desc')->get();
        return view('resepsionis.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        return view('resepsionis.pemilik.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'no_wa' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_wa.required' => 'No WhatsApp harus diisi',
            'no_wa.max' => 'No WhatsApp maksimal 15 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 255 karakter',
        ]);

        try {
            DB::beginTransaction();

            // 1. Generate iduser (ambil max id + 1)
            $lastUserId = User::max('iduser');
            $newUserId = $lastUserId ? $lastUserId + 1 : 1;

            // 2. Create User
            $user = User::create([
                'iduser' => $newUserId,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 3. Assign Role Pemilik (idrole = 5)
            UserRole::create([
                'iduser' => $user->iduser,
                'idrole' => 5, // Role Pemilik
            ]);

            // 4. Generate idpemilik (ambil max id + 1)
            $lastPemilikId = Pemilik::max('idpemilik');
            $newPemilikId = $lastPemilikId ? $lastPemilikId + 1 : 1;

            // 5. Create Pemilik
            Pemilik::create([
                'idpemilik' => $newPemilikId,
                'iduser' => $user->iduser,
                'no_wa' => $validated['no_wa'],
                'alamat' => $validated['alamat'],
            ]);

            DB::commit();

            return redirect()
                ->route('resepsionis.pemilik.index')
                ->with('success', 'Pemilik berhasil didaftarkan. Email: ' . $user->email);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pemilik: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pemilik = Pemilik::with('user')->findOrFail($id);
        return view('resepsionis.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);

        $validated = $request->validate([
            'no_wa' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ], [
            'no_wa.required' => 'No WhatsApp harus diisi',
            'no_wa.max' => 'No WhatsApp maksimal 15 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 255 karakter',
        ]);

        try {
            DB::beginTransaction();

            $pemilik->update($validated);

            DB::commit();

            return redirect()
                ->route('resepsionis.pemilik.index')
                ->with('success', 'Data pemilik berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data pemilik: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pemilik = Pemilik::with(['user', 'pet.ras.jenisHewan'])->findOrFail($id);
        return view('resepsionis.pemilik.show', compact('pemilik'));
    }

    public function destroy($id)
    {
        try {
            $pemilik = Pemilik::findOrFail($id);
            
            // Check if pemilik has pets
            if ($pemilik->pet()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Data pemilik tidak dapat dihapus karena masih memiliki pet terdaftar');
            }

            DB::beginTransaction();

            $userId = $pemilik->iduser;

            // 1. Delete Pemilik
            $pemilik->delete();

            // 2. Delete UserRole
            UserRole::where('iduser', $userId)->delete();

            // 3. Delete User
            User::where('iduser', $userId)->delete();

            DB::commit();

            return redirect()
                ->route('resepsionis.pemilik.index')
                ->with('success', 'Data pemilik beserta user dan role berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data pemilik: ' . $e->getMessage());
        }
    }
}
