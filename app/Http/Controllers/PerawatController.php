<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TemuDokter;
use App\Models\RekamMedis;
use App\Models\DetailRekamMedis;
use App\Models\KodeTindakanTerapi;

class PerawatController extends Controller
{
    /**
     * Dashboard perawat
     */
    public function dashboard()
    {
        // Statistik untuk dashboard
        $totalPasienHariIni = TemuDokter::whereDate('waktu_daftar', today())
            ->whereIn('status', ['A', 'P', 'S'])
            ->count();
        
        $pasienMenunggu = TemuDokter::whereIn('status', ['W', 'A'])
            ->count();
        
        $pasienDiproses = TemuDokter::where('status', 'P')
            ->count();
        
        $pasienSelesai = TemuDokter::whereDate('waktu_daftar', today())
            ->where('status', 'S')
            ->count();

        return view('perawat.dashboard', compact(
            'totalPasienHariIni',
            'pasienMenunggu',
            'pasienDiproses',
            'pasienSelesai'
        ));
    }

    /**
     * Display a listing of rekam medis
     */
    public function index(Request $request)
    {
        $query = RekamMedis::with([
            'temuDokter.pet.pemilik.user',
            'temuDokter.pet.ras.jenisHewan',
            'dokter'
        ]);

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

        return view('perawat.rekam-medis.index', compact('rekamMedis'));
    }

    /**
     * Daftar pasien yang perlu didampingi
     */
    public function daftarPasien()
    {
        $pasienList = TemuDokter::with([
            'pet.ras.jenisHewan',
            'pet.pemilik.user',
            'dokter'
        ])
        ->whereIn('status', ['W', 'A', 'P', 'S'])
        ->orderByRaw("FIELD(status, 'P', 'A', 'W', 'S')")
        ->orderBy('waktu_daftar', 'asc')
        ->paginate(15);

        return view('perawat.daftar-pasien.index', compact('pasienList'));
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
            return redirect()->route('perawat.rekam-medis.show', $existingRekam->idrekam_medis)
                ->with('info', 'Rekam medis sudah dibuat untuk pasien ini.');
        }

        return view('perawat.rekam-medis.create', compact('temuDokter'));
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
        ], [
            'idreservasi_dokter.required' => 'Data temu dokter tidak valid',
            'idreservasi_dokter.exists' => 'Data temu dokter tidak ditemukan',
            'anamnesa.required' => 'Anamnesa harus diisi',
            'temuan_klinis.required' => 'Temuan klinis harus diisi',
            'diagnosa.required' => 'Diagnosa harus diisi',
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
                'dokter_pemeriksa' => session('user_id') ?? Auth::id(), // Perawat yang input
            ]);

            // Update status temu_dokter menjadi P (Proses)
            TemuDokter::where('idreservasi_dokter', $request->idreservasi_dokter)
                ->update(['status' => 'P']);

            DB::commit();
            return redirect()->route('perawat.rekam-medis.show', $newId)
                ->with('success', 'Rekam medis berhasil disimpan! Menunggu dokter untuk menambahkan tindakan terapi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified rekam medis with details
     */
    public function show($id)
    {
        $rekamMedis = RekamMedis::with([
            'temuDokter.pet.ras.jenisHewan',
            'temuDokter.pet.pemilik.user',
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi.kategori',
            'detailRekamMedis.kodeTindakanTerapi.kategoriKlinis'
        ])->findOrFail($id);

        $kodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])
            ->orderBy('kode')
            ->get();

        return view('perawat.rekam-medis.show', compact('rekamMedis', 'kodeTindakanTerapi'));
    }

    /**
     * Store detail tindakan terapi (untuk perawat)
     * Perawat hanya bisa menambah tindakan terapi dasar
     */
    public function storeDetail(Request $request, $idRekamMedis)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string|max:1000',
        ], [
            'idkode_tindakan_terapi.required' => 'Tindakan terapi harus dipilih',
            'idkode_tindakan_terapi.exists' => 'Tindakan terapi tidak valid',
            'detail.max' => 'Detail rincian maksimal 1000 karakter',
        ]);

        try {
            // Generate ID untuk detail rekam medis
            $maxDetailId = DetailRekamMedis::max('iddetail_rekam_medis') ?? 0;

            DetailRekamMedis::create([
                'iddetail_rekam_medis' => $maxDetailId + 1,
                'idrekam_medis' => $idRekamMedis,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
                'petugas_input' => session('user_id') ?? Auth::id(),
                'tipe_petugas' => 'perawat'
            ]);

            return redirect()->route('perawat.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Tindakan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Update detail tindakan terapi
     * Perawat hanya bisa edit tindakan yang dia input sendiri
     */
    public function updateDetail(Request $request, $idRekamMedis, $idDetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string|max:1000',
        ], [
            'idkode_tindakan_terapi.required' => 'Tindakan terapi harus dipilih',
            'idkode_tindakan_terapi.exists' => 'Tindakan terapi tidak valid',
            'detail.max' => 'Detail rincian maksimal 1000 karakter',
        ]);

        try {
            $detail = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->where('iddetail_rekam_medis', $idDetail)
                ->firstOrFail();

            // Perawat hanya bisa edit tindakan yang dia input sendiri
            $currentUserId = session('user_id') ?? Auth::id();
            if ($detail->petugas_input && $detail->petugas_input != $currentUserId) {
                return redirect()->route('perawat.rekam-medis.show', $idRekamMedis)
                    ->with('error', 'Anda hanya bisa mengedit tindakan yang Anda input sendiri');
            }

            $detail->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail
            ]);

            return redirect()->route('perawat.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Tindakan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Delete detail tindakan terapi
     * Perawat hanya bisa hapus tindakan yang dia input sendiri
     */
    public function deleteDetail($idRekamMedis, $idDetail)
    {
        try {
            $detail = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->where('iddetail_rekam_medis', $idDetail)
                ->firstOrFail();

            // Perawat hanya bisa hapus tindakan yang dia input sendiri
            $currentUserId = session('user_id') ?? Auth::id();
            if ($detail->petugas_input && $detail->petugas_input != $currentUserId) {
                return redirect()->route('perawat.rekam-medis.show', $idRekamMedis)
                    ->with('error', 'Anda hanya bisa menghapus tindakan yang Anda input sendiri');
            }

            $detail->delete();

            return redirect()->route('perawat.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Tindakan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus tindakan: ' . $e->getMessage());
        }
    }
}
