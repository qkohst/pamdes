<?php

namespace App\Http\Controllers;

use App\Pelanggan;
use Illuminate\Http\Request;
use DataTables;

class PelangganController extends Controller
{
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
            $data_pelanggan = Pelanggan::where('is_delete', false)->get();
            // FILTER
            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            if (!empty($params_array)) {
                foreach ($params_array as $key => $value) {
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
            }

            // RETURN DATA
            return DataTables::of($data_pelanggan)
                ->addColumn('status_pelanggan', function ($pelanggan) {
                    return $pelanggan->status_pelanggan;
                })
                ->addColumn('action', function ($pelanggan) {
                    $actionButtons = '<div class="form-button-action">
                                        <button data-id="' . $pelanggan->id . ' title="Edit" class="btn btn-edit btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button type="button" data-id="' . $pelanggan->id . ' title="Hapus" class="btn btn-delete btn-action btn-sm btn-danger ml-1">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>';
                    return $actionButtons;
                })
                ->make(true);
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
        $pelanggan->is_delete = true;
        $pelanggan->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return response()->json($response, 200);
    }
}
