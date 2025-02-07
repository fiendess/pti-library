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

   public function addToFavorites(Request $request)
{
    try {
        $request->validate([
            'place_id' => 'required|string',
        ]);

        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Simpan API Key di .env
        $placeId = $request->place_id;

        // Panggil Google Places API untuk mendapatkan detail lokasi
        $response = Http::get("https://maps.googleapis.com/maps/api/place/details/json", [
            'place_id' => $placeId,
            'key' => $apiKey,
            'fields' => 'name,formatted_address,geometry,formatted_phone_number,opening_hours,website,types',
        ]);

        $data = $response->json();

        // Jika status API bukan 'OK', tampilkan error
        if ($data['status'] !== 'OK') {
            return response()->json(['success' => false, 'message' => 'Google API Error: ' . $data['status']], 500);
        }

        $place = $data['result'];

        // Cek apakah lokasi sudah ada di database
        $existingLocation = Location::where('name', $place['name'])
            ->where('address', $place['formatted_address'])
            ->first();

        if ($existingLocation) {
            return response()->json(['success' => false, 'message' => 'Location already exists'], 409);
        }

        // Simpan ke database dengan data dari API
        Location::create([
            'name' => $place['name'], // Ambil dari Google API
            'address' => $place['formatted_address'],
            'latitude' => $place['geometry']['location']['lat'],
            'longitude' => $place['geometry']['location']['lng'],
            'contact_number' => $place['formatted_phone_number'] ?? null, // Simpan NULL jika tidak tersedia
            'opening_hours' => isset($place['opening_hours']['weekday_text']) 
                ? json_encode($place['opening_hours']['weekday_text']) // Simpan dalam format JSON
                : null, // Simpan NULL jika tidak tersedia
            'website' => $place['website'] ?? null, // Simpan NULL jika tidak tersedia
            'type' => isset($place['types'][0]) ? ucfirst($place['types'][0]) : 'Library',
        ]);

        return response()->json(['success' => true, 'message' => 'Added to favorites successfully!']);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}



}
