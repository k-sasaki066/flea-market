<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Item;
use App\Models\User;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function getPurchase($item_id) {
        $payments = Payment::getPayments();
        $item = Item::getPaymentItem($item_id);
        $user = User::getPaymentUser();
        $address = '';

        return view('purchase', compact('payments', 'item', 'user', 'address'));
    }

    public function postPurchase(PurchaseRequest $request, $item_id) {

        return view('purchase');
    }

    public function getAddress($item_id) {

        return view('address', compact('item_id'));
    }

    public function postAddress(AddressRequest $request,$item_id) {
        $payments = Payment::getPayments();
        $item = Item::getPaymentItem($item_id);
        $user = User::getPaymentUser();
        $address = $request->all();

        return view('purchase', compact('payments', 'item', 'user', 'address'));
    }
}
