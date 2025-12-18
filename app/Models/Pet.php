<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pet extends Model
{
    protected $table = 'pet';
    protected $primaryKey = 'idpet';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;

    // Accessor untuk jenis_kelamin - convert J/B ke Jantan/Betina saat display
    protected function jenisKelamin(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === 'J' ? 'Jantan' : ($value === 'B' ? 'Betina' : $value),
        );
    }

    public function pemilik(){
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    public function ras(){
        return $this->belongsTo(Ras::class, 'idras_hewan', 'idras_hewan');
    }

}
