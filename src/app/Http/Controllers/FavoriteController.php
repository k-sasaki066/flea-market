<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function create($item_id)
    {
        $existingFavorite = Favorite::where('item_id', $item_id)
        ->where('user_id', Auth::id())
        ->first();

        if (!$existingFavorite) {
            $favorite = new Favorite();
            $favorite->fill([
                'item_id'=>$item_id,
                'user_id'=>Auth::id(),
            ])->save();
        }

        return response()->json(['message' => 'Liked successfully!']);
    }

    public function delete($item_id)
    {
        $deleted = Favorite::where('user_id', Auth::id())
        ->where('item_id', $item_id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Unliked successfully'], 200);
        } else {
            return response()->json(['message' => 'Already unliked or not found'], 404);
        }
    }

}
