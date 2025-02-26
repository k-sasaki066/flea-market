<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;

class FavoriteController extends Controller
{
    public function createFavorite($itemId)
    {
        try {
            $userId = Auth::id();
            $existingFavorite = Favorite::where('item_id', $itemId)
            ->where('user_id', $userId)
            ->first();

            if (!$existingFavorite) {
                DB::beginTransaction();
                $favorite = new Favorite();
                $favorite->fill([
                    'item_id'=>$itemId,
                    'user_id'=>$userId,
                ])->save();

                DB::commit();

                return response()->json(['message' => 'Liked successfully!']);
            }

            return response()->json(['message' => 'Already liked!'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('❌ データベースエラー: ' . $e->getMessage());
            return response()->json(['error' => 'データベースエラーが発生しました'], 500);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('❌ 予期しないエラー: ' . $e->getMessage());
            return response()->json(['error' => '予期しないエラーが発生しました'], 500);
        }
    }

    public function deleteFavorite($itemId)
    {
        try {
            $userId = Auth::id();
            $favorite = Favorite::where('user_id', $userId)
                ->where('item_id', $itemId)
                ->first();

            if (!$favorite) {
                return response()->json(['message' => 'Already unliked or not found'], 404);
            }

            $favorite->delete();

            return response()->json(['message' => 'Unliked successfully'], 200);
        } catch (QueryException $e) {
            Log::error('❌ データベースエラー:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Database error'], 500);
        } catch (Exception $e) {
            Log::error('❌ 予期しないエラー:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An unexpected error occurred'], 500);
        }
    }

}
