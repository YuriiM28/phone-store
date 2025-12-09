<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favorites = Auth::user()->favoritePhones()->with(['brand', 'category'])->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request, Phone $phone)
    {
        $existingFavorite = Favorite::where('user_id', Auth::id())
            ->where('phone_id', $phone->id)
            ->first();

        if ($existingFavorite) {
            return response()->json(['error' => 'Товар уже в избранном'], 422);
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'phone_id' => $phone->id,
        ]);

        return response()->json(['success' => 'Товар добавлен в избранное']);
    }

    public function destroy(Phone $phone)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('phone_id', $phone->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => 'Товар удален из избранного']);
        }

        return response()->json(['error' => 'Товар не найден в избранном'], 404);
    }

    public function toggle(Request $request, Phone $phone)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('phone_id', $phone->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
            $message = 'Товар удален из избранного';
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'phone_id' => $phone->id,
            ]);
            $isFavorited = true;
            $message = 'Товар добавлен в избранное';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'message' => $message,
                'favorites_count' => Auth::user()->favorites()->count()
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}