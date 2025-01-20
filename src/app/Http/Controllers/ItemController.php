<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Carbon\Carbon;

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

    public function getProfile() {

        return view('profile');
    }

    public function postProfile(Request $request) {
        $addressValidated = app(AddressRequest::class)->validated();
        $profileValidated = app(ProfileRequest::class)->validated();

        if($request->file('image_url')) {
            $original = $request->file('image_url')->getClientOriginalName();
            $image_name = Carbon::now()->format('Ymd_His').'_'.$original;
            request()->file('image_url')->move('storage/images', $image_name);

            User::find(Auth::id())->update([
            'nickname' => $request->nickname,
            'post_cord' => $request->post_cord,
            'address' => $request->address,
            'building' => $request->building,
            'image_url' => 'http://localhost/storage/images/'.$image_name,
            ]);
        }else {
            User::find(Auth::id())->update([
            'nickname' => $request->nickname,
            'post_cord' => $request->post_cord,
            'address' => $request->address,
            'building' => $request->building,
            ]);
        }

        return redirect('/')->with('result', 'プロフィールを更新しました');
    }
}
