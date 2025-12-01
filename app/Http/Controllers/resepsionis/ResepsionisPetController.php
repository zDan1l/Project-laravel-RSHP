<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\Ras;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResepsionisPetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['pemilik.user', 'ras.jenisHewan'])->orderBy('idpet', 'desc')->get();
        return view('resepsionis.pet.index', compact('pets'));
    }

    public function create()
    {
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik', 'desc')->get();
        $rasHewans = Ras::with('jenisHewan')->orderBy('nama_ras')->get();
        return view('resepsionis.pet.create', compact('pemiliks', 'rasHewans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:50',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'nama.max' => 'Nama pet maksimal 100 karakter',
            'idras_hewan.required' => 'Ras harus dipilih',
            'idras_hewan.exists' => 'Ras tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Generate ID manually since idpet is not auto-increment
            $lastId = Pet::max('idpet');
            $validated['idpet'] = $lastId ? $lastId + 1 : 1;

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
        $pet = Pet::with(['pemilik.user', 'ras'])->findOrFail($id);
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik', 'desc')->get();
        $rasHewans = Ras::with('jenisHewan')->orderBy('nama_ras')->get();
        return view('resepsionis.pet.edit', compact('pet', 'pemiliks', 'rasHewans'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:50',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'nama.max' => 'Nama pet maksimal 100 karakter',
            'idras_hewan.required' => 'Ras harus dipilih',
            'idras_hewan.exists' => 'Ras tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak valid',
        ]);

        try {
            DB::beginTransaction();

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
        $pet = Pet::with(['pemilik.user', 'pemilik.pet.ras.jenisHewan', 'ras.jenisHewan'])->findOrFail($id);
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
