<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

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
        // dd($param);

        return view('index', compact('items', 'parameter'));
    }

    public function searchItem(Request $request) {
        $items = Item::searchItems($request['keyword']);

        $parameter = Item::getParameter($request);

        return view('index', compact('items', 'parameter'));
    }
}
