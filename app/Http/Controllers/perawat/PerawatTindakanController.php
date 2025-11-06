<?php

namespace App\Http\Controllers\perawat;

use App\Http\Controllers\Controller;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerawatTindakanController extends Controller
{
    /**
     * Display a listing of tindakan terapi
     */
    public function index(Request $request)
    {
        $query = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis']);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('deskripsi_tindakan_terapi', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('idkategori', $request->kategori);
        }

        // Filter by kategori klinis
        if ($request->filled('kategori_klinis')) {
            $query->where('idkategori_klinis', $request->kategori_klinis);
        }

        $tindakanTerapi = $query->orderBy('kode')->paginate(10);
        
        // Get data for filters
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $kategoriKlinisList = KategoriKlinis::orderBy('nama_kategori_klinis')->get();

        return view('perawat.tindakan-terapi.index', compact('tindakanTerapi', 'kategoriList', 'kategoriKlinisList'));
    }

    /**
     * Show the form for creating a new tindakan terapi
     */
    public function create()
    {
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $kategoriKlinisList = KategoriKlinis::orderBy('nama_kategori_klinis')->get();

        return view('perawat.tindakan-terapi.create', compact('kategoriList', 'kategoriKlinisList'));
    }

    /**
     * Store a newly created tindakan terapi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ], [
            'kode.required' => 'Kode tindakan harus diisi',
            'kode.max' => 'Kode tindakan maksimal 5 karakter',
            'kode.unique' => 'Kode tindakan sudah digunakan',
            'deskripsi_tindakan_terapi.required' => 'Deskripsi tindakan harus diisi',
            'deskripsi_tindakan_terapi.max' => 'Deskripsi tindakan maksimal 1000 karakter',
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
                ->route('perawat.tindakan-terapi.index')
                ->with('success', 'Tindakan terapi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan tindakan terapi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified tindakan terapi
     */
    public function show($id)
    {
        $tindakan = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->findOrFail($id);

        return view('perawat.tindakan-terapi.show', compact('tindakan'));
    }

    /**
     * Show the form for editing the specified tindakan terapi
     */
    public function edit($id)
    {
        $tindakan = KodeTindakanTerapi::findOrFail($id);
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $kategoriKlinisList = KategoriKlinis::orderBy('nama_kategori_klinis')->get();

        return view('perawat.tindakan-terapi.edit', compact('tindakan', 'kategoriList', 'kategoriKlinisList'));
    }

    /**
     * Update the specified tindakan terapi
     */
    public function update(Request $request, $id)
    {
        $tindakan = KodeTindakanTerapi::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:5|unique:kode_tindakan_terapi,kode,' . $id . ',idkode_tindakan_terapi',
            'deskripsi_tindakan_terapi' => 'required|string|max:1000',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ], [
            'kode.required' => 'Kode tindakan harus diisi',
            'kode.max' => 'Kode tindakan maksimal 5 karakter',
            'kode.unique' => 'Kode tindakan sudah digunakan',
            'deskripsi_tindakan_terapi.required' => 'Deskripsi tindakan harus diisi',
            'deskripsi_tindakan_terapi.max' => 'Deskripsi tindakan maksimal 1000 karakter',
            'idkategori.required' => 'Kategori harus dipilih',
            'idkategori.exists' => 'Kategori tidak valid',
            'idkategori_klinis.required' => 'Kategori klinis harus dipilih',
            'idkategori_klinis.exists' => 'Kategori klinis tidak valid',
        ]);

        try {
            DB::beginTransaction();

            $tindakan->update($validated);

            DB::commit();

            return redirect()
                ->route('perawat.tindakan-terapi.index')
                ->with('success', 'Tindakan terapi berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui tindakan terapi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified tindakan terapi
     */
    public function destroy($id)
    {
        try {
            $tindakan = KodeTindakanTerapi::findOrFail($id);
            
            // Check if tindakan is being used in detail_rekam_medis
            if ($tindakan->detailRekamMedis()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Tindakan terapi tidak dapat dihapus karena sudah digunakan dalam rekam medis');
            }

            $tindakan->delete();

            return redirect()
                ->route('perawat.tindakan-terapi.index')
                ->with('success', 'Tindakan terapi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus tindakan terapi: ' . $e->getMessage());
        }
    }
}
