<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト101号',
            'profile_completed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $condition = Condition::create([
            'name' => 'テスト状態',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $item = Item::create([
            'user_id' => User::first()->id,
            'condition_id' => Condition::first()->id,
            'brand_id' => null,
            'name' => 'テスト商品',
            'image_url' => '/images/test.jpg',
            'category' => serialize([1]),
            'description' => 'これはテスト用の商品です。',
            'price' => 5000,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->item = Item::first();
    }

    public function test_テストデータが正しく作成されたか()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('conditions', [
            'name' => 'テスト状態',
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'price' => 5000,
        ]);
    }

    public function test_未認証ユーザーは会員登録ページにアクセスできる()
    {
        $this->assertGuest();
        $response = $this->get('/register');

        $response->assertStatus(200)->assertViewIs('auth.register');
    }

    public function 認証済みユーザーは会員登録ページにアクセスするとマイページにリダイレクトされる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect('/mypage');
    }

    public function test_未認証ユーザーはログインページにアクセスできる()
    {
        $this->assertGuest();
        $response = $this->get('/login');

        $response->assertStatus(200)->assertViewIs('auth.login');
    }

    public function 認証済みユーザーはログインページにアクセスするとマイページにリダイレクトされる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/mypage');
    }

    public function test_未認証ユーザーがメール認証ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $response = $this->get('/email/verify');

        $response->assertRedirect('/login');
    }

    public function test_メール認証未完了ユーザーがメール認証ページにアクセスできる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify_email');
    }

    public function test_認証済みユーザーがメール認証ページにアクセスするとマイページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertRedirect('/mypage');
    }
    
    public function test_すべてのユーザーがトップページにアクセスできる()
    {
        $response = $this->get('/');

        $response->assertStatus(200)->assertViewIs('index');
    }

    public function test_未認証ユーザーは商品詳細ページにアクセスできる()
    {
        $this->assertGuest();
        $this->assertNotNull($this->item, 'Itemデータが正しく作成されていません');
        $response = $this->get("/item/{$this->item->id}");

        $response->assertStatus(200)->assertViewIs('detail');
        $response->assertSee($this->item->name);
    }

    public function test_認証済みユーザーは商品詳細ページにアクセスできる()
    {
        $user = User::factory()->create();
        $this->assertNotNull($this->item, 'Itemデータが正しく作成されていません');
        $response = $this->actingAs($user)->get("/item/{$this->item->id}");

        $response->assertStatus(200)->assertViewIs('detail');
        $response->assertSee($this->item->name);
    }

    public function test_未認証ユーザーがマイページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $response = $this->get('/mypage');

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーはマイページにアクセスできる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200)->assertViewIs('mypage');
    }

    public function test_未認証ユーザーがプロフィール設定ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $response = $this->get('/mypage/profile');

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーはプロフィール設定ページにアクセスできる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200)->assertViewIs('profile');
    }

    public function test_未認証ユーザーが出品ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $response = $this->get('/sell');

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーは出品ページにアクセスできる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/sell');

        $response->assertStatus(200)->assertViewIs('sell');
    }

    public function test_未認証ユーザーが購入ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $this->assertNotNull($this->item, 'Itemデータが正しく作成されていません');
        $response = $this->get("/purchase/{$this->item->id}");

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーは購入ページにアクセスできる()
    {
        $user = User::factory()->create();
        $this->assertNotNull($this->item, 'Itemデータが正しく作成されていません');
        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");

        $response->assertStatus(200)->assertViewIs('purchase');
        $response->assertSee($this->item->name);
    }

    public function test_未認証ユーザーが配送先変更ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $this->assertNotNull($this->item, 'Itemデータが正しく作成されていません');
        $response = $this->get("/purchase/address/{$this->item->id}");

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーは配送先変更ページにアクセスできる()
    {
        $user = User::factory()->create();
        $this->assertNotNull($this->item, 'Itemデータが正しく作成されていません');
        $response = $this->actingAs($user)->get("purchase/address/{$this->item->id}");

        $response->assertStatus(200)->assertViewIs('address');
    }

    public function test_未認証ユーザーが決済成功ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $response = $this->get('/success');

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーは決済成功ページにアクセスできる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/success');

        $response->assertStatus(200)->assertViewIs('stripe.success');
    }

    public function test_未認証ユーザーが決済失敗ページにアクセスするとログインページにリダイレクトされる()
    {
        $this->assertGuest();
        $response = $this->get('/cancel');

        $response->assertRedirect('/login');
    }

    public function test_認証済みユーザーは決済失敗ページにアクセスできる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/cancel');

        $response->assertStatus(200)->assertViewIs('stripe.cancel');
    }
}
