<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    protected $primaryKey = 'idpemilik';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;


    public function user(){
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function pet(){
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }
}
