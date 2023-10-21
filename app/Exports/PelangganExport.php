<?php

namespace App\Exports;

use App\Pelanggan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PelangganExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function  view(): View
    {
        $data_pelanggan = Pelanggan::where('is_delete', false)->get();
        foreach ($this->filters as $key => $value) {
            if ($value != null && $value != "" && $key == "filter_kode") {
                $data_pelanggan = $data_pelanggan->filter(function ($pelanggan) use ($value) {
                    return strpos(strtolower($pelanggan->kode), strtolower($value)) !== false;
                });
            }
            if ($value != null && $value != "" && $key == "filter_nama_lengkap") {
                $data_pelanggan = $data_pelanggan->filter(function ($pelanggan) use ($value) {
                    return strpos(strtolower($pelanggan->nama_lengkap), strtolower($value)) !== false;
                });
            }
            if ($value != null && $value != "" && $key == "filter_status") {
                $data_pelanggan = $data_pelanggan->where('status', $value);
            }
        }

        // dd($data_pelanggan);
        $time_download = date('Y-m-d H:i:s');
        return view('pelanggan.export', compact('time_download', 'data_pelanggan'));
    }
}
