<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Favorite;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'image_url',
        'category',
        'description',
        'price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function brand()
    {
        return $this->hasOne(Brand::class);
    }

    public function favorites() {

        return $this->hasMany(Favorite::class);
    }

    public function comments() {
        return $this->belongsToMany(User::class, 'comments')->withPivot('comment');
    }

    public function purchase() {
        return $this->hasOne(Purchase::class);
    }

    public static function getItems()
    {
        $keyword = session('search_keyword');
        $query = Item::with('condition');

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $items = $query->orderBy('created_at', 'desc')->get();

        return $items;
    }

    public static function searchItems($keyword)
    {
        $query = Item::query();

        if (!empty($keyword)) {
            $query->where('user_id', '!=', Auth::id())->where('name', 'like', "%{$keyword}%");
        }

        $items = $query->with('condition')->get();

        return $items;
    }

    public static function searchSuggestItems()
    {
        $userId = Auth::id();

        $likedCategories = Favorite::where('favorites.user_id', $userId)
            ->join('items', 'favorites.item_id', '=', 'items.id')
            ->select('items.*')
            ->pluck('items.category')
            ->map(function ($category) {
            return unserialize($category);
            })
            ->flatten()
            ->unique();
        
        if($likedCategories->isNotEmpty()){
            $items = Item::where(function ($query) use ($likedCategories) {
                foreach ($likedCategories as $categoryId) {
                    $query->orWhere('category', 'LIKE', '%"'.$categoryId.'"%');
                }
            })
            ->whereDoesntHave('favorites', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('items.user_id', '!=', $userId)
            ->where('status', '1')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        }else {
            $items = Item::where('items.user_id', '!=', $userId)->where('condition_id', '1')->where('status', '1')->get();
        }


        return $items;
    }

    public static function getFavoriteItems()
    {
        $keyword = session('search_keyword');

        $query = Item::select('items.*')
        ->join('favorites', 'items.id', '=', 'favorites.item_id')
        ->where('favorites.user_id', Auth::id())
        ->orderBy('favorites.created_at', 'desc')
        ->with('favorites');

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $items = $query->get();

        return $items;
    }

    public static function getParameter($request)
    {
        $parameter = $request->input('tab');

        return $parameter;
    }

    public static function getDetailItem($item_id)
    {
        $item = Item::withCount('favorites', 'comments')
        ->with('condition', 'brand', 'favorites', 'comments')
        ->find($item_id);

        return $item;
    }

    public static function getSellItems()
    {
        $items = Item::where('user_id', Auth::id())->get();

        return $items;
    }

    public static function getBuyItems()
    {
        $userId = Auth::id();
        $items = Item::select('items.*')
        ->join('purchases', 'items.id', '=', 'purchases.item_id')
        ->where('purchases.user_id', $userId)
        ->orderBy('purchases.created_at', 'desc')
        ->get();

        return $items;
    }

    public static function getImageUrl($request_image)
    {
        $original = $request_image->getClientOriginalName();
        $image_name = Carbon::now()->format('Ymd_His').'_'.$original;
        $request_image->move('storage/images', $image_name);
        $image_url = 'http://localhost/storage/images/'.$image_name;

        return $image_url;
    }

    public static function getPaymentItem($item_id)
    {
        $item = Item::find($item_id);

        return $item;
    }
}
