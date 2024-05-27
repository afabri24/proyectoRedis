<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class SignupController extends Controller
{
    public function index()
    {
        return view('registro');
    }

    public function store(Request $request)
    {
        $user = [
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
        ];

        Redis::hmset('user:' . $request->usuario, $user);

        return redirect('/login')->with('status', 'Usuario registrado con éxito!');
    }
}