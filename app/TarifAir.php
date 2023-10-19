<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarifAir extends Model
{
    protected $table = 'tarif_air';
    protected $fillable = [
        'tarif_per_meter',
        'biaya_pemeliharaan',
        'biaya_administrasi'
    ];
}
