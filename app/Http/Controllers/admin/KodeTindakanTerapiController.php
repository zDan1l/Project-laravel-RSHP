<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KodeTindakanTerapiController extends Controller
{
    public function index()
    {
        $items = KodeTindakanTerapi::all();
        return view('admin.kodentindakan.index', compact('items'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.kodentindakan.create', compact('kategori', 'kategoriKlinis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ], [
            'kode.required' => 'Kode tindakan harus diisi',
            'kode.max' => 'Kode tindakan maksimal 50 karakter',
            'kode.unique' => 'Kode tindakan sudah digunakan',
            'deskripsi_tindakan_terapi.required' => 'Deskripsi tindakan harus diisi',
            'idkategori.required' => 'Kategori harus dipilih',
            'idkategori.exists' => 'Kategori tidak valid',
            'idkategori_klinis.required' => 'Kategori klinis harus dipilih',
            'idkategori_klinis.exists' => 'Kategori klinis tidak valid',
        ]);

        try {
            DB::beginTransaction();

            KodeTindakanTerapi::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.kodentindakan.index')
                ->with('success', 'Kode tindakan terapi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kode tindakan terapi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->findOrFail($id);
        $kategori = Kategori::all();
        $kategoriKlinis = KategoriKlinis::all();
        return view('admin.kodentindakan.edit', compact('item', 'kategori', 'kategoriKlinis'));
    }

    public function update(Request $request, $id)
    {
        $item = KodeTindakanTerapi::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:kode_tindakan_terapi,kode,' . $id . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required|string',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ], [
            'kode.required' => 'Kode tindakan harus diisi',
            'kode.max' => 'Kode tindakan maksimal 50 karakter',
            'kode.unique' => 'Kode tindakan sudah digunakan',
            'deskripsi_tindakan_terapi.required' => 'Deskripsi tindakan harus diisi',
            'idkategori.required' => 'Kategori harus dipilih',
            'idkategori.exists' => 'Kategori tidak valid',
            'idkategori_klinis.required' => 'Kategori klinis harus dipilih',
            'idkategori_klinis.exists' => 'Kategori klinis tidak valid',
        ]);

        try {
            DB::beginTransaction();

            $item->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.kodentindakan.index')
                ->with('success', 'Kode tindakan terapi berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate kode tindakan terapi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $item = KodeTindakanTerapi::findOrFail($id);
            
            // Check if being used in medical records
            if ($item->detailRekamMedis()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Kode tindakan tidak dapat dihapus karena sedang digunakan dalam rekam medis');
            }

            $item->delete();

            return redirect()
                ->route('admin.kodentindakan.index')
                ->with('success', 'Kode tindakan terapi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus kode tindakan terapi: ' . $e->getMessage());
        }
    }
}
