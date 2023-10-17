<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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
}
