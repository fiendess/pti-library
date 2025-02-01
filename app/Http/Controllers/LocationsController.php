<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;

class LocationsController extends Controller
{
    // Method untuk mencari lokasi berdasarkan input
   public function searchLocations(Request $request)
{
    $query = $request->input('location');

    if (!$query) {
        return response()->json(['error' => 'Location is required.'], 400);
    }

    // Menggunakan Google Places API untuk mencari lokasi perpustakaan
    $apiKey = env('GOOGLE_MAPS_API_KEY');
    $response = Http::get("https://maps.googleapis.com/maps/api/place/textsearch/json", [
        'query' => $query,
        'type' => 'library|bookstore',
        'key' => $apiKey
    ]);

    $data = $response->json();

    if (!isset($data['results'])) {
        return response()->json(['error' => 'No locations found.'], 404);
    }

    return response()->json($data['results']);
}


    // Method untuk mencari lokasi berdasarkan koordinat pengguna
    public function searchNearbyLocations(Request $request)
    {
        $latitude = $request->lat;
        $longitude = $request->lng;
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'location' => "{$latitude},{$longitude}",
            'radius' => 5000,  // Radius pencarian dalam meter (5 km)
            'type' => 'library',  // Mencari tempat dengan jenis 'library'
            'key' => $apiKey,
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['results']);
        }

        return response()->json(['error' => 'Failed to fetch libraries.'], 500);
    }

}
