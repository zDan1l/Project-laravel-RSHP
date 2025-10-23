<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pet';
    protected $primaryKey = 'idpet';
    protected $guarded = [];

    public function pemilik(){
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    public function ras(){
        return $this->belongsTo(Ras::class, 'idras_hewan', 'idras_hewan');
    }
}
