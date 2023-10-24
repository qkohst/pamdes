<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatHelper;
use App\Pelanggan;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Dashboard';
        $pagejs = 'dashboard.js';
        return view('dashboard.index', compact('title', 'pagejs'));
    }

    public function get_rekap_data()
    {
        $pelanggan_aktif = Pelanggan::where('is_delete', false)->where('status', true)->count();
        $pelanggan_non_aktif = Pelanggan::where('is_delete', false)->where('status', false)->count();
        $total_pelanggan = Pelanggan::where('is_delete', false)->count();

        $bulan_sekarang = date('Y-m');
        $tahun_sekarang = date('Y');
        $pembayaran_bulan_ini = DB::table('transaksi as t')
            ->selectRaw('SUM((((t.pemakaian_saat_ini - t.pemakaian_sebelumnya) * t.tarif_per_meter) + t.biaya_pemeliharaan + biaya_administrasi)) AS total_tagihan')
            ->where('is_delete', 0)
            ->where('status', 1)
            ->where('bulan_tahun', $bulan_sekarang)
            ->value('total_tagihan');

        $pembayaran_tahun_ini = DB::table('transaksi as t')
            ->selectRaw('SUM((((t.pemakaian_saat_ini - t.pemakaian_sebelumnya) * t.tarif_per_meter) + t.biaya_pemeliharaan + biaya_administrasi)) AS total_tagihan')
            ->where('is_delete', 0)
            ->where('status', 1)
            ->where('bulan_tahun',  'like', '%' . $tahun_sekarang . '%')
            ->value('total_tagihan');

        $total_pembayaran = DB::table('transaksi as t')
            ->selectRaw('SUM((((t.pemakaian_saat_ini - t.pemakaian_sebelumnya) * t.tarif_per_meter) + t.biaya_pemeliharaan + biaya_administrasi)) AS total_tagihan')
            ->where('is_delete', 0)
            ->where('status', 1)
            ->value('total_tagihan');

        $value_statistic = [];
        for ($j = 1; $j <= 12; $j++) {
            $value = DB::table('transaksi as t')
                ->selectRaw('SUM((((t.pemakaian_saat_ini - t.pemakaian_sebelumnya) * t.tarif_per_meter) + t.biaya_pemeliharaan + biaya_administrasi)) AS total_tagihan')
                ->where('is_delete', 0)
                ->where('status', 1)
                ->where('bulan_tahun', $tahun_sekarang . '-' . sprintf("%02d", $j))
                ->value('total_tagihan');
            array_push($value_statistic, $value);
        }


        $result = [
            'pelanggan' => [
                'aktif' => $pelanggan_aktif,
                'non_aktif' => $pelanggan_non_aktif,
                'total' => $total_pelanggan
            ],
            'pembayaran' => [
                'bulan_ini' => 'Rp ' . NumberFormatHelper::currency($pembayaran_bulan_ini),
                'tahun_ini' => 'Rp ' . NumberFormatHelper::currency($pembayaran_tahun_ini),
                'total' => 'Rp ' . NumberFormatHelper::currency($total_pembayaran),
            ],
            'statistic' => [
                'label' => ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                'value' => $value_statistic
            ]
        ];

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $result
        ];
        return response()->json($response, 200);
    }
}
