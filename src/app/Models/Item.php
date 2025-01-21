<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public static function getItems()
    {
        $query = Item::with('condition');
        if(Auth::check()) {
            $items = $query->where('user_id', '!=', Auth::id())->get();
        }else {
            $items = $query->get();
        }

        return $items;
    }

    public static function searchItems($keyword)
    {
        $query = Item::query();

        if (!empty($keyword)) {
            $query->where('name', 'like', "%$keyword%");
        }

        $items = $query->with('condition')->get();

        return $items;
    }

    public static function searchSuggestItems()
    {
        $items = Item::where('condition_id', '1')->get();

        return $items;
    }

    public static function getFavoriteItems()
    {
        $items = Item::whereHas('favorites', function ($query) {
            $query->where('user_id', Auth::id());
            })->with(['favorites' => function($query) {
                $query->where('user_id', Auth::id());
        }])->get();

        return $items;
    }

    public static function getParameter($request)
    {
        $parameter = $request->input('page');

        return $parameter;
    }

    public static function getDetail($item_id)
    {
        $joinTable = Item::with('condition', 'brand')->with(['favorites' => function($query) {
                $query->where('user_id', Auth::id());
        }])
        ->leftJoin('favorites', 'items.id', '=', 'favorites.item_id')
        ->select(
            'items.id',
            'items.user_id',
            'condition_id',
            'name',
            'image_url',
            'category',
            'description',
            'price',
            'status',
        )
        ->selectRaw(
            'COUNT(favorites.item_id) as favorite_count'
        )
        ->groupBy(
            'items.id',
            'items.user_id',
            'condition_id',
            'name',
            'image_url',
            'category',
            'description',
            'price',
            'status',
        )
        ->get(['items.*']);

        $item = $joinTable->find($item_id);
        return $item;
    }
}
