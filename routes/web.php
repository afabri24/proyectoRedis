<?php

use App\Http\Controllers\SoundsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::get('/signup', [SignupController::class, 'index']);

Route::post('/usuario/login', [LoginController::class, 'login']);

Route::get('/usuario/logout', [LogoutController::class, 'logout']);

Route::post('/usuario/registrar', [SignupController::class, 'store']);

Route::delete('/usuario/{nombre}', [UserController::class, 'delete']);

Route::put('/usuario/{nombre}', [UserController::class, 'update']);

Route::get('/usuario/{usuario}', [UserController::class, 'show']);

Route::get('/usuario', [UserController::class, 'showAll']);

Route::get('/home', [HomeController::class, 'index']);

Route::post('/sound', [SoundsController::class, 'store']);

Route::get('/sounds', [SoundsController::class, 'showAll']);

Route::delete('/sound/{id}', [SoundsController::class, 'destroy']);
