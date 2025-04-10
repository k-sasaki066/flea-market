<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Payment;
use App\Models\Item;
use App\Models\User;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;

class PurchaseController extends Controller
{
    public function getPurchase(Item $item) {
        try {
            $userId = Auth::id();
            $payments = Payment::getPayments();
            $user = User::getPaymentUser($userId);
            $address = [];

            if ($payments->isEmpty()) {
                Log::warning('支払い方法が空です');
            }
            if (!$item) {
                Log::warning('商品情報が取得できませんでした');
            }
            if (!$user) {
                Log::warning('ユーザー情報が取得できませんでした');
            }

            return view('purchase', compact('payments', 'item', 'user', 'address'));
        } catch (QueryException $e) {
            Log::error('❌ データベースエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'データの取得に失敗しました。');
        } catch (\Exception $e) {
            Log::error('❌ 予期しないエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function getAddress(Item $item) {
        try {
            $user = Auth::user();

            if (!$user->profile_completed) {
                return redirect('/mypage/profile')->with('error', '商品を購入するにはプロフィールを設定してください');
            }

            return view('address', compact('item', 'user'));
        } catch (\Exception $e) {
            Log::error("❌ 予期しないエラー: " . $e->getMessage());
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function postAddress(AddressRequest $request, Item $item) {
        try {
            $userId = Auth::id();
            $payments = Payment::getPayments();
            $user = User::getPaymentUser($userId);
            $address = array_merge($request->validated(), [
                'building' => $request->building ?? null,
            ]);

            if ($payments->isEmpty()) {
                Log::warning('支払い方法が空です');
            }
            if (!$item) {
                Log::warning('商品情報が取得できませんでした');
            }
            if (!$user) {
                Log::warning('ユーザー情報が取得できませんでした');
            }

            return view('purchase', compact('payments', 'item', 'user', 'address'));
        } catch (QueryException $e) {
            Log::error('❌ データベースエラー:', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'データの取得に失敗しました。');
        }catch (\Exception $e) {
            Log::error("❌ 予期しないエラー: " . $e->getMessage());
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
        }
    }

    public function postPurchase(PurchaseRequest $request, Item $item)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $item->load('user');

            $user = Auth::user();
            if (!$user) {
                throw new Exception("認証ユーザーが見つかりません");
            }

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
                    'item_id' => $item->id,
                    'post_cord' => $request->post_cord,
                    'address' => $request->address,
                    'building' => $request->building,
                    'item_name' => $item->name,
                    'seller_id' => $item->user_id,
                    'seller_nickname' => $item->user->nickname,
                    'seller_email' => $item->user->email,
                    'purchaser_nickname' => $user->nickname,
                ],
            ]);

            return redirect($session->url);
        } catch (InvalidRequestException $e) {
            Log::error("❌ Stripe API のリクエストエラー: " . $e->getMessage());
            return redirect('/')->with('error', '決済情報が不正です。管理者にお問い合わせください。');
        } catch (AuthenticationException $e) {
            Log::error("❌ Stripe API 認証エラー: " . $e->getMessage());
            return redirect('/')->with('error', '決済処理ができません。（認証エラー）');
        } catch (ApiConnectionException $e) {
            Log::error("❌ Stripe API への接続エラー: " . $e->getMessage());
            return redirect('/')->with('error', '決済処理に失敗しました。ネットワークを確認してください。');
        } catch (\Exception $e) {
            Log::error("❌ 予期しないエラー: " . $e->getMessage());
            return redirect('/')->with('error', '予期しないエラーが発生しました。');
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