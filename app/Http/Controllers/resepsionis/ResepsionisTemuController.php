<?php

namespace App\Http\Controllers\resepsionis;

use App\Http\Controllers\Controller;
use App\Models\TemuDokter;
use App\Models\Pet;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepsionisTemuController extends Controller
{
    /**
     * Display a listing of temu dokter.
     */
    public function index()
    {
        // Ambil data temu dokter hari ini dengan relasi
        $temuDokter = TemuDokter::with(['pet.pemilik', 'pet.jenisHewan', 'roleUser.user', 'roleUser.role'])
            ->whereDate('waktu_daftar', today())
            ->orderBy('no_urut', 'asc')
            ->get();

        // Statistik
        $stats = [
            'hari_ini' => TemuDokter::whereDate('waktu_daftar', today())->count(),
            'menunggu' => TemuDokter::where('status', 'A')->whereDate('waktu_daftar', today())->count(),
            'selesai' => TemuDokter::where('status', 'S')->whereDate('waktu_daftar', today())->count(),
            'dibatalkan' => TemuDokter::where('status', 'C')->whereDate('waktu_daftar', today())->count(),
        ];

        return view('resepsionis.temu-dokter.index', compact('temuDokter', 'stats'));
    }

    /**
     * Show the form for creating a new temu dokter.
     */
    public function create()
    {
        // Ambil data pet dan dokter untuk form
        $pets = Pet::with(['pemilik', 'jenisHewan'])->get();
        
        // Ambil dokter (user dengan role dokter)
        $dokters = UserRole::with(['user', 'role'])
            ->whereHas('role', function($query) {
                $query->where('nama_role', 'dokter');
            })
            ->where('status', 1)
            ->get();

        return view('resepsionis.temu-dokter.create', compact('pets', 'dokters'));
    }

    /**
     * Store a newly created temu dokter in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'idrole_user' => 'required|exists:user_role,idrole_user',
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor urut otomatis
            $lastUrut = TemuDokter::whereDate('waktu_daftar', today())
                ->max('no_urut');
            
            $noUrut = $lastUrut ? $lastUrut + 1 : 1;

            // Simpan temu dokter
            $temuDokter = TemuDokter::create([
                'no_urut' => $noUrut,
                'waktu_daftar' => now(),
                'status' => 'A', // A = Antri/Menunggu
                'idpet' => $validated['idpet'],
                'idrole_user' => $validated['idrole_user'],
            ]);

            DB::commit();

            return redirect()->route('resepsionis.temu-dokter.index')
                ->with('success', 'Pendaftaran temu dokter berhasil! Nomor antrian: ' . $noUrut);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mendaftar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified temu dokter.
     */
    public function show($id)
    {
        $temuDokter = TemuDokter::with(['pet.pemilik', 'pet.jenisHewan', 'roleUser.user', 'roleUser.role'])
            ->findOrFail($id);

        return view('resepsionis.temu-dokter.show', compact('temuDokter'));
    }

    /**
     * Cancel the temu dokter.
     */
    public function cancel($id)
    {
        try {
            $temuDokter = TemuDokter::findOrFail($id);
            $temuDokter->update(['status' => 'C']); // C = Cancelled

            return redirect()->route('resepsionis.temu-dokter.index')
                ->with('success', 'Appointment berhasil dibatalkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    /**
     * Check-in pasien (ubah status ke proses)
     */
    public function checkin($id)
    {
        try {
            $temuDokter = TemuDokter::findOrFail($id);
            $temuDokter->update(['status' => 'P']); // P = Proses/Konsultasi

            return redirect()->route('resepsionis.temu-dokter.index')
                ->with('success', 'Pasien berhasil check-in!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal check-in: ' . $e->getMessage());
        }
    }
}
