<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResepsionisPemilikController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik', 'desc')->get();
        return view('resepsionis.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        $users = User::doesntHave('pemilik')->get();
        return view('resepsionis.pemilik.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser|unique:pemilik,iduser',
            'no_wa' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ], [
            'iduser.required' => 'User harus dipilih',
            'iduser.exists' => 'User tidak valid',
            'iduser.unique' => 'User sudah terdaftar sebagai pemilik',
            'no_wa.required' => 'No WhatsApp harus diisi',
            'no_wa.max' => 'No WhatsApp maksimal 15 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 255 karakter',
        ]);

        try {
            DB::beginTransaction();

            // Generate idpemilik (ambil max id + 1)
            $lastId = Pemilik::max('idpemilik');
            $validated['idpemilik'] = $lastId ? $lastId + 1 : 1;

            Pemilik::create($validated);

            DB::commit();

            return redirect()
                ->route('resepsionis.pemilik.index')
                ->with('success', 'Data pemilik berhasil ditambahkan');
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
        $users = User::doesntHave('pemilik')
            ->orWhere('iduser', $pemilik->iduser)
            ->get();
        return view('resepsionis.pemilik.edit', compact('pemilik', 'users'));
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);

        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser|unique:pemilik,iduser,' . $id . ',idpemilik',
            'no_wa' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ], [
            'iduser.required' => 'User harus dipilih',
            'iduser.exists' => 'User tidak valid',
            'iduser.unique' => 'User sudah terdaftar sebagai pemilik',
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

            $pemilik->delete();

            return redirect()
                ->route('resepsionis.pemilik.index')
                ->with('success', 'Data pemilik berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data pemilik: ' . $e->getMessage());
        }
    }
}
