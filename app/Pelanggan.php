<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $fillable = [
        'kode',
        'nama_lengkap',
        'nomor_hp_wa',
        'alamat',
        'status',
        'is_delete'
    ];

    public function getStatusPelangganAttribute()
    {
        if ($this->status == 1) {
            $status_str = 'Aktif';
        } elseif ($this->status == 0) {
            $status_str = 'Non Aktif';
        }
        return $status_str;
    }

    static function getKodePelanggan()
    {
        $prefix = 'PAM';
        $counter = 0;
        $digit = 4;
        $last_record = Pelanggan::where('is_delete', false)->where('kode', 'like', '%' . $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last_record && strlen($last_record->kode) == 7) {
            $counter = (int)substr($last_record->kode, 3, 4);
        }
        $no = sprintf("%0{$digit}d", $counter + 1);
        return $prefix . $no;
    }
}
