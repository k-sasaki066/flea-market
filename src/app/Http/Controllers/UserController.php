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

        switch ($request['page']) {
            case 'sell':
                $items = Item::getExhibitedItems();
                break;
            case 'buy':
                $items = Item::getPurchasedItems();
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
        $brands = Brand::all();

        return view('sell', compact('categories','conditions', 'brands'));
    }

    public function postSell(ExhibitionRequest $request) {
        $user = Auth::user();

        if (!$user->profile_completed) {
            return redirect('/mypage/profile')->with('error', '商品を出品するにはプロフィールを設定してください');
        }

        $image_url = Item::getImageUrl($request->file('image_url'));

        $brand = null;
        if (!empty($request->brand_name)) {
            $brand = Brand::firstOrCreate(['name' => $request->brand_name]);
        }

        Item::create([
            'user_id' => Auth::id(),
            'condition_id' => $request->condition_id,
            'brand_id' => $brand ? $brand->id : null,
            'name' => $request->name,
            'image_url' => $image_url,
            'category' => serialize($request->category),
            'description' => $request->description,
            'price' => $request->price,
            'status' => '1',
        ]);

        return redirect('/mypage')->with('result', '商品を出品しました');
    }

    public function getBrandName(Request $request) {
        $query = $request->query('query');

        $brands = Brand::query();

        if (!empty($query)) {
            $brands->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($query) . "%"]);
        }

        return response()->json($brands->get());
    }
}
