<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Item;
use App\Models\User;

class PurchaseController extends Controller
{
    public function getPurchase($item_id) {
        $payments = Payment::all();
        $item = Item::find($item_id);
        $user = User::select('id', 'post_cord', 'address', 'building')->find(Auth::id());

        return view('purchase', compact('payments', 'item', 'user'));
    }

    public function getAddress($item_id) {

        return view('address');
    }
}
