<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;

class LocationsController extends Controller
{
    public function findLibraries(Request $request)
    {
        $query = $request->input('search');
        $libraries = Library::where('name', 'LIKE', "%{$query}%")
                            ->orWhere('location', 'LIKE', "%{$query}%")
                            ->get();

        return view('libraries', compact('libraries', 'query'));
    }


    public function searchLocations(Request $request)
    {
        $query = $request->input('location');

        if (!$query) {
            \Log::error('Search error: Location query is empty');
            return response()->json(['error' => 'Location is required.'], 400);
        }

        $apiKey = config('services.google_maps.key');

        if (!$apiKey) {
            \Log::error('Google API Key is missing');
            return response()->json(['error' => 'API Key is missing.'], 500);
        }

        \Log::info("Searching for: $query with API Key: $apiKey");

        try {
            $response = Http::get("https://maps.googleapis.com/maps/api/place/textsearch/json", [
                'query' => $query,
                'key' => $apiKey
            ]);

            $data = $response->json();

            if (!isset($data['results']) || empty($data['results'])) {
                \Log::error('No locations found from API response');
                return response()->json(['error' => 'No locations found.'], 404);
            }

            return response()->json($data['results']);

        } catch (\Exception $e) {
            \Log::error('Google API Request Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch data from Google API.'], 500);
        }
    
    }


    public function addToFavorites(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'contact_number' => 'nullable|json',
            'opening_hours' => 'nullable|json',
            'website' => 'nullable|string',
            'type' => 'required|string',
        ]);

        try {
            Location::create($validatedData);
            return response()->json(['success' => true, 'message' => 'Location added to favorites.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add location.', 'error' => $e->getMessage()]);
        }
    }
}
