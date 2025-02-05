<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;



class RegisterController extends Controller
{
      public function index()
    {
        return view('/register', [
            'title' => 'Register'
        ]);
    }

    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:8|confirmed',
            
        ]);

        
        user::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);


        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}
