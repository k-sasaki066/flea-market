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
        'brand_id',
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
        return $this->belongsTo(Brand::class);
    }

    public function favorites() {

        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase() {
        return $this->hasOne(Purchase::class);
    }

    public static function getItems()
    {
        $items = Item::select('items.*')
        ->with('condition')
        ->when(Auth::check(), function ($query) {
            return $query->where('user_id', '!=', Auth::id());
        })
        ->when(session('search_keyword') ?? null, function ($query, $keyword) {
            return $query->where('name', 'like', "%{$keyword}%");
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return $items;
    }

    public static function searchItems($keyword)
    {
        $items = Item::select('items.*')
        ->when(Auth::check(), function ($query) {
            return $query->where('user_id', '!=', Auth::id());
        })
        ->when(!empty($keyword), function ($query) use ($keyword) {
            return $query->where('name', 'like', "%{$keyword}%");
        })
        ->with('condition')
        ->get();

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
            $items = Item::where('items.user_id', '!=', $userId)->where('condition_id', '1')
            ->where('status', '1')
            ->limit(10)
            ->get();
        }


        return $items;
    }

    public static function getFavoriteItems()
    {
        $keyword = session('search_keyword');

        $items = Item::select('items.*')
        ->whereHas('favorites', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%{$keyword}%");
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return $items;
    }

    public static function getParameter($request)
    {
        $allowedPages = ['suggest', 'mylist', 'sell', 'buy', 'default'];
        $parameter = $request->input('page', 'default');

        return in_array($parameter, $allowedPages, true) ? $parameter : 'default';
    }

    public static function getExhibitedItems($userId)
    {
        $items = Item::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

        return $items;
    }

    public static function getPurchasedItems($userId)
    {
        $items = Item::select('items.*')
        ->join('purchases', function ($join) use ($userId) {
            $join->on('items.id', '=', 'purchases.item_id')
                ->where('purchases.user_id', '=', $userId)
                ->where('purchases.payment_status', '!=', 'canceled');
        })
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
}
