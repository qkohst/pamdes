<?php

namespace App\Http\Controllers;

use App\Pelanggan;
use Illuminate\Http\Request;

class APIPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_pelanggan = Pelanggan::where('is_delete', false)->where('status', true)->get();
        $result_data = collect();
        foreach ($data_pelanggan as $pelanggan) {
            $arr = [];
            $arr['id_pelanggan'] = $pelanggan->id;
            $arr['kode_pelanggan'] = $pelanggan->kode;
            $arr['nama_pelanggan'] = $pelanggan->kode . ' | ' . $pelanggan->nama_lengkap;
            $result_data->push($arr);
        }

        $response = [
            'status'  => 'success',
            'message' => 'List data pelanggan',
            'data' => $result_data
        ];

        return response()->json($response, 200);
    }
}
