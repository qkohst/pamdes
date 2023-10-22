<?php

namespace App\Http\Controllers;

use App\Exports\PelangganExport;
use App\Imports\PelangganImport;
use App\Pelanggan;
use App\Transaksi;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    public static $columnames = [
        0 => 'aksi',
        1 => 'kode',
        2 => 'nama_lengkap',
        3 => 'nomor_hp_wa',
        4 => 'alamat',
        5 => 'status_str',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Pelanggan';
        $pagejs = 'pelanggan.js';
        if ($request->ajax()) {
            // FILTER
            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            $filter = '';
            if (!empty($params_array)) {
                foreach ($params_array as $key => $value) {
                    if ($value != null && $value != "" && $key == "filter_kode") {
                        $filter .= " AND kode LIKE '%" . $value . "%'";
                    }
                    if ($value != null && $value != "" && $key == "filter_nama_lengkap") {
                        $filter .= " AND nama_lengkap LIKE '%" . $value . "%'";
                    }
                    if ($value != null && $value != "" && $key == "filter_status") {
                        $filter .= " AND status = " . $value . "";
                    }
                }
            }

            // SEARCHING
            $search = $request->search['value'];
            $searching = '';
            if (!empty($search)) {
                $searching = " AND (
                    kode LIKE '%" . $search . "%' OR
                    nama_lengkap LIKE '%" . $search . "%' OR
                    nomor_hp_wa LIKE '%" . $search . "%' OR
                    alamat LIKE '%" . $search . "%' OR
                    IF(status = 1, 'Aktif', 'Non Aktif') LIKE '%" . $search . "%'
                )";
            }

            // SORTING & LIMIT
            $sorting = " ORDER BY " . self::$columnames[$request->order[0]['column']] . " " . $request->order[0]['dir'];
            $limit = " LIMIT " . $request->length . " OFFSET " . $request->start;

            // MAIN QUERY
            $query = 'SELECT id, kode, nama_lengkap, nomor_hp_wa, alamat, IF(status = 1, "Aktif", "Non Aktif") AS status_str  
                        FROM pelanggan
                        where is_delete = 0';

            $all_pelanggan = DB::select($query);
            $totalData = count($all_pelanggan);
            $pelanggan_filtered = DB::select($query . $searching . $filter);
            $totalFiltered = count($pelanggan_filtered);

            $data_pelanggan = DB::select($query . $searching . $filter . $sorting . $limit);

            // RETURN DATA
            $resultData = collect();
            foreach ($data_pelanggan as $pelanggan) {
                $arr = [];
                // cek data 
                $is_used = Transaksi::where("is_delete", false)->where("pelanggan_id", $pelanggan->id)->count();
                $can_delete = '';
                if ($is_used > 0) {
                    $can_delete = 'disabled';
                }

                $actionButtons = '<div class="form-button-action">
                                 <button data-id="' . $pelanggan->id . '" title="Edit" class="btn btn-edit btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                     <i class="fa fa-pen"></i>
                                 </button>
                                 <button type="button" ' . $can_delete . ' data-id="' . $pelanggan->id . '" title="Hapus" class="btn btn-delete btn-action btn-sm btn-danger ml-1">
                                     <i class="fa fa-trash"></i>
                                 </button>
                             </div>';

                $arr['action'] = $actionButtons;
                $arr['kode'] = $pelanggan->kode;
                $arr['nama_lengkap'] = $pelanggan->nama_lengkap;
                $arr['nomor_hp_wa'] =  $pelanggan->nomor_hp_wa;
                $arr['alamat'] =  $pelanggan->alamat;
                $arr['status_str'] =  $pelanggan->status_str;
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
        return view('pelanggan.index', compact('title', 'pagejs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_duplicate = Pelanggan::where('kode', strtolower($request->kode_pelanggan))->where('is_delete', false)->first();
        if (!is_null($is_duplicate)) {
            $response = [
                'status'  => 'error',
                'message' => 'Kode pelanggan ' . $request->kode_pelanggan . ' telah digunakan'
            ];
            return response()->json($response, 200);
        }

        $kode_pelanggan = $request->kode_pelanggan;
        if ($request->kode_pelanggan == '<AUTO GENERATE>') {
            $kode_pelanggan = Pelanggan::getKodePelanggan();
        }

        $pelanggan = new Pelanggan();
        $pelanggan->kode = $kode_pelanggan;
        $pelanggan->nama_lengkap = $request->nama_lengkap;
        $pelanggan->nomor_hp_wa = $request->nomor_hp_wa;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->save();

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
        $pelanggan = Pelanggan::findorfail($id);
        $response = [
            'pelanggan_id'  => $pelanggan->id,
            'kode_pelanggan' => $pelanggan->kode,
            'nama_lengkap' => $pelanggan->nama_lengkap,
            'nomor_hp_wa' => $pelanggan->nomor_hp_wa,
            'alamat' => $pelanggan->alamat,
            'status' => $pelanggan->status
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
        $pelanggan = Pelanggan::findorfail($id);
        $pelanggan->nama_lengkap = $request->nama_lengkap;
        $pelanggan->nomor_hp_wa = $request->nomor_hp_wa;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->status = $request->status;
        $pelanggan->save();

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
        $pelanggan = Pelanggan::findorfail($id);
        // cek data 
        $is_used = Transaksi::where("is_delete", false)->where("pelanggan_id", $pelanggan->id)->count();
        if ($is_used > 0) {
            $response = [
                'status'  => 'error',
                'message' => 'Data pelanggan sudah digunakan pada transaksi'
            ];
            return response()->json($response, 200);
        }

        $pelanggan->is_delete = true;
        $pelanggan->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return response()->json($response, 200);
    }

    public function download_format_import()
    {
        $file = public_path() . "/format_import_excel/format_import_pelanggan.xls";
        $headers = array(
            'Content-Type: application/xls',
        );
        return Response::download($file, 'format_import_pelanggan ' . date('Y-m-d H_i_s') . '.xls', $headers);
    }

    public function import(Request $request)
    {
        // dd($request->file('file_import'));

        // get array data
        $file = $request->file('file_import');
        $filePath = $file->store('temp');
        $import = new PelangganImport();
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

        // cek apakah data sudah ada 
        foreach ($importedData as $data) {
            $is_duplicate_kode = Pelanggan::where('kode', $data['kode_pelanggan'])
                ->where('is_delete', false)->first();
            if (!is_null($is_duplicate_kode)) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Kode pelanggan ' . $data['kode_pelanggan'] . ' telah terdata di sistem, silahkan perbaiki data anda dan import ulang'
                ];
                return response()->json($response, 200);
            }

            $is_duplicate_data = Pelanggan::where('nama_lengkap', $data['nama_lengkap'])
                ->where('nomor_hp_wa', $data['nomor_hp_wa'])
                ->where('is_delete', false)->first();
            if (!is_null($is_duplicate_data)) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Pelanggan ' . $data['nama_lengkap'] . ' telah terdata di sistem, silahkan perbaiki data anda dan import ulang'
                ];
                return response()->json($response, 200);
            }

            if ($data['nama_lengkap'] == '' || $data['nama_lengkap'] == null) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Kolom nama lengkap tidak boleh kosong, silahkan perbaiki data anda dan import ulang'
                ];
                return response()->json($response, 200);
            }

            if ($data['alamat'] == '' || $data['alamat'] == null) {
                $response = [
                    'status'  => 'error',
                    'message' => 'Kolom alamat tidak boleh kosong, silahkan perbaiki data anda dan import ulang'
                ];
                return response()->json($response, 200);
            }
        }

        // loop save data
        foreach ($importedData as $data) {
            $kode_pelanggan = $data['kode_pelanggan'];
            if ($kode_pelanggan == '' || $kode_pelanggan == null) {
                $kode_pelanggan = Pelanggan::getKodePelanggan();
            }

            $pelanggan = new Pelanggan();
            $pelanggan->kode = $kode_pelanggan;
            $pelanggan->nama_lengkap = $data['nama_lengkap'];
            $pelanggan->nomor_hp_wa = $data['nomor_hp_wa'];
            $pelanggan->alamat = $data['alamat'];
            $pelanggan->save();
        }
        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return response()->json($response, 200);
    }

    public function export(Request $request)
    {
        $filter_data = $request->all();
        $filename = 'data_pelanggan ' . date('Y-m-d H_i_s') . '.xls';
        return Excel::download(new PelangganExport($filter_data), $filename);
    }
}
