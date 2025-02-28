<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Brand;
use App\Models\Purchase;
use Mockery;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    protected $item;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $condition = Condition::create([
            'name' => '良好',
        ]);

        Category::create(['name' => 'ファッション']);

        Brand::create(['id' => 1, 'name' => 'テストブランド']);

        $categoryIds = Category::pluck('id')->take(1)->toArray();
        $this->item = Item::create([
            'user_id' => $this->user->id,
            'condition_id' => Condition::first()->id,
            'brand_id' => Brand::first()->id,
            'name' => 'テスト商品',
            'image_url' => '/images/test.jpg',
            'category' => serialize($categoryIds),
            'description' => 'これはテスト用の商品です。',
            'price' => 5000,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_テストデータが正しく作成されたか()
    {
        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseCount('items', 1);
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
        ]);

        $this->assertDatabaseCount('conditions', 1);
        $this->assertDatabaseHas('conditions', [
            'name' => '良好',
        ]);

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseHas('categories', [
            'name' => 'ファッション',
        ]);

        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseHas('brands', [
            'name' => 'テストブランド',
        ]);
    }

    public function test_購入ページで必要な情報が表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");
        $response->assertStatus(200);
        $response->assertViewIs('purchase')
        ->assertViewHas('item')
        ->assertViewHas('user');

        $response->assertSee($this->item->name);
        $response->assertSee(number_format($this->item->price));
        $response->assertSee($user->post_cord);
        $response->assertSee($user->address);
        $response->assertSee($user->building);
    }

    public function test_プロフィールが未設定の状態で配送先変更ボタンをクリックするとプロフィール設定ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);

        $response = $this->actingAs($user)->get("/purchase/address/{$this->item->id}");
        $response->assertStatus(200);

        $response->assertRedirect('/mypage/profile');
        $response->assertSessionHas(['error' => '商品を購入するにはプロフィールを設定してください']);
    }

    public function test_配送先を未選択場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");
        $response->assertStatus(200);

        $response = $this->post("/purchase/{$this->item->id}", [
            'payment_id' => '',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['payment_id' => '支払い方法を選択してください']);
    }

    public function test_郵便番号が未入力場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");
        $response->assertStatus(200);

        $response = $this->post("/purchase/{$this->item->id}", [
            'payment_id' => 1,
            'post_cord' => '',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['post_cord' => '郵便番号を設定してください']);
    }

    public function test_住所が未入力場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");
        $response->assertStatus(200);

        $response = $this->post("/purchase/{$this->item->id}", [
            'payment_id' => 1,
            'post_cord' => '123-4567',
            'address' => '',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['address' => '住所を設定してください']);
    }

    public function test_正しい情報で購入ボタンをクリックするとstripe決済画面に遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        // Stripe API の `Session::create()` をモック
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $mockSession = Mockery::mock('alias:' . Session::class);
        $mockSession->shouldReceive('create')->once()->andReturn((object) ['url' => 'https://checkout.stripe.com']);

        // 決済リクエストデータ
        $purchaseData = [
            'payment_id' => 1,
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ];

        // 購入リクエスト実行
        $response = $this->actingAs($user)->post("/purchase/{$this->item->id}", $purchaseData);

        // Stripe の決済ページにリダイレクトされるか確認
        $response->assertRedirect('https://checkout.stripe.com');
    }

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        // 購入画面を開くとデフォルトの情報が表示
        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");
        $response->assertStatus(200);

        $response->assertSee($user->post_cord);
        $response->assertSee($user->address);
        $response->assertSee($user->building);

        // 配送先設定
        $response = $this->post("/purchase/address/{$this->item->id}", [
            'post_cord' => '987-6543',
            'address' => '岩手県テスト市テスト町1-1',
            'building' => '',
        ]);

        // 購入ページに設定した住所が表示される
        $response = $this->get("/purchase/address/{$this->item->id}");
        $response->assertStatus(200);

        $response->assertSee('987-6543');
        $response->assertSee('岩手県テスト市テスト町1-1');
    }
}
