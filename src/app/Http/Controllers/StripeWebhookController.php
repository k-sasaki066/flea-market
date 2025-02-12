<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Models\Item;
use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\Exception\SignatureVerificationException;

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

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                Purchase::create([
                    'user_id' => $session['metadata']['user_id'],
                    'item_id' => $session['metadata']['item_id'],
                    'payment_id' => $session['metadata']['payment_id'],
                    'post_cord' => $session['metadata']['post_cord'],
                    'address' => $session['metadata']['address'],
                    'building' => $session['metadata']['building'],
                    'stripe_session_id' => $session['id'],
                    'payment_status' => 'paid'
                ]);

                Item::find($session['metadata']['item_id'])->update([
                    'status' => 2,
                ]);

                Log::info("✅ 購入データを保存しました: ", ['session_id' => $session->id]);
            }
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('❌ Webhook 処理エラー:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
}
