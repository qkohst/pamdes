<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public static $columnames = [
        0 => 'aksi',
        1 => 'nama',
        2 => 'username',
        3 => 'status_str',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'User';
        $pagejs = 'user.js';
        if ($request->ajax()) {
            // FILTER
            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            $filter = '';
            if (!empty($params_array)) {
                foreach ($params_array as $key => $value) {
                    if ($value != null && $value != "" && $key == "filter_nama") {
                        $filter .= " AND nama LIKE '%" . $value . "%'";
                    }
                    if ($value != null && $value != "" && $key == "filter_username") {
                        $filter .= " AND username LIKE '%" . $value . "%'";
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
                    nama LIKE '%" . $search . "%' OR
                    username LIKE '%" . $search . "%' OR
                    IF(status = 1, 'Aktif', 'Non Aktif') LIKE '%" . $search . "%'
                )";
            }

            // SORTING & LIMIT
            $sorting = " ORDER BY " . self::$columnames[$request->order[0]['column']] . " " . $request->order[0]['dir'];
            $limit = " LIMIT " . $request->length . " OFFSET " . $request->start;

            // MAIN QUERY
            $query = 'SELECT id, nama, username, IF(status = 1, "Aktif", "Non Aktif") AS status_str  
                        FROM user
                        where is_delete = 0 AND id != ' . Auth::user()->id;

            $all_user = DB::select($query);
            $totalData = count($all_user);
            $user_filtered = DB::select($query . $searching . $filter);
            $totalFiltered = count($user_filtered);

            $data_user = DB::select($query . $searching . $filter . $sorting . $limit);

            // RETURN DATA
            $resultData = collect();
            foreach ($data_user as $user) {
                $arr = [];
                $actionButtons = '<div class="form-button-action">
                                 <button data-id="' . $user->id . '" title="Edit" class="btn btn-edit btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                     <i class="fa fa-pen"></i>
                                 </button>
                                 <button type="button" data-id="' . $user->id . '" title="Hapus" class="btn btn-delete btn-action btn-sm btn-danger ml-1">
                                     <i class="fa fa-trash"></i>
                                 </button>
                             </div>';

                $arr['action'] = $actionButtons;
                $arr['nama'] = $user->nama;
                $arr['username'] = $user->username;
                $arr['status_str'] =  $user->status_str;
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
        return view('user.index', compact('title', 'pagejs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_duplicate = User::where('username', strtolower($request->username))->where('is_delete', false)->first();
        if (!is_null($is_duplicate)) {
            $response = [
                'status'  => 'error',
                'message' => 'Username ' . $request->username . ' telah digunakan'
            ];
            return response()->json($response, 200);
        }

        $user = new User();
        $user->nama = $request->nama;
        $user->username = strtolower($request->username);
        $user->password = bcrypt($request->password);
        $user->role = 1;
        $user->avatar = 'default.png';
        $user->save();

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
        $user = User::findorfail($id);
        $response = [
            'user_id'  => $user->id,
            'nama' => $user->nama,
            'username' => $user->username,
            'status' => $user->status
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
        $is_duplicate = User::where('username', strtolower($request->username))->where('id', '!=', $id)->where('is_delete', 1)->first();
        if (!is_null($is_duplicate)) {
            $response = [
                'status'  => 'error',
                'message' => 'Username ' . $request->username . ' telah digunakan'
            ];
            return response()->json($response, 200);
        }

        $user = User::findorfail($id);
        $user->nama = $request->nama;
        $user->username = strtolower($request->username);
        $user->status = $request->status;
        if ($request->password != '') {
            $user->password = bcrypt($request->password);
        }
        $user->save();

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
        $user = User::findorfail($id);
        $user->is_delete = true;
        $user->save();

        $response = [
            'status'  => 'success',
            'message' => 'Data berhasil dihapus'
        ];
        return response()->json($response, 200);
    }
}
