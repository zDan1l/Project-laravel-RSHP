<?php

namespace App\Http\Controllers\dokter;

use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use App\Models\DetailRekamMedis;
use App\Models\KodeTindakanTerapi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokterRekamMedisController extends Controller
{
    /**
     * Display antrian pasien untuk dokter yang login
     */
    public function antrian(Request $request)
    {
        $idRoleUser = session('idrole_user');
        
        $query = TemuDokter::with(['pet.pemilik.user', 'pet.ras.jenisHewan'])
            ->whereIn('status', ['A', 'P', 'W']); // A = Antri, P = Proses, W = Waiting

        // Filter by idrole_user jika ada
        if ($idRoleUser) {
            $query->where('idrole_user', $idRoleUser);
        }

        // Filter by date (opsional)
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_daftar', $request->tanggal);
        }

        $antrian = $query->orderBy('waktu_daftar', 'desc')->get();

        return view('dokter.antrian.index', compact('antrian'));
    }

    /**
     * Mulai pemeriksaan - ubah status menjadi P (Proses)
     */
    public function mulaiPemeriksaan($id)
    {
        try {
            $temuDokter = TemuDokter::findOrFail($id);

            $temuDokter->update(['status' => 'P']); // P = Proses

            return redirect()->route('dokter.rekam-medis.create', $id)
                ->with('success', 'Pemeriksaan dimulai. Silakan isi rekam medis.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulai pemeriksaan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of rekam medis
     */
    public function index(Request $request)
    {
        $idUser = session('user_id') ?? Auth::id();

        $query = RekamMedis::with([
            'temuDokter.pet.pemilik.user',
            'temuDokter.pet.ras.jenisHewan',
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi'
        ]);

        // Filter by dokter jika user_id ada
        if ($idUser) {
            $query->where('dokter_pemeriksa', $idUser);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('temuDokter.pet', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('temuDokter.pet.pemilik.user', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('diagnosa', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $rekamMedis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('dokter.rekam-medis.index', compact('rekamMedis'));
    }

    /**
     * Show the form for creating a new rekam medis
     */
    public function create($idTemuDokter)
    {
        $temuDokter = TemuDokter::with(['pet.pemilik.user', 'pet.ras.jenisHewan'])
            ->findOrFail($idTemuDokter);

        // Cek apakah sudah ada rekam medis untuk temu_dokter ini
        $existingRekam = RekamMedis::where('idreservasi_dokter', $idTemuDokter)->first();
        if ($existingRekam) {
            return redirect()->route('dokter.rekam-medis.show', $existingRekam->idrekam_medis)
                ->with('info', 'Rekam medis sudah dibuat untuk pasien ini.');
        }

        $tindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])
            ->orderBy('kode')
            ->get();

        return view('dokter.rekam-medis.create', compact('temuDokter', 'tindakanTerapi'));
    }

    /**
     * Store a newly created rekam medis
     */
    public function store(Request $request)
    {
        $request->validate([
            'idreservasi_dokter' => 'required|exists:temu_dokter,idreservasi_dokter',
            'anamnesa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|array',
            'tindakan.*.idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'tindakan.*.detail' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate ID rekam medis
            $maxId = RekamMedis::max('idrekam_medis') ?? 0;
            $newId = $maxId + 1;

            // Create rekam medis
            $rekamMedis = RekamMedis::create([
                'idrekam_medis' => $newId,
                'idreservasi_dokter' => $request->idreservasi_dokter,
                'created_at' => now(),
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
                'dokter_pemeriksa' => session('user_id') ?? Auth::id(),
            ]);

            // Create detail rekam medis (tindakan)
            if ($request->has('tindakan') && is_array($request->tindakan)) {
                $maxDetailId = DetailRekamMedis::max('iddetail_rekam_medis') ?? 0;
                
                foreach ($request->tindakan as $tindakan) {
                    if (!empty($tindakan['idkode_tindakan_terapi'])) {
                        $maxDetailId++;
                        DetailRekamMedis::create([
                            'iddetail_rekam_medis' => $maxDetailId,
                            'idrekam_medis' => $newId,
                            'idkode_tindakan_terapi' => $tindakan['idkode_tindakan_terapi'],
                            'detail' => $tindakan['detail'] ?? null,
                        ]);
                    }
                }
            }

            // Update status temu_dokter menjadi S (Selesai)
            TemuDokter::where('idreservasi_dokter', $request->idreservasi_dokter)
                ->update(['status' => 'S']);

            DB::commit();
            return redirect()->route('dokter.rekam-medis.show', $newId)
                ->with('success', 'Rekam medis berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified rekam medis
     */
    public function show($id)
    {
        $rekamMedis = RekamMedis::with([
            'temuDokter.pet.pemilik.user', 
            'temuDokter.pet.ras.jenisHewan', 
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi.kategori',
            'detailRekamMedis.kodeTindakanTerapi.kategoriKlinis'
        ])->findOrFail($id);

        return view('dokter.rekam-medis.show', compact('rekamMedis'));
    }

    /**
     * Show the form for editing rekam medis
     */
    public function edit($id)
    {
        $rekamMedis = RekamMedis::with([
            'temuDokter.pet.pemilik.user', 
            'temuDokter.pet.ras.jenisHewan',
            'detailRekamMedis'
        ])->findOrFail($id);

        // Hanya dokter yang membuat yang bisa edit
        $userId = session('user_id') ?? Auth::id();
        if ($rekamMedis->dokter_pemeriksa != $userId) {
            return redirect()->route('dokter.rekam-medis.index')
                ->with('error', 'Anda hanya dapat mengedit rekam medis yang Anda buat.');
        }

        $tindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])
            ->orderBy('kode')
            ->get();

        return view('dokter.rekam-medis.edit', compact('rekamMedis', 'tindakanTerapi'));
    }

    /**
     * Update the specified rekam medis
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'anamnesa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'diagnosa' => 'required|string',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);

        // Hanya dokter yang membuat yang bisa update
        $userId = session('user_id') ?? Auth::id();
        if ($rekamMedis->dokter_pemeriksa != $userId) {
            return redirect()->route('dokter.rekam-medis.index')
                ->with('error', 'Anda hanya dapat mengedit rekam medis yang Anda buat.');
        }

        try {
            $rekamMedis->update([
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
            ]);

            return redirect()->route('dokter.rekam-medis.show', $id)
                ->with('success', 'Rekam medis berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store detail rekam medis (tindakan terapi)
     */
    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);

        // Hanya dokter yang membuat yang bisa tambah detail
        $userId = session('user_id') ?? Auth::id();
        if ($rekamMedis->dokter_pemeriksa != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        try {
            $maxDetailId = DetailRekamMedis::max('iddetail_rekam_medis') ?? 0;
            
            DetailRekamMedis::create([
                'iddetail_rekam_medis' => $maxDetailId + 1,
                'idrekam_medis' => $id,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

            return redirect()->back()->with('success', 'Tindakan terapi berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Update detail rekam medis
     */
    public function updateDetail(Request $request, $id, $idDetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);

        $userId = session('user_id') ?? Auth::id();
        if ($rekamMedis->dokter_pemeriksa != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        try {
            DetailRekamMedis::where('iddetail_rekam_medis', $idDetail)
                ->update([
                    'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                    'detail' => $request->detail,
                ]);

            return redirect()->back()->with('success', 'Tindakan terapi berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Delete detail rekam medis
     */
    public function deleteDetail($id, $idDetail)
    {
        $rekamMedis = RekamMedis::findOrFail($id);

        $userId = session('user_id') ?? Auth::id();
        if ($rekamMedis->dokter_pemeriksa != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        try {
            DetailRekamMedis::where('iddetail_rekam_medis', $idDetail)->delete();
            return redirect()->back()->with('success', 'Tindakan terapi berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus tindakan: ' . $e->getMessage());
        }
    }
}
