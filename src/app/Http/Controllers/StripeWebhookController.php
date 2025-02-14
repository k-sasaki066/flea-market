<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Models\Item;
use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\KonbiniPaymentMail;
use App\Mail\KonbiniPaymentSuccessMail;
use App\Mail\OrderConfirmationMail;
use Stripe\PaymentIntent;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($request->getContent(), $sig_header, $endpoint_secret);
            Log::info('✅ Webhook 受信:', ['type' => $event->type]);

            // カード決済完了 & コンビニ支払い手順メール送信
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                // payment_intentを取得
                if (!empty($session->payment_intent)) {
                    $paymentIntent = PaymentIntent::retrieve($session->payment_intent);
                    // 支払い方法を取得
                    $paymentMethodType = $paymentIntent->payment_method_types[0] ?? null;

                    Log::info("📌 使用された支払い方法: " . $paymentMethodType);
                }

                Purchase::create([
                    'user_id' => $session['metadata']['user_id'],
                    'item_id' => $session['metadata']['item_id'],
                    'payment_id' => ($paymentMethodType == 'card' ? 2 : 1),
                    'post_cord' => $session['metadata']['post_cord'],
                    'address' => $session['metadata']['address'],
                    'building' => $session['metadata']['building'],
                    'stripe_session_id' => $session['id'],
                    'payment_status' => ($paymentMethodType == 'card' ? 'paid' : 'pending'),
                ]);

                Item::find($session['metadata']['item_id'])->update([
                    'status' => 2,
                ]);

                $data = [
                    'name' => $session->customer_details->name,
                    'item' => $session['metadata']['item_name'],
                    'price' => $session->amount_total,
                    'address' => $session['metadata']['address'],
                    'building' => $session['metadata']['building'],
                    'post_cord' => $session['metadata']['post_cord'],
                    'payment_method' => ($paymentMethodType == 'card') ? 'クレジットカード決済' : 'コンビニ決済',
                ];

                Mail::to($session->customer_details->email)->send(new OrderConfirmationMail($data));

                Log::info("📩 注文確認メールを送信しました: ", $data);

                // コンビニ決済処理
                if($paymentMethodType == 'konbini') {
                    $data = [];
                    $paymentDeadline = Carbon::now()->addDays(7)->format('Y年m月d日');

                    // `hosted_voucher_url`（支払い手順ページ）を取得
                    $hostedVoucherUrl = $paymentIntent->next_action->konbini_display_details->hosted_voucher_url ?? null;

                    // メール送信用データ
                    $data = [
                        'name' => $session->customer_details->name,
                        'item' => $session['metadata']['item_name'],
                        'price' => $session->amount_total,
                        'voucher_url' => $hostedVoucherUrl,
                        'payment_deadline' => $paymentDeadline,
                    ];
                    Log::info('📩 送信するメールデータ:', $data);

                    // 支払い手順メールを送信
                    Mail::to($session->customer_details->email)->send(new KonbiniPaymentMail($data));
                }
            }

            // コンビニ決済完了時の処理
            if ($event->type === 'checkout.session.async_payment_succeeded') {
                $session = $event->data->object;
                $updated = Purchase::where('stripe_session_id', $session['id'])->update([
                    'payment_status' => 'paid'
                ]);

                $data = [
                    'name' => $session->customer_details->name,
                    'item' => $session['metadata']['item_name'],
                    'price' => $session->amount_total,
                ];

                Mail::to($session->customer_details->email)->send(new KonbiniPaymentSuccessMail($data));

                Log::info("📩 コンビニ決済完了メールを送信しました: ", $data);

                if ($updated) {
                    Log::info("✅ コンビニ支払い完了: ", ['session_id' => $session->id]);
                } else {
                    Log::error("❌ 購入データが見つかりませんでした。Session ID: " . $session->id);
                }
            }
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('❌ Webhook 処理エラー:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
}
