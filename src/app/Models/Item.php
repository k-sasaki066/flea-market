<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getItems()
    {
        $items = Item::with('condition')->get();

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

    public static function getParameter($request)
    {
        $parameter = $request->input('page');

        return $parameter;
    }

    public static function getDetail($item_id)
    {
        $item = Item::with('condition', 'brand')->find($item_id);

        return $item;
    }
}
