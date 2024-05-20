<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    function logout() {
        session()->forget('nombre');
        session()->forget('authenticated');
    
        return redirect('/')->with('status', 'Cierre de sesión exitoso!');
    }
}
