<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoundsController;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->get('authenticated')) {
            return redirect('/login');
        }
        
        // Call the showAll method from the SoundController
        $soundController = new SoundsController;
        $response = $soundController->showAll();
        $items = json_decode($response->getContent());

        return view('home', ['items' => $items]);
    }
}
