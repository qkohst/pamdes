<?php

namespace App;

use App\Helpers\OptionPeriodeHelper;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'pelanggan_id',
        'kode',
        'bulan_tahun',
        'pemakaian_sebelumnya',
        'pemakaian_saat_ini',
        'tarif_per_meter',
        'biaya_pemeliharaan',
        'biaya_administrasi',
        'status',
        'is_delete',
        'tanggal_pembayaran',
    ];

    public function pelanggan()
    {
        return $this->belongsTo('App\Pelanggan');
    }

    public function getBulanTahunIndoAttribute()
    {
        $result = OptionPeriodeHelper::tglIndo($this->bulan_tahun);
        return $result;
    }

    public function getTotalTagihanAttribute()
    {
        $result = (($this->pemakaian_saat_ini - $this->pemakaian_sebelumnya) * $this->tarif_per_meter) + $this->biaya_pemeliharaan + $this->biaya_administrasi;
        return $result;
    }

    public function getTotalPemakaianAttribute()
    {
        $result = $this->pemakaian_saat_ini - $this->pemakaian_sebelumnya;
        return $result;
    }

    public function getTanggalPembayaranIndoAttribute()
    {
        $result = OptionPeriodeHelper::tglIndoFull($this->tanggal_pembayaran);
        return $result;
    }

    public function getStatusStrAttribute()
    {
        if ($this->status == 1) {
            $result = 'Lunas';
        } elseif ($this->status == 0) {
            $result = 'belum Lunas';
        }
        return $result;
    }

    static function getKodeTransaksi($bulan_tahun)
    {
        $prefix = str_replace("-", "", $bulan_tahun);
        $counter = 0;
        $digit = 4;
        $last_record = Transaksi::where('is_delete', false)->where('kode', 'like', '%' . $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last_record) {
            $counter = (int)substr($last_record->kode, 6, 4);
        }
        $no = sprintf("%0{$digit}d", $counter + 1);
        return $prefix . $no;
    }
}
