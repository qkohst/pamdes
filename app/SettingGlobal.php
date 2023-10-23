<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingGlobal extends Model
{
    protected $table = 'setting_global';
    protected $fillable = [
        'nama',
        'alamat',
        'nomor_hp_wa',
        'ukuran_kertas_nota'
    ];
}
