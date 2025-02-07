<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request) {
        $parameter = Item::getParameter($request);

        switch ($request['tab']) {
            case 'suggest':
                $items = Item::searchSuggestItems();
                break;
            case 'mylist':
                $items = Item::getFavoriteItems();
                break;
            default:
                $items = Item::getItems();
        }

        return view('index', compact('items', 'parameter'));
    }

    public function searchItem(Request $request) {
        $keyword = $request->input('keyword');

        if ($keyword) {
            session(['search_keyword' => $keyword]);
        } else {
            session()->forget('search_keyword');
        }
        $items = Item::searchItems($keyword);

        $parameter = Item::getParameter($request);

        return redirect()->back()->with((['items'=>$items, 'parameter'=>$parameter]));
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

    public function postComment(CommentRequest $request, $item_id) {
        Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item_id,
        'comment' => $request->comment,
        ]);

        return redirect()->back()->with('result', 'コメントを送信しました');
    }
}
