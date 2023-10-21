<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatHelper;
use App\Helpers\OptionPeriodeHelper;
use App\Imports\TransaksiImport;
use App\Pelanggan;
use App\Transaksi;
use Illuminate\Http\Request;
use DataTables;
use Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PemakaianController extends Controller
{
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
            $data_transaksi = Transaksi::where('is_delete', false)->where('status', false)->get();
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
                ->addColumn('action', function ($transaksi) {
                    $actionButtons = '<div class="form-button-action">
                                        <button data-id="' . $transaksi->id . ' title="Edit" class="btn btn-edit btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button type="button" data-id="' . $transaksi->id . ' title="Hapus" class="btn btn-delete btn-action btn-sm btn-danger ml-1">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>';
                    return $actionButtons;
                })
                ->make(true);
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
