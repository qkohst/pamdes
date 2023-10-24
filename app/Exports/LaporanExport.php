<?php

namespace App\Exports;

use App\Transaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $data_laporan = Transaksi::where('is_delete', false)->where('status', true)->orderBy('bulan_tahun', 'DESC')->get();
        foreach ($this->filters as $key => $value) {
            if ($value != null && $value != "" && $key == "filter_kode") {
                $data_laporan = $data_laporan->filter(function ($transaski) use ($value) {
                    return strpos(strtolower($transaski->kode), strtolower($value)) !== false;
                });
            }
            if ($value != null && $value != "" && $key == "filter_bulan_tahun") {
                $data_laporan = $data_laporan->where('bulan_tahun', $value);
            }
            if ($value != null && $value != "" && $key == "filter_pelanggan") {
                $data_laporan = $data_laporan->where('pelanggan_id', $value);
            }
        }

        $time_download = date('Y-m-d H:i:s');
        return view('laporan.export', compact('time_download', 'data_laporan'));
    }
}
