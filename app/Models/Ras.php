<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ras extends Model
{
    protected $table = 'ras_hewan';
    protected $primaryKey = 'idras_hewan';
    protected $guarded = [];

    public function pet(){
        return $this->hasMany(Pet::class, 'idras_hewan', 'idras_hewan');
    }
}
