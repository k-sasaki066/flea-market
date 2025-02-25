<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Models\Item;
use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\KonbiniPaymentMail;
use App\Mail\KonbiniPaymentSuccessMail;
use App\Mail\OrderConfirmationMail;
use Stripe\PaymentIntent;
use Carbon\Carbon;
use App\Mail\CardPaymentFailureMail;
use App\Mail\KonbiniPaymentFailureMail;
use App\Mail\SellerNotificationMail;
use App\Mail\ShippingNotificationMail;
use App\Mail\SellerOrderCancelMail;
use App\Mail\PurchaseFailedMail;
use Exception;

class StripeWebhookController extends Controller
{
    private function translateErrorMessage($errorMessage)
    {
        $errorTranslations = [
            "Your card was declined." => "カードが拒否されました。",
            "Your card has insufficient funds." => "カードの残高が不足しています。",
            "Your card has expired." => "カードの有効期限が切れています。",
            "Incorrect CVC code." => "CVC コードが間違っています。",
            "The card's security code is incorrect." => "カードのセキュリティコードが間違っています。",
            "Your card number is incorrect." => "カード番号が間違っています。",
            "This transaction has been declined by your bank." => "銀行により取引が拒否されました。",
        ];

        return $errorTranslations[$errorMessage] ?? "決済エラーが発生しました。別のカードを試すか、カード会社にお問い合わせください。";
    }

    private function orderConfirmationMail($session, $paymentMethodType){
        $data = [
            'purchaser_nickname' => $session['metadata']['purchaser_nickname'],
            'item' => $session['metadata']['item_name'],
            'price' => $session->amount_total,
            'address' => $session['metadata']['address'],
            'building' => $session['metadata']['building'],
            'post_cord' => $session['metadata']['post_cord'],
            'payment_method' => ($paymentMethodType == 'card') ? 'クレジットカード決済' : 'コンビニ決済',
        ];

        Mail::to($session->customer_details->email)->send(new OrderConfirmationMail($data));

        Log::info("📩 購入者へ注文確認メールを送信しました: ", $data);
    }

    // 出品者へ商品が売れた連絡
    private function sellerNotificationMail($session, $paymentMethodType){
        $data = [
            'purchaser_nickname' => $session['metadata']['purchaser_nickname'],
            'item' => $session['metadata']['item_name'],
            'price' => $session->amount_total,
            'payment_method' => ($paymentMethodType == 'card') ? 'クレジットカード決済' : 'コンビニ決済',
            'seller_nickname' => $session['metadata']['seller_nickname'],
        ];

        Mail::to($session['metadata']['seller_email'])->send(new SellerNotificationMail($data));

        Log::info("📩 出品者へメールを送信しました: ", $data);
    }

    // コンビニ支払い手順メール
    private function konbiniPaymentMail($session, $paymentIntent) {
        $expiresAt = Carbon::createFromTimestamp($paymentIntent->next_action->konbini_display_details->expires_at);
        Log::info('✅ 支払い期限:', ['expiresAt' => $expiresAt]);

        $hostedVoucherUrl = $paymentIntent->next_action->konbini_display_details->hosted_voucher_url ?? null;

        $data = [
            'purchaser_nickname' => $session['metadata']['purchaser_nickname'],
            'item' => $session['metadata']['item_name'],
            'price' => $session->amount_total,
            'voucher_url' => $hostedVoucherUrl,
            'expires_at' => $expiresAt,
        ];
        Log::info('📩 購入者へ支払い手順メールを送信しました:', $data);

        Mail::to($session->customer_details->email)->send(new KonbiniPaymentMail($data));
    }

    // コンビニ決済完了メール
    private function konbiniPaymentSuccessMail($session) {
        $data = [
            'purchaser_nickname' => $session['metadata']['purchaser_nickname'],
            'item' => $session['metadata']['item_name'],
            'price' => $session->amount_total,
        ];

        Mail::to($session->customer_details->email)->send(new KonbiniPaymentSuccessMail($data));

        Log::info("📩 コンビニ決済完了メールを送信しました: ", $data);
    }

    // 発送準備メール送信
    private function shippingNotificationMail($session, $paymentMethodType){
        $data = [
            'purchaser_nickname' => $session['metadata']['purchaser_nickname'],
            'item' => $session['metadata']['item_name'],
            'price' => $session->amount_total,
            'address' => $session['metadata']['address'],
            'building' => $session['metadata']['building'],
            'post_cord' => $session['metadata']['post_cord'],
            'seller_nickname' => $session['metadata']['seller_nickname'],
        ];

        Mail::to($session['metadata']['seller_email'])->send(new ShippingNotificationMail($data));

        Log::info("📩 出品者に発送準備メールを送信しました: ", $data);
    }

    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($request->getContent(), $sig_header, $endpoint_secret);
            Log::info('✅ Webhook 受信:', ['type' => $event->type]);

            $session = $event->data->object ?? null;
            $sessionId = $session->id;
            $paymentMethodType = $session->payment_method_types[0] ?? null;

            $data = [];

            // カード決済完了 & コンビニ支払い手順メール送信
            if ($event->type === 'checkout.session.completed') {
                DB::beginTransaction();
                try {
                    // payment_intentを取得
                    $paymentMethodType = null;
                    if (!empty($session->payment_intent)) {
                        try {
                            $paymentIntent = PaymentIntent::retrieve($session->payment_intent);
                            $paymentMethodType = $paymentIntent->payment_method_types[0] ?? null;
                            Log::info("📌 使用された支払い方法: " . $paymentMethodType);
                        } catch (Exception $e) {
                            Log::error("❌ PaymentIntent の取得に失敗しました: " . $e->getMessage());
                        }
                    }

                    $metadata = $session->metadata ?? [];
                    $userId = $metadata['user_id'] ?? null;
                    $itemId = $metadata['item_id'] ?? null;
                    $postCord = $metadata['post_cord'] ?? '';
                    $address = $metadata['address'] ?? '';
                    $building = $metadata['building'] ?? '';

                    // `status=1` の場合のみ `status=2` に更新（アトミックロック）
                    // update の際に status = 1（未購入）を status = 2（購入済み）に 「同時に」変更できた場合のみ成功 させる
                    $updated = Item::where('id', $itemId)
                        ->where('status', 1) // 未購入状態を確認
                        ->update(['status' => 2]);
                        Log::info('✅ 商品のステータスを購入済みに更新しました', ['item_id' => $itemId, 'session_id' => $session->id]);
                    
                    // 他のユーザーが先に購入していた場合（更新なし)
                    if ($updated === 0) {
                        Log::warning("❌ 商品が既に購入済みです", ['item_id' => $itemId]);

                        DB::rollBack();
                        Session::update($session->id, [
                            'metadata' => ['purchase_error' => 'already_sold']
                        ]);

                        // 購入者へ「商品が購入済み」の通知メールを送信
                        $data = [
                            'purchaser_nickname' => $metadata['purchaser_nickname'] ?? 'お客',
                            'item_name' => $metadata['item_name'] ?? '商品',
                        ];
                        Mail::to($session->customer_details->email)->send(new PurchaseFailedMail($data));

                        Log::info("📩 購入失敗メールを送信しました: ", ['email' => $session->customer_details->email, 'data' => $data]);

                        // Stripe でカード決済が完了していた場合は返金処理
                        if ($paymentIntent && $paymentMethodType == 'card') {
                            Stripe::setApiKey(env('STRIPE_SECRET'));

                            try {
                                $refund = Refund::create([
                                    'payment_intent' => $paymentIntent,
                                ]);
                                Log::info("✅ 返金処理完了: ", ['payment_intent' => $paymentIntent, 'refund_id' => $refund->id]);
                            } catch (Exception $e) {
                                Log::error("❌ 返金処理に失敗: " . $e->getMessage(), ['payment_intent' => $paymentIntent]);
                            }
                        }

                        return;
                    }

                    Purchase::create([
                        'user_id' => $userId,
                        'item_id' => $itemId,
                        'payment_id' => ($paymentMethodType == 'card' ? 2 : 1),
                        'post_cord' => $postCord,
                        'address' => $address,
                        'building' => $building,
                        'stripe_session_id' => $session['id'],
                        'payment_status' => ($paymentMethodType == 'card' ? 'paid' : 'pending'),
                    ]);

                    DB::commit();
                    Log::info("✅ 商品購入完了", ['item_id' => $itemId, 'user_id' => $userId]);

                    $this->orderConfirmationMail($session, $paymentMethodType);

                    $this->sellerNotificationMail($session, $paymentMethodType);

                    // コンビニ決済処理
                    if($paymentMethodType == 'konbini') {
                        $this->konbiniPaymentMail($session, $paymentIntent);
                    }

                    // カード決済完了時
                    if($paymentMethodType == 'card') {
                        $this->shippingNotificationMail($session, $paymentMethodType);
                    }

                } catch (Exception $e) {
                    Log::error("❌ checkout.session.completed の処理中にエラーが発生しました: " . $e->getMessage());
                }
            }

            // コンビニ決済完了時の処理
            if ($event->type === 'checkout.session.async_payment_succeeded') {
                $updated = Purchase::where('stripe_session_id', $session['id'])->first();

                if ($updated) {
                    Log::info("✅ コンビニ支払い完了: ", ['session_id' => $session->id]);
                    $updated->update([
                        'payment_status' => 'paid'
                    ]);
                    Log::info('✅ 購入ステータスをpaidに更新しました', ['session_id' => $session->id]);
                    // コンビニ決済完了メール
                    $this->konbiniPaymentSuccessMail($session);
                    // 発送準備メール
                    $this->shippingNotificationMail($session, $paymentMethodType);
                } else {
                    Log::error("❌ 購入データが見つかりませんでした。Session ID: " . $session->id);
                }
            }

            // コンビニ決済支払い期限切れ
            if ($event->type === 'checkout.session.async_payment_failed') {
                $expiresAt = Carbon::createFromTimestamp($session->expires_at);

                $purchase = Purchase::with('user')->where('stripe_session_id', $sessionId)->first();

                // テスト(実際にデータベースに登録してあるデータを使用)
                // $purchase = Purchase::where('stripe_session_id', 'cs_test_a1Kd53kl6mZNyeNdFFvEfGPPhQmKz4vRjmeq5A19jskobNJcklCFVQFI5N')->with('user')->first();
                // テストここまで

                Log::error("❌ 非同期決済が失敗しました: ", ['session_id' => $sessionId]);

                if ($purchase) {
                    $purchase->update([
                        'payment_status' => 'canceled',
                    ]);
                    $item = Item::with('user')->find($purchase->item_id);
                    if ($item) {
                        $item->update([
                            'status' => 1,
                        ]);
                        Log::info('✅ 商品のステータスを出品に更新しました', ['item_id' => $purchase->item_id, 'purchase' => $purchase]);

                        // 購入者への決済失敗メール
                        $data = [
                            'purchaser_nickname' => $purchase->user->nickname ?? 'お客',
                            'item' => $item->name ?? '商品',
                            'price' => $session->amount_total,
                            'expires_at' => $expiresAt,
                        ];

                        Mail::to($session->customer_details->email)->send(new KonbiniPaymentFailureMail($data));

                        Log::info("✅ 購入者へコンビニ決済失敗メールを送信しました: ", ['data' => $data]);

                        // 出品者へのキャンセルメール
                        $data = [
                            'seller_nickname' => $item->user->nickname ?? 'お客',
                            'item' => $item->name ?? '商品',
                            'price' => $session->amount_total,
                        ];

                        Mail::to($item->user->email)->send(new SellerOrderCancelMail($data));
                        Log::info("📩 出品者へキャンセルメールを送信しました: ", $data);
                    }

                    Log::info("❌ 注文をキャンセルしました: ", ['session_id' => $sessionId]);
                }
            }

            // 決済が失敗した場合
            if ($event->type === 'payment_intent.payment_failed') {

                Log::info("📌 使用された支払い方法: " . $paymentMethodType);

                if($paymentMethodType == 'card') {
                    $errorMessage = $session->last_payment_error->message ?? "決済に失敗しました。";
                    $translatedError = $this->translateErrorMessage($errorMessage);
                    
                    Log::error("❌ カード決済失敗: ", ['session_id' => $sessionId, 'error' => $translatedError]);

                    if ($errorMessage) {
                        $data = [
                            'name' => $session->last_payment_error->payment_method->billing_details->name,
                            'message' => $translatedError,
                        ];

                        Mail::to($session->last_payment_error->payment_method->billing_details->email)->send(new CardPaymentFailureMail($data));
                        Log::error("📩 決済失敗メールを送信しました: ", ['data' => $data]);
                    }
                }elseif($paymentMethodType == 'konbini') {
                    $errorMessage = $session->last_payment_error->message ?? 'コンビニ決済に失敗しました。';
                    $paymentIntentId = $session->id;
                    
                    Log::error("❌ コンビニ決済エラー（Stripe 側）: ", [
                        'payment_intent_id' => $paymentIntentId,
                        'error_message' => $errorMessage
                    ]);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('❌ Webhook 処理エラー:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
}
