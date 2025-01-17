<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    public function index(Request $request) {
        $items = Item::getItems();
        $parameter = Item::getParameter($request);
        if($request['page'] == 'suggest') {
            $items = Item::searchSuggestItems();
        }elseif($request['page'] == 'mylist') {
            $items = Item::getItems();
        }

        return view('index', compact('items', 'parameter'));
    }

    public function searchItem(Request $request) {
        $items = Item::searchItems($request['keyword']);

        $parameter = Item::getParameter($request);

        return view('index', compact('items', 'parameter'));
    }

    public function getDetail($item_id) {
        $item = Item::getDetail($item_id);

        $categories = unserialize($item['category']);
        foreach($categories as $value) {
            $name = Category::find($value);
            $category[] = $name['name'];
        }

        return view('detail', compact('item', 'category'));
    }
}
