<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatHelper;
use App\Helpers\OptionPeriodeHelper;
use App\Imports\TransaksiImport;
use App\Pelanggan;
use App\Transaksi;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PemakaianController extends Controller
{
    public static $columnames = [
        0 => 'aksi',
        1 => 'kode_transaksi',
        2 => 'bulan_tahun_indo',
        3 => 'nama_pelanggan',
        4 => 'total_pemakaian',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Pemakaian';
        $pagejs = 'pemakaian.js';
        $option_bulan_tahun = OptionPeriodeHelper::getOptionBulanTahun();
        $data_pelanggan = Pelanggan::where('is_delete', false)->where('status', true)->get();

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
                    (t.pemakaian_saat_ini - t.pemakaian_sebelumnya) LIKE '%" . $search . "%'
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
                        (t.pemakaian_saat_ini - t.pemakaian_sebelumnya) AS total_pemakaian 
                        FROM transaksi AS t
                        INNER JOIN pelanggan AS p
                        ON t.pelanggan_id = p.id
                        where t.status = 0 AND t.is_delete = 0';

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
                                <button data-id="' . $transaksi->transaksi_id . '" title="Edit" class="btn btn-edit btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                    <i class="fa fa-pen"></i>
                                </button>
                                <button type="button" data-id="' . $transaksi->transaksi_id . '" title="Hapus" class="btn btn-delete btn-action btn-sm btn-danger ml-1">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>';

                $arr['action'] = $actionButtons;
                $arr['kode'] = $transaksi->kode_transaksi;
                $arr['bulan_tahun_indo'] = $transaksi->bulan_tahun_indo;
                $arr['nama_pelanggan'] =  $transaksi->nama_pelanggan;
                $arr['total_pemakaian'] = NumberFormatHelper::decimal($transaksi->total_pemakaian);
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
        return view('pemakaian.index', compact('title', 'pagejs', 'data_pelanggan', 'option_bulan_tahun'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_duplicate = Transaksi::where('pelanggan_id', $request->pelanggan_id)
            ->where('bulan_tahun', $request->bulan_tahun)
            ->where('is_delete', false)
            ->first();
        if (!is_null($is_duplicate)) {
            $response = [
                'status'  => 'error',
                'message' => 'Pemakaian pelanggan ' . $is_duplicate->pelanggan->nama_lengkap . ' bulan ' . OptionPeriodeHelper::tglIndo($is_duplicate->bulan_tahun) . ' telah tercatat',
            ];
            return response()->json($response, 200);
        }

        $kode_transaksi = Transaksi::getKodeTransaksi($request->bulan_tahun);

        $transaksi = new Transaksi();
        $transaksi->pelanggan_id = $request->pelanggan_id;
        $transaksi->kode = $kode_transaksi;
        $transaksi->bulan_tahun = $request->bulan_tahun;
        $transaksi->pemakaian_sebelumnya = $request->pemakaian_sebelumnya;
        $transaksi->pemakaian_saat_ini = $request->pemakaian_saat_ini;
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
            'pemakaian_sebelumnya' => $transaksi->pemakaian_sebelumnya,
            'pemakaian_saat_ini' => $transaksi->pemakaian_saat_ini,
        ];

        return response()->json($response, 200);
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
        $transaksi = Transaksi::findorfail($id);
        $transaksi->pemakaian_saat_ini = $request->pemakaian_saat_ini;
        $transaksi->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findorfail($id);
        $transaksi->is_delete = true;
        $transaksi->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return response()->json($response, 200);
    }

    public function get_transaksi_terakhir(Request $request)
    {
        $transaksi_terakhir = Transaksi::where("pelanggan_id", $request->pelanggan_id)->where('is_delete', false)->orderBy('id', 'desc')->first();
        if ($transaksi_terakhir) {
            $response = [
                'pemakaian_terakhir'  => $transaksi_terakhir->pemakaian_saat_ini,
            ];
        } else {
            $response = [
                'pemakaian_terakhir'  => 0,
            ];
        }
        return response()->json($response, 200);
    }

    public function download_format_import()
    {
        $file = public_path() . "/format_import_excel/format_import_pemakaian.xls";
        $headers = array(
            'Content-Type: application/xls',
        );
        return Response::download($file, 'format_import_pemakaian ' . date('Y-m-d H_i_s') . '.xls', $headers);
    }

    public function import(Request $request)
    {
        // dd($request->file('file_import'));
        // get array data
        $file = $request->file('file_import');
        $filePath = $file->store('temp');
        $import = new TransaksiImport();
        Excel::import($import, $filePath);
        Storage::delete($filePath);
        $importedData = $import->getData();
        // dd($importedData);
        if (count($importedData) == 0) {
            $response = [
                'status'  => 'error',
                'message' => 'Data pada file import tidak ditemukan, silahkan perbaiki data anda dan import ulang'
            ];
            return response()->json($response, 200);
        }

        // cek validasi data
        foreach ($importedData as $data) {
            if (strlen($data['bulan_tahun']) != 7) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Bulan tahun tidak valid, silahkan perbaiki data anda dan import ulang'
                ];
                return response()->json($response, 200);
            }

            $pelanggan = Pelanggan::where('kode', $data['kode_pelanggan'])
                ->where('is_delete', false)->first();
            if (is_null($pelanggan)) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Kode pelanggan ' . $data['kode_pelanggan'] . ' tidak ditemukan'
                ];
                return response()->json($response, 200);
            }

            $is_duplicate_data = Transaksi::where('pelanggan_id', $pelanggan->id)
                ->where('bulan_tahun', $data['bulan_tahun'])
                ->where('is_delete', false)->first();
            if (!is_null($is_duplicate_data)) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Transaksi bulan ' . $data['bulan_tahun'] . ' pelanggan ' . $pelanggan->kode . ' telah terdata di sistem, silahkan perbaiki data anda dan import ulang'
                ];
                return response()->json($response, 200);
            }

            if ($data['pemakaian_saat_ini'] == null || $data['pemakaian_saat_ini'] == 0) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Pemakaian bulan ini pelanggan ' . $pelanggan->kode . ' tidak boleh kosong atau 0'
                ];
                return response()->json($response, 200);
            }

            if ($data['pemakaian_sebelumnya'] == null || $data['pemakaian_sebelumnya'] == 0) {
                $transaksi_terakhir = Transaksi::where("pelanggan_id", $pelanggan->id)->where('is_delete', false)->orderBy('id', 'desc')->first();
                if (!is_null($transaksi_terakhir) && $data['pemakaian_saat_ini'] <= $transaksi_terakhir->pemakaian_saat_ini) {
                    $response = [
                        'status'  => 'error',
                        'message' => 'Pemakaian bulan ini pelanggan ' . $pelanggan->kode . ' tidak boleh kurang dari pemakaian pada bulan sebelumnya'
                    ];
                    return response()->json($response, 200);
                }
            }
        }

        // loop save data
        foreach ($importedData as $data) {
            $pelanggan = Pelanggan::where('kode', $data['kode_pelanggan'])
                ->where('is_delete', false)->first();
            $kode_transaksi = Transaksi::getKodeTransaksi($data['bulan_tahun']);
            $pemakaian_sebelumnya = $data['pemakaian_sebelumnya'];
            if ($pemakaian_sebelumnya == null || $pemakaian_sebelumnya == 0) {
                $transaksi_terakhir = Transaksi::where("pelanggan_id", $pelanggan->id)->where('is_delete', false)->orderBy('id', 'desc')->first();
                if (!is_null($transaksi_terakhir)) {
                    $pemakaian_sebelumnya = $transaksi_terakhir->pemakaian_saat_ini;
                } else {
                    $pemakaian_sebelumnya = 0;
                }
            }

            $transaksi = new Transaksi();
            $transaksi->pelanggan_id = $pelanggan->id;
            $transaksi->kode = $kode_transaksi;
            $transaksi->bulan_tahun = $data['bulan_tahun'];
            $transaksi->pemakaian_sebelumnya = $pemakaian_sebelumnya;
            $transaksi->pemakaian_saat_ini = $data['pemakaian_saat_ini'];
            $transaksi->save();
        }
        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return response()->json($response, 200);
    }
}
