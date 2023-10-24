<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatHelper;
use App\Helpers\OptionPeriodeHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public static $columnames = [
        0 => 'nama_pelanggan',
        1 => 'bulan_tahun_indo',
        2 => 'kode_transaksi',
        3 => 'total_pemakaian',
        4 => 'total_tagihan',
        5 => 'status_str',
    ];

    public function index()
    {
        $title = 'Login';
        $pagejs = 'login.js';
        return view('auth.login', compact('title', 'pagejs'));
    }

    public function store(Request $request)
    {
        $user_login = User::where('username', $request->username)->where('is_delete', false)->first();
        if (!$user_login) {
            $response = [
                'status'  => 'error',
                'message' => 'Username tidak ditemukan'
            ];
            return response()->json($response, 200);
        }
        if ($user_login->status == false) {
            $response = [
                'status'  => 'error',
                'message' => 'User ' . $user_login->username . ' telah dinonaktifkan'
            ];
            return response()->json($response, 200);
        }
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $response = [
                'status'  => 'error',
                'message' => 'Password salah'
            ];
            return response()->json($response, 200);
        }

        $response = [
            'status'  => 'success',
            'message' => 'Login berhasil'
        ];
        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function cek_tagihan(Request $request)
    {
        $title = 'Cek Tagihan';
        $pagejs = 'cet_tagihan.js';
        $option_bulan_tahun = OptionPeriodeHelper::getOptionBulanTahun();
        if ($request->ajax()) {
            // FILTER
            $totalData = 0;
            $totalFiltered = 0;
            $resultData = collect();

            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            if (!empty($params_array) && $params_array['filter_kode'] != '') {
                $filter = '';
                $filter_kode_pelanggan = '';
                if (!empty($params_array)) {
                    foreach ($params_array as $key => $value) {
                        if ($value != null && $value != "" && $key == "filter_kode") {
                            $filter_kode_pelanggan = " AND p.kode LIKE '%" . trim($value) . "%'";
                        }
                        if ($value != null && $value != "" && $key == "filter_bulan_tahun") {
                            $filter .= " AND t.bulan_tahun = '" . $value . "'";
                        }
                        if ($value != null && $value != "" && $key == "filter_status") {
                            $filter .= " AND t.status = '" . $value . "'";
                        }
                    }
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
                        IF(t.status = 1, 
                        (((t.pemakaian_saat_ini - t.pemakaian_sebelumnya)*t.tarif_per_meter)+t.biaya_pemeliharaan+biaya_administrasi),
                        ((((t.pemakaian_saat_ini - t.pemakaian_sebelumnya)*(SELECT tarif_per_meter FROM tarif_air LIMIT 1))+(SELECT biaya_pemeliharaan FROM tarif_air LIMIT 1)+(SELECT biaya_administrasi FROM tarif_air LIMIT 1))) 
                        )
                        AS total_tagihan,
                        IF(t.status = 1, "Lunas", "Belum Lunas") AS status_str
                        FROM transaksi AS t
                        INNER JOIN pelanggan AS p
                        ON t.pelanggan_id = p.id
                        where t.is_delete = 0 ' . $filter_kode_pelanggan;

                $all_transaksi = DB::select($query);
                $totalData = count($all_transaksi);
                $transaksi_filtered = DB::select($query . $filter);
                $totalFiltered = count($transaksi_filtered);

                $data_transaksi = DB::select($query . $filter . $sorting . $limit);

                // RETURN DATA
                $resultData = collect();
                foreach ($data_transaksi as $transaksi) {
                    $arr = [];
                    $arr['nama_pelanggan'] =  $transaksi->nama_pelanggan;
                    $arr['bulan_tahun_indo'] = $transaksi->bulan_tahun_indo;
                    $arr['kode'] = $transaksi->kode_transaksi;
                    $arr['total_pemakaian'] = NumberFormatHelper::decimal($transaksi->total_pemakaian);
                    $arr['total_tagihan'] = NumberFormatHelper::currency($transaksi->total_tagihan);
                    $arr['status_str'] =  $transaksi->status_str;
                    $resultData->push($arr);
                }
            }

            $response = [
                "draw" => intval($request->draw),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $resultData,
            ];

            return response()->json($response);
        };
        return view('auth.cek_tagihan', compact('title', 'pagejs', 'option_bulan_tahun'));
    }
}
