<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    function update(Request $request, $nombre) {
        $user = [
            'nombre' => $request->input('nombre'),
            'password' => Hash::make($request->input('password')),
        ];
    
        Redis::hmset('user:' . $nombre, $user);
    
        return redirect('/')->with('status', 'Usuario actualizado con éxito!');
    }

    function delete(Request $request) {
        Redis::del('user:' . $request->nombre);
    
        return redirect('/')->with('status', 'Usuario eliminado con éxito!');
    }

    function show($usuario) {
        $usuario = Redis::hgetall('user:'.$usuario);
    
        return response()->json($usuario);
    }

    function showAll() {
        $keys = Redis::keys('*');
        $data = [];
    
        foreach ($keys as $key) {
            $data[$key] = Redis::hgetall($key);
        }
    
        return response()->json($data);
    }
}
