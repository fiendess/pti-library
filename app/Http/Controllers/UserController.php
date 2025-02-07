<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
     // Menampilkan halaman profil
    public function index()
    {
        return view('profile', [
            'user' => Auth::user()
        ]);
    }

    // Update profil pengguna
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

     public function favoritelibraries()
    {
        return view('favorites.libraries');
    }

    public function favoritebooks()
    {
        return view('favorites.books');
    }

    public function addFavoriteLibrary(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'location_id' => 'required|exists:locations,id',
    ]);

    $locationId = $request->location_id;

    if ($user->favoriteLibraries()->where('location_id', $locationId)->exists()) {
        return response()->json(['success' => false, 'message' => 'Library already in favorites']);
    }

    $user->favoriteLibraries()->attach($locationId);

    return response()->json(['success' => true, 'message' => 'Library added to favorites']);
}


    public function getFavorites()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Decode JSON
        $favorites = json_decode($user->favourite_library, true) ?? [];

        return response()->json(['success' => true, 'favorites' => $favorites]);
    }

}
