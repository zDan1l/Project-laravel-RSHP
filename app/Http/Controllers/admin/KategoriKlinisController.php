<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        $items = KategoriKlinis::all();
        return view('admin.kategoriklinis.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kategoriklinis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori_klinis' => 'required|string|max:100',
        ], [
            'nama_kategori_klinis.required' => 'Nama kategori klinis harus diisi',
            'nama_kategori_klinis.max' => 'Nama kategori klinis maksimal 100 karakter',
        ]);

        try {
            DB::beginTransaction();

            KategoriKlinis::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.kategoriklinis.index')
                ->with('success', 'Kategori klinis berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori klinis: ' . $e->getMessage());
        }
    }
}
