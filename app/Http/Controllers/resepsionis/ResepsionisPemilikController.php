<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pemilik;
use App\Helpers\StringHelper;
use App\Helpers\ValidationHelper;
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
        return view('resepsionis.pemilik.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
        ], [
            'no_wa.required' => 'No WhatsApp harus diisi',
            'no_wa.max' => 'No WhatsApp maksimal 20 karakter',
            'alamat.required' => 'Alamat harus diisi',
        ]);

        try {
            DB::beginTransaction();
            
            // Sanitize input
            $validated = ValidationHelper::sanitizeInput($validated);
            
            // Tambahkan iduser dari auth
            $validated['iduser'] = auth()->user()->iduser;

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
        return view('resepsionis.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, $id)
    {
        $pemilik = Pemilik::findOrFail($id);

        $validated = $request->validate([
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
        ], [
            'no_wa.required' => 'No WhatsApp harus diisi',
            'no_wa.max' => 'No WhatsApp maksimal 20 karakter',
            'alamat.required' => 'Alamat harus diisi',
        ]);

        try {
            DB::beginTransaction();
            
            // Sanitize input
            $validated = ValidationHelper::sanitizeInput($validated);

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
        $pemilik = Pemilik::with(['user', 'pet.jenisHewan'])->findOrFail($id);
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
