<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeTindakanTerapi extends Model
{
    protected $table = 'kode_tindakan_terapi';
    protected $primaryKey = 'idkode_tindakan_terapi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }

    public function kategoriKlinis(){
        return $this->belongsTo(KategoriKlinis::class, 'idkategori_klinis', 'idkategori_klinis');
    }

    public function detailRekamMedis(){
        return $this->hasMany(DetailRekamMedis::class, 'idkode_tindakan_terapi', 'idkode_tindakan_terapi');
    }
}
