<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;


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

Route::get('/ver-claves', function () {
    $keys = Redis::keys('*');

    return response()->json($keys);
});

Route::get('/signup', [SignupController::class,'showRegistrationForm']);



// Registrar un usuario
Route::post('/registrar-usuario', [SignupController::class,'register']);

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
        'password' => Hash::make($request->input('password')),
    ];

    Redis::hmset('user:' . $nombre, $user);

    return redirect('/')->with('status', 'Usuario actualizado con éxito!');
});

Route::get('/login',[loginController::class,'showLoginForm'])->name('login');

Route::post('/login', [loginController::class,'login']);

Route::get('/logout', function () {
    session()->forget('nombre');
    session()->forget('authenticated');

    return redirect('/')->with('status', 'Cierre de sesión exitoso!');
});

Route::get('/home', [HomeController::class,'index']);

?>