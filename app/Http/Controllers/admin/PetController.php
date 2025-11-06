<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index(){
        $pets = Pet::with(['pemilik', 'jenisHewan'])->get();
        return view('admin.pet.index', compact('pets'));
    }

    public function create(){
        $pemiliks = Pemilik::orderBy('nama')->get();
        $jenisHewans = JenisHewan::orderBy('nama_jenis')->get();
        return view('admin.pet.create', compact('pemiliks', 'jenisHewans'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'ras' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'tanggal_lahir' => 'nullable|date',
            'warna' => 'nullable|string|max:50',
            'ciri_khas' => 'nullable|string|max:255',
            'berat' => 'nullable|numeric|min:0',
            'tinggi' => 'nullable|numeric|min:0',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'nama.max' => 'Nama pet maksimal 100 karakter',
            'idjenis_hewan.required' => 'Jenis hewan harus dipilih',
            'idjenis_hewan.exists' => 'Jenis hewan tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus jantan atau betina',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'berat.numeric' => 'Berat harus berupa angka',
            'berat.min' => 'Berat tidak boleh negatif',
            'tinggi.numeric' => 'Tinggi harus berupa angka',
            'tinggi.min' => 'Tinggi tidak boleh negatif',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak valid',
        ]);

        try {
            DB::beginTransaction();

            Pet::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.pet.index')
                ->with('success', 'Pet berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pet: ' . $e->getMessage());
        }
    }
}
