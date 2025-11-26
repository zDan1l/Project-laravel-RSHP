<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    protected $table = 'jenis_hewan';
    protected $primaryKey = 'idjenis_hewan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    public function pets(){
        return $this->hasMany(Pet::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}
