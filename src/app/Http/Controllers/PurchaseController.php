<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class PurchaseController extends Controller
{
    public function getPurchase($item_id) {
        $payments = Payment::getPayments();
        $item = Item::getPaymentItem($item_id);
        $user = User::getPaymentUser();
        $address = '';

        return view('purchase', compact('payments', 'item', 'user', 'address'));
    }

    public function getAddress($item_id) {
        $user = Auth::user();

        if (!$user->profile_completed) {
            return redirect('/mypage/profile')->with('error', '商品を購入するにはプロフィールを設定してください');
        }

        return view('address', compact('item_id'));
    }

    public function postAddress(AddressRequest $request,$item_id) {
        $payments = Payment::getPayments();
        $item = Item::getPaymentItem($item_id);
        $user = User::getPaymentUser();
        $address = $request->all();

        return view('purchase', compact('payments', 'item', 'user', 'address'));
    }

    public function postPurchase(PurchaseRequest $request, $item_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $item = Item::getPaymentItem($item_id);
        $user = Auth::user();

        try {
            $session = Session::create([
                'payment_method_types' => ['card', 'konbini'],
                'payment_method_options' => [
                    'konbini' => [
                    'expires_after_days' => 7,
                    ],
                ],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => [
                    'capture_method' => 'automatic',
                ],
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'item_id' => $item_id,
                    'post_cord' => $request->post_cord,
                    'address' => $request->address,
                    'building' => $request->building,
                    'item_name' => $item->name,
                    'seller_nickname' => $item->user->nickname,
                    'seller_email' => $item->user->email,
                    'purchaser_nickname' => $user->nickname,
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', '決済セッションの作成に失敗しました。');
        }
    }

    public function getSessionStatus(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');
        $session = Session::retrieve($sessionId);
        $paymentMethodType = $session->payment_method_types[0] ?? null;

        return response()->json([
            'purchase_error' => $session->metadata->purchase_error ?? null,
            'item_id' => $session->metadata->item_id ?? null,
            'payment_method' => $paymentMethodType,
        ]);
    }

    public function success(Request $request)
    {
        return view('stripe.success');
    }

    public function cancel()
    {
        return view('stripe.cancel');
    }
}
