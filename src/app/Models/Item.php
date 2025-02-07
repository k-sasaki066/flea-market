<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $items = $query->get();

        return $items;
    }

    public static function searchItems($keyword)
    {
        $query = Item::query();

        if (!empty($keyword)) {
            $query->where('user_id', '!=', Auth::id())->where('name', 'like', "%$keyword%");
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
        $keyword = session('search_keyword');
        $query = Item::whereHas('favorites', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('favorites');

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
