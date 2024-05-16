<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
{
    if (!$request->session()->get('authenticated')) {
        // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
        return redirect('/login');
    }

    $items = collect([
        (object) ['id' => 1, 'name' => 'Item 1'],
        (object) ['id' => 2, 'name' => 'Item 2'],
        (object) ['id' => 3, 'name' => 'Item 3'],
    ]);

    return view('home', ['items' => $items]);
}
}
