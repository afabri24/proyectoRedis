<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola-mundo', 'App\Http\Controllers\Controller@HolaMUNDO');

Route::get('/crear-usuario', function () {
    $user = [
        'nombre' => 'juan',
        'correo' => 'juan123@gmail.com',
        'contraseña' => '123456789'
    ];

    Redis::hMset('user:juan2', $user);

    return response()->json(['message' => 'Usuario creado en Redis']);
});

Route::get('/obtener-usuario', function () {
    $user = Redis::hgetall('user:juan2');

    return response()->json($user);
});

Route::get('/signup', function () {
    return view('registro');
});

Route::get('/ver-claves', function () {
    $keys = Redis::keys('*');

    return response()->json($keys);
});

// Registrar un usuario
Route::post('/registrar-usuario', function (Request $request) {
    $user = [
        'nombreUsuario' => $request->nombre,
        'password' => Hash::make($request->password),
    ];

    Redis::hmset('user:' . $request->nombre, $user);

    return redirect('/ver-todo')->with('status', 'Usuario registrado con éxito!');
});

Route::get('/ver-todo', function () {
    $keys = Redis::keys('*');
    $data = [];

    foreach ($keys as $key) {
        $data[$key] = Redis::hgetall($key);
    }

    return response()->json($data);
});

Route::delete('/eliminar-usuario/{nombre}', function ($nombre) {
    Redis::del('user:' . $nombre);

    return redirect('/')->with('status', 'Usuario eliminado con éxito!');
});


Route::put('/actualizar-usuario/{nombre}', function (Request $request, $nombre) {
    $user = [
        'nombre' => $request->input('nombre'),
        'contraseia' => Hash::make($request->input('password')),
    ];

    Redis::hmset('user:' . $nombre, $user);

    return redirect('/')->with('status', 'Usuario actualizado con éxito!');
});

Route::get('/login', function () {
    return view('inicioSesion');
});

Route::post('/login', function (Request $request) {
    $nombre = $request->input('nombre');
    $password = $request->input('password');

    $user = Redis::hgetall('user:' . $nombre);

    if ($user && Hash::check($password, $user['password'])) {
        // Iniciar sesión y redirigir al usuario
        session(['nombre' => $nombre]);
        return redirect('/')->with('status', 'Inicio de sesión exitoso!');
    } else {
        // Redirigir al usuario con un mensaje de error
        return redirect('/login')->with('error', 'Nombre de usuario o contraseña incorrectos.');
    }
});

?>