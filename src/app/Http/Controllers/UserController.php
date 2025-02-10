<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Brand;
use Carbon\Carbon;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;

class UserController extends Controller
{
    public function getProfile() {
        $user = User::find(Auth::id());
        return view('profile', compact('user'));
    }

    public function postProfile(Request $request) {
        $addressValidated = app(AddressRequest::class)->validated();
        $profileValidated = app(ProfileRequest::class)->validated();

        if($request->file('image_url')) {
            $image_url = Item::getImageUrl($request->file('image_url'));

            User::find(Auth::id())->update([
            'nickname' => $request->nickname,
            'post_cord' => $request->post_cord,
            'address' => $request->address,
            'building' => $request->building,
            'image_url' => $image_url,
            'profile_completed' => true,
            ]);
        }else {
            User::find(Auth::id())->update([
            'nickname' => $request->nickname,
            'post_cord' => $request->post_cord,
            'address' => $request->address,
            'building' => $request->building,
            'profile_completed' => true,
            ]);
        }

        return redirect('/mypage')->with('result', 'プロフィールが更新されました');
    }

    public function getMypage(Request $request) {
        $user = User::find(Auth::id(), ['nickname','image_url']);

        switch ($request['tab']) {
            case 'sell':
                $items = Item::getSellItems();
                break;
            case 'buy':
                $items = Item::getBuyItems();
                break;
            default:
                $items = [];
        }
        $parameter = Item::getParameter($request);

        return view('mypage', compact('user', 'items', 'parameter'));
    }

    public function getSell() {
        $categories = Category::getCategories();
        $conditions = Condition::getConditions();

        return view('sell', compact('categories','conditions'));
    }

    public function postSell(ExhibitionRequest $request) {
        $image_url = Item::getImageUrl($request->file('image_url'));

        Item::create([
            'user_id' => Auth::id(),
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'image_url' => $image_url,
            'category' => serialize($request->category),
            'description' => $request->description,
            'price' => $request->price,
            'status' => '1',
        ]);

        if($request->brand_name) {
            $item_id = Item::where('image_url', $image_url)->where('user_id', Auth::id())->first()->id;
            Brand::create([
                'item_id' => $item_id,
                'name' => $request->brand_name,
            ]);
        }

        return redirect('/mypage')->with('result', '商品を出品しました');
    }
}
