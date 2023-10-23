<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatHelper;
use App\Helpers\OptionPeriodeHelper;
use App\Pelanggan;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public static $columnames = [
        0 => 'bulan_tahun_indo',
        1 => 'nama_pelanggan',
        2 => 't.kode',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $title = 'Laporan';
        $pagejs = 'laporan.js';
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
                    CONCAT(p.kode, ' | ', p.nama_lengkap) LIKE '%" . $search . "%'
                )";
            }

            // SORTING & LIMIT
            $sorting = " ORDER BY " . self::$columnames[$request->order[0]['column']] . " " . $request->order[0]['dir'];
            $limit = " LIMIT " . $request->length . " OFFSET " . $request->start;

            // MAIN QUERY
            $query = 'SELECT t.id as transaksi_id, t.kode as kode_transaksi, 
                        t.pemakaian_sebelumnya, t.pemakaian_saat_ini,
                        t.tarif_per_meter, t.biaya_pemeliharaan, t.biaya_administrasi, t.tanggal_pembayaran,
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
                        ((t.pemakaian_saat_ini - t.pemakaian_sebelumnya)*t.tarif_per_meter) AS biaya_air,
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
                $arr['bulan_tahun_indo'] = $transaksi->bulan_tahun_indo;
                $arr['nama_pelanggan'] =  $transaksi->nama_pelanggan;
                $arr['kode'] = $transaksi->kode_transaksi;
                $arr['pemakaian_sebelumnya'] = NumberFormatHelper::decimal($transaksi->pemakaian_sebelumnya);
                $arr['pemakaian_saat_ini'] = NumberFormatHelper::decimal($transaksi->pemakaian_saat_ini);
                $arr['total_pemakaian'] = NumberFormatHelper::decimal($transaksi->total_pemakaian);

                $arr['biaya_air'] = NumberFormatHelper::currency($transaksi->biaya_air);
                $arr['biaya_pemeliharaan'] = NumberFormatHelper::currency($transaksi->biaya_pemeliharaan);
                $arr['biaya_administrasi'] = NumberFormatHelper::currency($transaksi->biaya_administrasi);
                $arr['total_tagihan'] = NumberFormatHelper::currency($transaksi->total_tagihan);
                $arr['bayar'] = NumberFormatHelper::currency($transaksi->total_tagihan);
                $arr['tanggal_pembayaran'] = OptionPeriodeHelper::tglIndoFull($transaksi->tanggal_pembayaran);
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
        return view('laporan.index', compact('title', 'pagejs', 'data_pelanggan', 'option_bulan_tahun', 'data_transaksi_belum_bayar', 'data_transaksi_sudah_bayar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
