<?php

namespace App\Http\Controllers\pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PemilikDashboardController extends Controller
{
    public function index()
    {
        // Set locale untuk Carbon ke Indonesia
        Carbon::setLocale('id');
        
        // Get current user's pemilik data
        $user = Auth::user();
        $pemilik = Pemilik::where('iduser', $user->iduser)->first();
        
        if (!$pemilik) {
            return redirect()->route('login')->with('error', 'Data pemilik tidak ditemukan. Silakan hubungi administrator.');
        }
        
        // Get statistics for this pemilik
        $stats = $this->getStatistics($pemilik->idpemilik);
        
        // Get pemilik's pets with their appointments
        $pets = Pet::with(['ras.jenisHewan', 'temuDokter' => function($query) {
                $query->orderBy('waktu_daftar', 'desc');
            }])
            ->where('idpemilik', $pemilik->idpemilik)
            ->orderBy('idpet', 'desc')
            ->get();
        
        // Get upcoming appointments (status = 'A' (Antri), 'P' (Proses))
        $upcomingAppointments = DB::table('temu_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->leftJoin('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->leftJoin('role_user', 'temu_dokter.idrole_user', '=', 'role_user.idrole_user')
            ->leftJoin('user', 'role_user.iduser', '=', 'user.iduser')
            ->where('pet.idpemilik', $pemilik->idpemilik)
            ->whereIn('temu_dokter.status', ['A', 'P']) // A = Antri, P = Proses
            ->select(
                'temu_dokter.*',
                'pet.nama as nama_pet',
                'ras_hewan.nama_ras',
                'jenis_hewan.nama_jenis_hewan',
                'user.nama as nama_dokter'
            )
            ->orderBy('temu_dokter.waktu_daftar', 'asc')
            ->get();
        
        // Tanggal hari ini dalam bahasa Indonesia
        $tanggalHariIni = Carbon::now()->isoFormat('dddd, D MMMM YYYY');
        
        return view('pemilik.dashboard.index', compact('stats', 'pets', 'pemilik', 'tanggalHariIni', 'upcomingAppointments'));
    }
    
    /**
     * Get dashboard statistics for specific pemilik
     */
    private function getStatistics($idpemilik)
    {
        // Get all pet IDs for this pemilik
        $petIds = Pet::where('idpemilik', $idpemilik)->pluck('idpet');
        
        return [
            // Total pets owned by this pemilik
            'total_pet' => $petIds->count(),
            
            // Total janji temu mendatang (A = Antri, P = Proses)
            'janji_mendatang' => DB::table('temu_dokter')
                ->whereIn('idpet', $petIds)
                ->whereIn('status', ['A', 'P'])
                ->count(),
            
            // Total rekam medis - join through temu_dokter
            'total_rekam_medis' => DB::table('rekam_medis')
                ->join('temu_dokter', 'rekam_medis.idreservasi_dokter', '=', 'temu_dokter.idreservasi_dokter')
                ->whereIn('temu_dokter.idpet', $petIds)
                ->count(),
            
            // Last visit date (S = Selesai)
            'kunjungan_terakhir' => DB::table('temu_dokter')
                ->whereIn('idpet', $petIds)
                ->where('status', 'S')
                ->orderBy('waktu_daftar', 'desc')
                ->value('waktu_daftar'),
        ];
    }
}
