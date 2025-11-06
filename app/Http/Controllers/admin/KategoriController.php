<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        $items = Kategori::all();
        return view('admin.kategori.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter',
        ]);

        try {
            DB::beginTransaction();

            Kategori::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.kategori.index')
                ->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }
}
