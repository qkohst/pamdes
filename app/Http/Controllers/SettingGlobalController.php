<?php

namespace App\Http\Controllers;

use App\SettingGlobal;
use Illuminate\Http\Request;

class SettingGlobalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Setting Global';
        $pagejs = 'setting_global.js';
        $setting_global = SettingGlobal::first();
        return view('setting_global.index', compact('title', 'setting_global', 'pagejs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setting_global = SettingGlobal::first();
        $setting_global->nama = $request->nama_instansi;
        $setting_global->alamat = $request->alamat;
        $setting_global->nomor_hp_wa = $request->nomor_hp_wa;
        $setting_global->ukuran_kertas_nota = $request->ukuran_kertas_nota;
        $setting_global->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan'
        ];
        return response()->json($response, 200);
    }
}
