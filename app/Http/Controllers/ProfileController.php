<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Profile';
        $pagejs = 'profile.js';
        return view('profile.index', compact('title', 'pagejs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $is_duplicate = User::where('username', strtolower($request->username))->where('id', '!=', $user->id)->where('is_delete', 1)->first();
        if (!is_null($is_duplicate)) {
            $response = [
                'status'  => 'error',
                'message' => 'Username ' . $request->username . ' telah digunakan'
            ];
            return response()->json($response, 200);
        }

        if ($request->password_lama != '' && Hash::check($request->password_lama, $user->password) == false) {
            $response = [
                'status'  => 'error',
                'message' => 'Password lama tidak sesuai'
            ];
            return response()->json($response, 200);
        }

        $user->nama = $request->nama;
        $user->username = $request->username;
        if ($request->foto_profile != 'undefined' && $request->has('foto_profile')) {
            $avatar_file = $request->file('foto_profile');
            $name_avatar = 'img-' . date('dmYHis') . '.' . $avatar_file->getClientOriginalExtension();
            $avatar_file->move('assets/img/user/', $name_avatar);
            $user->avatar = $name_avatar;
        }
        if (!is_null($request->password_baru)) {
            $user->password = bcrypt($request->password_baru);
        }
        $user->update();

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
