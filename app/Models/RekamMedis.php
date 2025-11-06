<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'anamnesa',
        'temuan_klinis',
        'diagnosa',
        'idreservsi_dokter', // Note: typo in schema, using as-is
        'dokter_pemeriksa',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationship to TemuDokter (via idreservsi_dokter)
    public function temuDokter()
    {
        return $this->belongsTo(TemuDokter::class, 'idreservsi_dokter', 'idreservasi_dokter');
    }

    // Relationship to DetailRekamMedis
    public function detailRekamMedis()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }

    // Relationship to dokter pemeriksa (User)
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_pemeriksa', 'iduser');
    }
}