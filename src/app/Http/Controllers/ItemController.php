<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request) {
        $items = Item::getItems();
        $parameter = Item::getParameter($request);
        if($request['page'] == 'suggest') {
            $items = Item::searchSuggestItems();
        }elseif($request['page'] == 'mylist') {
            $items = Item::getFavoriteItems();
        }

        return view('index', compact('items', 'parameter'));
    }

    public function searchItem(Request $request) {
        $items = Item::searchItems($request['keyword']);

        $parameter = Item::getParameter($request);

        return view('index', compact('items', 'parameter'));
    }

    public function getDetail($item_id) {
        $item = Item::getDetailItem($item_id);
        $favorite = $item->favorites()->where('user_id', Auth::id())->first();

        $categories = unserialize($item['category']);
        foreach($categories as $value) {
            $name = Category::find($value);
            $category[] = $name['name'];
        }

        return view('detail', compact('item', 'category', 'favorite'));
    }
}
