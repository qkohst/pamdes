<?php

namespace App\Exports;

use App\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PembayaranExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function  view(): View
    {
        $data_pembayaran = Transaksi::where('is_delete', false)->where('status', true)->get();
        foreach ($this->filters as $key => $value) {
            if ($value != null && $value != "" && $key == "filter_kode") {
                $data_transaksi = $data_transaksi->filter(function ($transaski) use ($value) {
                    return strpos(strtolower($transaski->kode), strtolower($value)) !== false;
                });
            }
            if ($value != null && $value != "" && $key == "filter_bulan_tahun") {
                $data_transaksi = $data_transaksi->where('bulan_tahun', $value);
            }
            if ($value != null && $value != "" && $key == "filter_pelanggan") {
                $data_transaksi = $data_transaksi->where('pelanggan_id', $value);
            }
        }

        $time_download = date('Y-m-d H:i:s');
        return view('pembayaran.export', compact('time_download', 'data_pembayaran'));
    }
}
