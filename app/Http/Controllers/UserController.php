<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;

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
            $data_user = User::where('is_delete', false)->get();
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
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-success" data-original-title="View">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-primary ml-1" data-original-title="Edit">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-action btn-sm btn-danger ml-1" data-original-title="Delete">
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
