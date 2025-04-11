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
use Illuminate\Support\Facades\Storage;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

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

    public function test_変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertViewIs('profile');

        $response->assertSee($user->image_url);
        $response->assertSee($user->post_cord);
        $response->assertSee($user->address);
        $response->assertSee($user->building);

        $file = UploadedFile::fake()->create('test_image.jpg');
        $response = $this->post('/mypage/profile', [
            'nickname' => $user->nickname,
            'image_url' => $file,
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertRedirect('/mypage')->assertSessionHas(['result' => 'プロフィールが更新されました']);

        $user->refresh();
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
            'image_url' => $user->image_url,
            'profile_completed' => true,
        ]);
        Storage::disk('public')->assertExists('images/' . basename($user->image_url));

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);
        $response->assertViewIs('profile');

        $response->assertSee($user->image_url);
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区');
        $response->assertSee('テストビル101');
    }

    public function test_画像の形式が違う場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $response = $this->post('/mypage/profile', [
            'image_url' => $file,
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['image_url' => '「.png」または「.jpeg」形式でアップロードしてください']);
    }

    public function test_ユーザー名が未入力の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

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
        $user = User::factory()->create();

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
        $user = User::factory()->create();

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
        $user = User::factory()->create();

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
        $user = User::factory()->create();

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

    public function test_全ての項目が入力されている場合_プロフィールが登録され_マイページに遷移される()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'nickname' => null,
            'post_cord' => null,
            'address' => null,
            'building' => null,
            'image_url' => null,
            'profile_completed' => false,
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);

        $file = UploadedFile::fake()->create('test_image.jpg');
        $response = $this->post('/mypage/profile', [
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区100-1',
            'building' => 'テストビル101',
            'image_url' => $file,
        ]);

        $response->assertRedirect('/mypage')->assertSessionHas('result', 'プロフィールが更新されました');

        $savedItem = User::find($user->id);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => '東京都新宿区100-1',
            'building' => 'テストビル101',
            'image_url' => $savedItem->image_url,
            'profile_completed' => true,
        ]);
    }
}
