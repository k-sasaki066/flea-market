<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;

class MypageTest extends TestCase
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

        $this->assertDatabaseCount('comments', 5);
        $this->assertDatabaseHas('comments', [
            'comment' => 'コメント失礼します。こちらの商品はお値引き可能でしょうか。',
        ]);

        $this->assertDatabaseCount('favorites', 15);
    }

    public function test_必要な情報が取得できる_プロフィール画像_ユーザー名()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertViewIs('mypage');

        $response->assertSee($user->image_url);
        $response->assertSee($user->nickname);
    }

    public function test_必要な情報が取得できる_出品した商品一覧()
    {
        // 出品商品を持つユーザーを取得
        $user = User::whereHas('items')->withCount('items')->firstOrFail();
        $itemsCount = $user->items_count;

        $response = $this->actingAs($user)->get('/mypage?page=sell');

        $response->assertStatus(200)
        ->assertViewIs('mypage')
        ->assertViewHas('items', function ($items) use ($itemsCount, $user){
            return $items->count() === $itemsCount && $items->every(fn ($item) => $item->user_id === $user->id);
        });
    }

    public function test_必要な情報が取得できる_購入した商品一覧()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $items = Item::take(3)->get();

        foreach($items as $item) {
            $randomStr = substr(str_shuffle($chars), 0, 10);

            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_id' => Payment::first()->id,
                'post_cord' => $user->post_cord,
                'address' => $user->address,
                'building' => $user->building,
                'stripe_session_id' => $randomStr,
                'payment_status' => 'paid',
            ]);

            $item->update([
                'status' => 2,
            ]);
        }
        
        $this->assertDatabaseCount('purchases', 3);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200)
            ->assertViewIs('mypage')
            ->assertViewHas('items', function ($items) use ($user){
                return $items->count() === 3 && $items->every(fn ($item) => $item->purchase->user_id === $user->id);
            });
    }

    public function test_初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'image_url' => '/images/test.jpg',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertViewIs('profile');

        $response->assertSee('/images/test.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区');
        $response->assertSee('テストビル101');
    }

    public function test_画像の形式が違う場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf'); // PDFファイルを作成

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'image_url' => $file,
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['image_url' => 'jpegまたはpng形式のファイルを指定してください']);
    }

    public function test_ユーザー名が未入力の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'nickname' => '',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['nickname' => 'ユーザー名を入力してください']);
    }

    public function test_郵便番号が未入力の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'nickname' => 'test',
            'post_cord' => '',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['post_cord' => '郵便番号を入力してください']);
    }

    public function test_郵便番号をハイフンなしで入力の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'nickname' => 'test',
            'post_cord' => '1234567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['post_cord' => '郵便番号は半角数字でハイフン(-)を入れて8文字で入力してください']);
    }

    public function test_郵便番号を全角数字で入力の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'nickname' => 'test',
            'post_cord' => '１２３−４５６７',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['post_cord' => '郵便番号は半角数字でハイフン(-)を入れて8文字で入力してください']);
    }

    public function test_住所が未入力の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => '',
            'building' => '',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['address' => '住所を入力してください']);
    }
}
