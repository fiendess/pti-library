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

 

}
