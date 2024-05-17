<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoundsController;

class HomeController extends Controller
{
    public function index(Request $request)
{
    if (!$request->session()->get('authenticated')) {
        // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
        return redirect('/login');
    }

    // $items = collect([
    //     (object) ['id' => 1, 'name' => 'Item 1', 'sound' => asset('sonidos/sonido1.mp3')],
    //     (object) ['id' => 2, 'name' => 'Item 2', 'sound' => asset('sonidos/sonido2.mp3')],
    //     (object) ['id' => 3, 'name' => 'Item 3', 'sound' => asset('sonidos/sonido3.mp3')],
    // ]);

    // Call the showAll method from the SoundController
    $soundController = new SoundsController;
    $response = $soundController->showAll();
    $items = json_decode($response->getContent());

    return view('home', ['items' => $items]);
}
}
