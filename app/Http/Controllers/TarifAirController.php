<?php

namespace App\Http\Controllers;

use App\TarifAir;
use Illuminate\Http\Request;

class TarifAirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Tarif Air';
        $pagejs = 'tarifair.js';
        $tarif_air = TarifAir::first();
        return view('tarif_air.index', compact('title', 'tarif_air', 'pagejs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tarif_air = TarifAir::first();
        $tarif_air->tarif_per_meter = $request->tarif_per_meter;
        $tarif_air->biaya_pemeliharaan = $request->biaya_pemeliharaan;
        $tarif_air->biaya_administrasi = $request->biaya_administrasi;
        $tarif_air->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return response()->json($response, 200);
    }
}
