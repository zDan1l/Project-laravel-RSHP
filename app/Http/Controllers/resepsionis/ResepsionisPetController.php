<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\JenisHewan;
use App\Helpers\StringHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResepsionisPetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['pemilik.user', 'jenisHewan'])->orderBy('idpet', 'desc')->get();
        return view('resepsionis.pet.index', compact('pets'));
    }

    public function create()
    {
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik')->get();
        $jenisHewans = JenisHewan::orderBy('nama_jenis')->get();
        return view('resepsionis.pet.create', compact('pemiliks', 'jenisHewans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pet' => 'required|string|max:100',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna' => 'nullable|string|max:50',
            'ciri_khusus' => 'nullable|string',
        ], [
            'nama_pet.required' => 'Nama pet harus diisi',
            'nama_pet.max' => 'Nama pet maksimal 100 karakter',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak ditemukan',
            'idjenis_hewan.required' => 'Jenis hewan harus dipilih',
            'idjenis_hewan.exists' => 'Jenis hewan tidak ditemukan',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
        ]);

        try {
            DB::beginTransaction();

            // Format nama pet
            $validated['nama_pet'] = StringHelper::formatName($validated['nama_pet']);
            
            // Sanitize input
            $validated = ValidationHelper::sanitizeInput($validated);

            Pet::create($validated);

            DB::commit();

            return redirect()
                ->route('resepsionis.pet.index')
                ->with('success', 'Data pet berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pet: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik')->get();
        $jenisHewans = JenisHewan::orderBy('nama_jenis')->get();
        return view('resepsionis.pet.edit', compact('pet', 'pemiliks', 'jenisHewans'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'nama_pet' => 'required|string|max:100',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna' => 'nullable|string|max:50',
            'ciri_khusus' => 'nullable|string',
        ], [
            'nama_pet.required' => 'Nama pet harus diisi',
            'nama_pet.max' => 'Nama pet maksimal 100 karakter',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak ditemukan',
            'idjenis_hewan.required' => 'Jenis hewan harus dipilih',
            'idjenis_hewan.exists' => 'Jenis hewan tidak ditemukan',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
        ]);

        try {
            DB::beginTransaction();

            // Format nama pet
            $validated['nama_pet'] = StringHelper::formatName($validated['nama_pet']);
            
            // Sanitize input
            $validated = ValidationHelper::sanitizeInput($validated);

            $pet->update($validated);

            DB::commit();

            return redirect()
                ->route('resepsionis.pet.index')
                ->with('success', 'Data pet berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data pet: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pet = Pet::with(['pemilik.user', 'pemilik.pet.jenisHewan', 'jenisHewan'])->findOrFail($id);
        return view('resepsionis.pet.show', compact('pet'));
    }

    public function destroy($id)
    {
        try {
            $pet = Pet::findOrFail($id);
            
            // TODO: Check if pet has related records (temu dokter, rekam medis, etc)
            
            $pet->delete();

            return redirect()
                ->route('resepsionis.pet.index')
                ->with('success', 'Data pet berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data pet: ' . $e->getMessage());
        }
    }
}
