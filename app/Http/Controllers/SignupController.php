<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class SignupController extends Controller
{
    public function showRegistrationForm()
    {
        return view('registro');
    }

    public function register(Request $request)
    {
        $user = [
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
        ];

        Redis::hmset('user:' . $request->nombre, $user);

        return redirect('/login')->with('status', 'Usuario registrado con éxito!');
    }
}