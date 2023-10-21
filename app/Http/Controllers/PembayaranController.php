<?php

namespace App\Http\Controllers;

use App\Exports\PembayaranExport;
use App\Helpers\NumberFormatHelper;
use App\Helpers\OptionPeriodeHelper;
use App\Pelanggan;
use App\TarifAir;
use App\Transaksi;
use Illuminate\Http\Request;
use DataTables;
use Excel;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Pembayaran';
        $pagejs = 'pembayaran.js';
        $option_bulan_tahun = OptionPeriodeHelper::getOptionBulanTahun();
        $data_pelanggan = Pelanggan::where('is_delete', false)->get();
        $data_transaksi_belum_bayar = Transaksi::where('is_delete', false)->where('status', false)->get();

        if ($request->ajax()) {
            $data_transaksi = Transaksi::where('is_delete', false)->where('status', true)->get();
            // FILTER
            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            if (!empty($params_array)) {
                foreach ($params_array as $key => $value) {
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
            }

            // RETURN DATA
            return DataTables::of($data_transaksi)
                ->addColumn('bulan_tahun_indo', function ($transaksi) {
                    return $transaksi->bulan_tahun_indo;
                })
                ->addColumn('nama_pelanggan', function ($transaksi) {
                    return $transaksi->pelanggan->kode . ' | ' . $transaksi->pelanggan->nama_lengkap;
                })
                ->addColumn('total_pemakaian', function ($transaksi) {
                    return NumberFormatHelper::decimal($transaksi->total_pemakaian);
                })
                ->addColumn('total_tagihan', function ($transaksi) {
                    return NumberFormatHelper::currency($transaksi->total_tagihan);
                })
                ->addColumn('status_str', function ($transaksi) {
                    return $transaksi->status_str;
                })
                ->addColumn('action', function ($transaksi) {
                    $actionButtons = '<div class="form-button-action">
                                        <button data-id="' . $transaksi->id . ' title="Cetak Slip" class="btn btn-print btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        <button data-id="' . $transaksi->id . ' title="Detail" class="btn btn-show btn-action btn-sm btn-success ml-1" data-toggle="modal" data-target="#modalDetailData">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>';
                    return $actionButtons;
                })
                ->make(true);
        };
        return view('pembayaran.index', compact('title', 'pagejs', 'data_pelanggan', 'option_bulan_tahun', 'data_transaksi_belum_bayar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaksi = Transaksi::findorfail($request->transaksi_id);
        $transaksi->tarif_per_meter = $request->tarif_per_meter;
        $transaksi->biaya_pemeliharaan = $request->biaya_pemeliharaan;
        $transaksi->biaya_administrasi = $request->biaya_administrasi;
        $transaksi->tanggal_pembayaran = $request->tanggal_pembayaran;
        $transaksi->status = true;
        $transaksi->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi = Transaksi::findorfail($id);
        $response = [
            'transaksi_id'  => $transaksi->id,
            'kode_transaksi' => $transaksi->kode,
            'bulan_tahun' => $transaksi->bulan_tahun_indo,
            'pelanggan' => $transaksi->pelanggan->kode . ' | ' . $transaksi->pelanggan->nama_lengkap,
            'total_pemakaian' => $transaksi->total_pemakaian,
            'total_tagihan' => $transaksi->total_tagihan,
            'tanggal_pembayaran' => OptionPeriodeHelper::tglIndoFull($transaksi->tanggal_pembayaran),
            'status' => $transaksi->status_str,
        ];

        return response()->json($response, 200);
    }

    public function get_transaksi_belum_bayar(Request $request)
    {
        $data_transaksi_belum_bayar = Transaksi::where('pelanggan_id', $request->pelanggan_id)
            ->where('is_delete', false)
            ->where('status', false)
            ->get();

        $tarif_air = TarifAir::first();

        $result = collect();
        foreach ($data_transaksi_belum_bayar as $transaksi) {
            $arr = [];
            $arr['transaksi_id'] = $transaksi->id;
            $arr['bulan_tahun'] = OptionPeriodeHelper::tglIndo($transaksi->bulan_tahun);
            $arr['total_pemakaian'] = $transaksi->pemakaian_saat_ini - $transaksi->pemakaian_sebelumnya;
            $arr['tarif_per_meter'] = $tarif_air->tarif_per_meter;
            $arr['biaya_pemeliharaan'] = $tarif_air->biaya_pemeliharaan;
            $arr['biaya_administrasi'] = $tarif_air->biaya_administrasi;
            $arr['total_tagihan'] = (($transaksi->pemakaian_saat_ini - $transaksi->pemakaian_sebelumnya) * $tarif_air->tarif_per_meter) + $tarif_air->biaya_pemeliharaan + $tarif_air->biaya_administrasi;
            $result->push($arr);
        }

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $result
        ];
        return response()->json($response, 200);
    }

    public function export(Request $request)
    {
        $filter_data = $request->all();
        $filename = 'data_pembayaran ' . date('Y-m-d H_i_s') . '.xls';
        return Excel::download(new PembayaranExport($filter_data), $filename);
    }
}
