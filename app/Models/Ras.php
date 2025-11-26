<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ras extends Model
{
    protected $table = 'ras_hewan';
    protected $primaryKey = 'idras_hewan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;


    public function pet(){
        return $this->hasMany(Pet::class, 'idras_hewan', 'idras_hewan');
    }
}
