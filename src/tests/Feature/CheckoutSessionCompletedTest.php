<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use Stripe\Stripe;
use Mockery;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class CheckoutSessionCompletedTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_テストデータが正しく作成されたか()
    {
        $this->assertDatabaseCount('users', 5);
        $this->assertDatabaseCount('purchases', 0);


        $this->assertDatabaseCount('items', 10);
        $this->assertDatabaseHas('items', [
            'name' => '腕時計',
            'price' => 15000,
        ]);

        $this->assertDatabaseCount('conditions', 4);
        $this->assertDatabaseHas('conditions', [
            'name' => '良好',
        ]);

        $this->assertDatabaseCount('categories', 14);
        $this->assertDatabaseHas('categories', [
            'name' => 'ファッション',
        ]);

        $this->assertDatabaseCount('brands', 7);
        $this->assertDatabaseHas('brands', [
            'name' => 'EMPORIO-AMANI',
        ]);
    }

    public function test_checkout_session_completed_を受信すると購入が正常に完了する()
    {
        $user = User::factory()->create();
        $item = Item::where('status', 1)->first();

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $mockSession = Mockery::mock('alias:' . Session::class);
        $mockSession->shouldReceive('create')->once()->andReturn((object) ['url' => 'https://checkout.stripe.com']);
        $paymentId = Payment::where('way', 'コンビニ払い')->value('id');

        // 配送先を指定
        $purchaseData = [
            'payment_id' => $paymentId,
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ];

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", $purchaseData);

        $response->assertRedirect('https://checkout.stripe.com');

        $mockPaymentIntent = Mockery::mock('alias:' . PaymentIntent::class);
        $mockPaymentIntent->shouldReceive('retrieve')
            ->with('pi_test_123')
            ->andReturn((object) [
                'status' => 'succeeded',
            ]);

        $webhookPayload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'test_session_123',
                    'payment_intent' => 'pi_test_123',
                    'payment_method_types' => ['konbini'],
                    'amount_total' => $item->price,
                    'metadata' => [
                        'user_id' => $user->id,
                        'item_id' => $item->id,
                        'item_name' => $item->name,
                        'seller_nickname' => $item->user->nickname,
                        'seller_email' => $item->user->email,
                        'purchaser_nickname' => $user->nickname,
                        'address' => $purchaseData['address'],
                        'post_cord' => $purchaseData['post_cord'],
                        'building' => $purchaseData['building'],
                    ],
                    'customer_details' => [
                        'email' => $user->email,
                        'name' => $user->name,
                    ],
                    'payment_status' => 'unpaid',
                    'status' => 'complete',
                ],
            ],
        ];
        $secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = json_encode($webhookPayload, JSON_UNESCAPED_SLASHES);
        $timestamp = time();
        $signedPayload = "{$timestamp}.{$payload}";
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        $signatureHeader = "t={$timestamp},v1={$signature}";

        $response = $this->postJson('/webhook/stripe', $webhookPayload, [
            'Stripe-Signature' => $signatureHeader,
        ]);
        $response->assertStatus(200);

        // 「購入する」ボタンを押下すると購入が完了する
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 2,
        ]);

        // 購入した商品に送付先住所が紐づいて登録される
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'stripe_session_id' => 'test_session_123',
            'payment_status' => 'pending',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        // 商品一覧画面にて「sold」と表示される
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('items');
        $items = $response->viewData('items');
        $response->assertSee('Sold');
        foreach ($items->where('id', '!=', $item->id) as $availableItem) {
            $response->assertDontSee('<p class="sold-out bold">Sold</p>');
        }

        // 「プロフィール/購入した商品一覧」に追加されている
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);

        $response->assertSee('Sold');
        $response->assertViewHas('items', function ($items) use ($user){
            return $items->count() === 1 && $items->every(fn ($item) => $item->purchase->user_id === $user->id);
        });
    }
}