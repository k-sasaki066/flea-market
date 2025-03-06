<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Mail\PurchaseFailedMail;
use Stripe\Stripe;
use Stripe\Refund;
use Mockery;

class CheckoutSessionCompletedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_テストデータが正しく作成されたか()
    {
        $this->assertDatabaseCount('users', 5);

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

    public function test_カード決済が正常に完了する()
    {
        Log::shouldReceive('error')->never();
        // Log::error() が呼ばれないことを明示

        $user = User::factory()->create();
        $item = Item::where('status', 1)->first(); // 未購入の商品

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'test_session_123',
                    'payment_intent' => 'pi_test_123',
                    'payment_method_types' => ['card'],
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'metadata' => [
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'post_cord' => $user->post_cord,
                    'address' => $user->address,
                    'building' => $user->building,
                    'seller_nickname' => $item->user->nickname,
                    'seller_email' => $item->user->email,
                    'purchaser_nickname' => $user->nickname,
                    ],
                    'customer_details' => [
                        'email' => $user->email,
                        'name' => $user->name,
                    ],
                ],
            ],
        ];

        Log::shouldReceive('error')->never();

        // テスト用の Webhook 呼び出し
        $response = $this->actingAs($user)->postJson('/webhook/stripe', $payload,[
            'Stripe-Signature' => 't=123456,v1=fake_signature'
        ]);

        dump($response->json());
        dump($payload);

        // `Purchase` が登録されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'stripe_session_id' => 'test_session_123',
            'payment_status' => 'paid',
        ]);

        // `status` が `2` に更新されたことを確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 2,
        ]);

        Log::shouldHaveReceived('error')->never();
        // エラーログが記録されていないことを確認

        $response->assertStatus(200);
    }
}
