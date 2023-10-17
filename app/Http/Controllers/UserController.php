<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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
            $data_user = User::where('id', '!=', Auth::user()->id)->where('is_delete', false)->get();
            // FILTER
            $filter_record = (isset($request->filter_record)) ? $request->filter_record : '';
            $params_array = [];
            parse_str($filter_record, $params_array);
            if (!empty($params_array)) {
                foreach ($params_array as $key => $value) {
                    if ($value != null && $value != "" && $key == "filter_nama") {
                        $data_user = $data_user->filter(function ($user) use ($value) {
                            return strpos(strtolower($user->nama), strtolower($value)) !== false;
                        });
                    }
                    if ($value != null && $value != "" && $key == "filter_username") {
                        $data_user = $data_user->filter(function ($user) use ($value) {
                            return strpos(strtolower($user->username), strtolower($value)) !== false;
                        });
                    }
                    if ($value != null && $value != "" && $key == "filter_status") {
                        $data_user = $data_user->where('status', $value);
                    }
                }
            }

            // RETURN DATA
            return DataTables::of($data_user)
                ->addColumn('status_user', function ($user) {
                    return $user->status_user;
                })
                ->addColumn('action', function ($user) {
                    $actionButtons = '<div class="form-button-action">
                                        <button data-id="' . $user->id . ' title="Edit" class="btn btn-edit btn-action btn-sm btn-primary ml-1" data-toggle="modal" data-target="#modalEditData">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button type="button" data-id="' . $user->id . ' title="Hapus" class="btn btn-delete btn-action btn-sm btn-danger ml-1">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>';
                    return $actionButtons;
                })
                ->make(true);
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
        $validator = Validator::make($request->all(), [
            'nama' => 'max:100',
            'username' => 'max:100|unique:user',
        ]);

        if ($validator->fails()) {
            $response = [
                'status'  => 'error',
                'message' => $validator->messages()->all()[0]
            ];
            return response()->json($response, 200);
        }

        $user = new User();
        $user->nama = $request->nama;
        $user->username = strtolower($request->username);
        $user->password = bcrypt($request->password);
        $user->role = 1;
        $user->status = true;
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
        $checkUsername = User::where('username', strtolower($request->username))->where('id', '!=', $id)->first();
        if (!is_null($checkUsername)) {
            $response = [
                'status'  => 'error',
                'message' => 'username ' . $request->username . ' telah digunakan'
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
