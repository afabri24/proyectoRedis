<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;



class SoundsController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->input('name');
        $file = $request->file('sound');
        $filename = $file->getClientOriginalName();

        // Check the size of the audio file
        if ($file->getSize() > 1048576) {
            return response()->json(['error' => 'El archivo de audio no puede ser mayor a 1MB.'], 400);
        }

        // Convert the audio file to a binary string
        $binaryData = file_get_contents($file->getPathname());

        // Generate a unique ID for the item
        $id = Str::uuid()->toString();

        // Create an array with the item data
        $item = [
            'id' => $id,
            'name' => $name,
            'sound' => base64_encode($binaryData)
        ];

        // Store the item in Redis as a JSON string
        Redis::rpush('audios', json_encode($item));

        return response()->json(['message' => 'Ítem guardado con éxito.', 'id' => $id]);
    }

    public function showAll()
    {
        // Get all the items from Redis
        $items = Redis::lrange('audios', 0, -1);

        // Create an array to hold the item objects
        $itemObjects = [];

        // Loop through the items and decode each one
        foreach ($items as $item) {
            $itemObjects[] = json_decode($item);
        }

        // Return the item objects
        return response()->json($itemObjects);
    }




    public function destroy($id)
{
    // Log the ID
    Log::info("Destroying item with ID: $id");

    // Aquí asumo que estás usando una lista de Redis donde cada elemento es un JSON
    // con 'id' y otros atributos. De lo contrario, ajusta según sea necesario.
    
    $items = Redis::lrange('audios', 0, -1);
    foreach ($items as $item) {
        $decodedItem = json_decode($item, true);

        // Log the decoded item ID
        Log::info("Decoded item ID: " . $decodedItem['id']);

        if ($decodedItem['id'] == $id) {
            Redis::lrem('audios', 1, $item);
            return response()->json(['success' => true]);
        }
    }
    
    return response()->json(['success' => false], 404);
}

}

