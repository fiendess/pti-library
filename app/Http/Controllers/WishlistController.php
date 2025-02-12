<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'You must be logged in to add to wishlist.'], 401);
        }

        $request->validate([
            'book_id' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $bookId = $request->book_id;

        $exists = Wishlist::where('user_id', $user->id)->where('book_id', $bookId)->exists();
        if ($exists) {
            return response()->json(['message' => 'Book already in wishlist.'], 409);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'book_id' => $bookId,
        ]);

        return response()->json(['message' => 'Book added to wishlist.'], 200);
    }

    public function getWishlist()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $wishlist = Wishlist::where('user_id', Auth::id())->get();
        return view('wishlist', compact('wishlist'));
    }

    public function removeFromWishlist($id)
    {
        $wishlistItem = Wishlist::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$wishlistItem) {
            return redirect()->route('wishlist.get')->with('error', 'Book not found in wishlist.');
        }

        $wishlistItem->delete();
        return redirect()->route('wishlist.get')->with('success', 'Book removed from wishlist.');
    }

}
