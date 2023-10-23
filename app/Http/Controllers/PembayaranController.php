<?php

namespace App\Http\Controllers;

use App\Exports\PembayaranExport;
use App\Helpers\NumberFormatHelper;
use App\Helpers\OptionPeriodeHelper;
use App\Pelanggan;
use App\SettingGlobal;
use App\TarifAir;
use App\Transaksi;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class PembayaranController extends Controller
{
    public static $columnames = [
        0 => 'aksi',
        1 => 'kode_transaksi',
        2 => 'bulan_tahun_indo',
        3 => 'nama_pelanggan',
        4 => 'total_pemakaian',
        5 => 'total_tagihan',
        6 => 'status_str',
    ];

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
        $data_transaksi_sudah_bayar = Transaksi::where('is_delete', false)->where('status', true)->groupBy('bulan_tahun')->orderBy('bulan_tahun', 'DESC')->get();
        if ($request->ajax()) {
            // FILTER
            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            $filter = '';
            if (!empty($params_array)) {
                foreach ($params_array as $key => $value) {
                    if ($value != null && $value != "" && $key == "filter_kode") {
                        $filter .= " AND t.kode LIKE '%" . $value . "%'";
                    }
                    if ($value != null && $value != "" && $key == "filter_bulan_tahun") {
                        $filter .= " AND t.bulan_tahun = '" . $value . "'";
                    }
                    if ($value != null && $value != "" && $key == "filter_pelanggan") {
                        $filter .= " AND t.pelanggan_id = " . $value . "";
                    }
                }
            }

            // SEARCHING
            $search = $request->search['value'];
            $searching = '';
            if (!empty($search)) {
                $searching = " AND (
                    t.kode LIKE '%" . $search . "%' OR
                    CONCAT(
                        CASE SUBSTRING(t.bulan_tahun, 6, 2)
                            WHEN '01' THEN 'Januari'
                            WHEN '02' THEN 'Februari'
                            WHEN '03' THEN 'Maret'
                            WHEN '04' THEN 'April'
                            WHEN '05' THEN 'Mei'
                            WHEN '06' THEN 'Juni'
                            WHEN '07' THEN 'Juli'
                            WHEN '08' THEN 'Agustus'
                            WHEN '09' THEN 'September'
                            WHEN '10' THEN 'Oktober'
                            WHEN '11' THEN 'November'
                            WHEN '12' THEN 'Desember'
                        END, ' ', SUBSTRING(t.bulan_tahun, 1, 4)) LIKE '%" . $search . "%' OR
                    CONCAT(p.kode, ' | ', p.nama_lengkap) LIKE '%" . $search . "%' OR
                    (t.pemakaian_saat_ini - t.pemakaian_sebelumnya) LIKE '%" . $search . "%' OR 
                    REPLACE(FORMAT((((t.pemakaian_saat_ini - t.pemakaian_sebelumnya)*t.tarif_per_meter)+t.biaya_pemeliharaan+biaya_administrasi), 2), ',', '.') LIKE '%" . str_replace(",", ".", $search) . "%' OR 
                    IF(t.status = 1, 'Lunas', 'Belum Lunas') LIKE '%" . $search . "%'
                )";
            }

            // SORTING & LIMIT
            $sorting = " ORDER BY " . self::$columnames[$request->order[0]['column']] . " " . $request->order[0]['dir'];
            $limit = " LIMIT " . $request->length . " OFFSET " . $request->start;

            // MAIN QUERY
            $query = 'SELECT t.id as transaksi_id, t.kode as kode_transaksi, 
                        CONCAT(
                            CASE SUBSTRING(t.bulan_tahun, 6, 2)
                                WHEN "01" THEN "Januari"
                                WHEN "02" THEN "Februari"
                                WHEN "03" THEN "Maret"
                                WHEN "04" THEN "April"
                                WHEN "05" THEN "Mei"
                                WHEN "06" THEN "Juni"
                                WHEN "07" THEN "Juli"
                                WHEN "08" THEN "Agustus"
                                WHEN "09" THEN "September"
                                WHEN "10" THEN "Oktober"
                                WHEN "11" THEN "November"
                                WHEN "12" THEN "Desember"
                            END, " ", SUBSTRING(t.bulan_tahun, 1, 4)) AS bulan_tahun_indo, 
                        CONCAT(p.kode, " | ", p.nama_lengkap) AS nama_pelanggan, 
                        (t.pemakaian_saat_ini - t.pemakaian_sebelumnya) AS total_pemakaian,
                        (((t.pemakaian_saat_ini - t.pemakaian_sebelumnya)*t.tarif_per_meter)+t.biaya_pemeliharaan+biaya_administrasi) AS total_tagihan,
                        IF(t.status = 1, "Lunas", "Belum Lunas") AS status_str
                        FROM transaksi AS t
                        INNER JOIN pelanggan AS p
                        ON t.pelanggan_id = p.id
                        where t.status = 1 AND t.is_delete = 0';

            $all_transaksi = DB::select($query);
            $totalData = count($all_transaksi);
            $transaksi_filtered = DB::select($query . $searching . $filter);
            $totalFiltered = count($transaksi_filtered);

            $data_transaksi = DB::select($query . $searching . $filter . $sorting . $limit);

            // RETURN DATA
            $resultData = collect();
            foreach ($data_transaksi as $transaksi) {
                $arr = [];
                $actionButtons = '<div class="form-button-action">
                                    <button  type="button" data-id="' . $transaksi->transaksi_id . '" title="Cetak Slip" class="btn btn-print btn-action btn-sm btn-primary ml-1">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    <button data-id="' . $transaksi->transaksi_id . '" title="Detail" class="btn btn-show btn-action btn-sm btn-success ml-1" data-toggle="modal" data-target="#modalDetailData">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>';

                $arr['action'] = $actionButtons;
                $arr['kode'] = $transaksi->kode_transaksi;
                $arr['bulan_tahun_indo'] = $transaksi->bulan_tahun_indo;
                $arr['nama_pelanggan'] =  $transaksi->nama_pelanggan;
                $arr['total_pemakaian'] = NumberFormatHelper::decimal($transaksi->total_pemakaian);
                $arr['total_tagihan'] = NumberFormatHelper::currency($transaksi->total_tagihan);
                $arr['status_str'] =  $transaksi->status_str;
                $resultData->push($arr);
            }

            $response = [
                "draw" => intval($request->draw),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $resultData,
            ];

            return response()->json($response);
        };
        return view('pembayaran.index', compact('title', 'pagejs', 'data_pelanggan', 'option_bulan_tahun', 'data_transaksi_belum_bayar', 'data_transaksi_sudah_bayar'));
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

    public function print_slip(Request $request)
    {
        $transaksi = Transaksi::findorfail($request->transaksi_id);
        $setting_global = SettingGlobal::first();
        $petugas = Auth::user()->nama;
        $waktu_cetak = OptionPeriodeHelper::tglIndoFull(date("Y-m-d")) . " " . date("H:i:s") . " WIB";
        $title = "SLIP PEMBAYARAN " . $transaksi->kode;
        if ($setting_global->ukuran_kertas_nota == 77) {
            $slip = PDF::loadview('pembayaran.slip_77', compact('title', 'transaksi', 'setting_global', 'petugas', 'waktu_cetak'));
        }
        if ($setting_global->ukuran_kertas_nota == 55) {
            $slip = PDF::loadview('pembayaran.slip_55', compact('title', 'transaksi', 'setting_global', 'petugas', 'waktu_cetak'));
        }
        return $slip->stream('SLIP PEMBAYARAN ' . $transaksi->kode . '.pdf');
    }

    public function print_all(Request $request)
    {
        $data_transaksi = Transaksi::where('bulan_tahun', $request->periode_bulan)->where('status', true)->get();
        $setting_global = SettingGlobal::first();
        $petugas = Auth::user()->nama;
        $waktu_cetak = OptionPeriodeHelper::tglIndoFull(date("Y-m-d")) . " " . date("H:i:s") . " WIB";
        $title = "SLIP PEMBAYARAN " . $request->periode_bulan;
        if ($setting_global->ukuran_kertas_nota == 77) {
            $slip = PDF::loadview('pembayaran.slip_77_aal', compact('title', 'data_transaksi', 'setting_global', 'petugas', 'waktu_cetak'));
        }
        if ($setting_global->ukuran_kertas_nota == 55) {
            $slip = PDF::loadview('pembayaran.slip_55_all', compact('title', 'data_transaksi', 'setting_global', 'petugas', 'waktu_cetak'));
        }
        return $slip->stream('SLIP PEMBAYARAN ' . $request->periode_bulan . '.pdf');
    }
}
