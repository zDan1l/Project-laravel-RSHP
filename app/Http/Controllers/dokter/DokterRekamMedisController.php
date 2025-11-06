<?php

namespace App\Http\Controllers\dokter;

use App\Models\RekamMedis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DokterRekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamMedis::with([
            'temuDokter.pet.pemilik',
            'temuDokter.pet.jenisHewan',
            'dokter'
        ]);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('temuDokter.pet', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhereHas('temuDokter.pet.pemilik', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhere('diagnosa', 'like', "%{$search}%");
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
}
