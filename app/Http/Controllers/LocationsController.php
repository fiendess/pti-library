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
        $request->validate([
            'location' => 'required|string',
        ]);

        $location = $request->location;
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/place/textsearch/json", [
            'query' => $location,
            'key' => $apiKey,
        ]);

        if ($response->successful()) {
            $locations = $response->json()['results'];
            return view('locations.index', compact('locations'));
        }

        return back()->with('error', 'Failed to fetch locations.');
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
