<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;

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

        return redirect('/mypage')->with('result', 'プロフィールを更新しました');
    }

    public function getMypage(Request $request) {
        $user = User::find(Auth::id(), ['nickname','image_url']);

        $parameter = Item::getParameter($request);
        if($request['page'] == 'sell') {
            $items = User::getSellItems();
        }elseif($request['page'] == 'buy') {
            $items = Item::searchSuggestItems();
        }else {
            $items = null;
        }

        return view('mypage', compact('user', 'items', 'parameter'));
    }
}
