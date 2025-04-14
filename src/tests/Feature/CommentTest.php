<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Brand;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    protected $item;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Condition::create(['name' => '良好',]);

        Category::create(['id' => 1, 'name' => 'ファッション']);
        Category::create(['id' => 2, 'name' => '家電']);

        Brand::create(['id' => 1, 'name' => 'テストブランド']);

        $this->item = Item::create([
            'user_id' => $this->user->id,
            'condition_id' => Condition::first()->id,
            'brand_id' => Brand::first()->id,
            'name' => 'テスト商品',
            'image_url' => '/images/test.jpg',
            'category' => serialize([0=>"1", 1=>"2"]),
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

        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseHas('categories', [
            'name' => 'ファッション',
        ]);

        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseHas('brands', [
            'name' => 'テストブランド',
        ]);
    }

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $initialCount = Comment::where('item_id', $this->item->id)->count();
        $this->assertEquals(0, $initialCount);

        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertStatus(200)->assertViewIs('detail');

        $commentData = ['comment' => 'テストコメント'];

        $response = $this->post("/comment/{$this->item->id}", $commentData);

        $response->assertRedirect()->assertSessionHas('result', 'コメントを送信しました');

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント',
        ]);

        $newCount = Comment::where('item_id', $this->item->id)->count();
        $this->assertEquals($initialCount + 1, $newCount);

        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertSee((string)$newCount);
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $response = $this->get("/item/{$this->item->id}");
        $response->assertStatus(200)->assertViewIs('detail');

        $response->assertSee('<a class="item-comment__form-btn form-btn bold" href="/login" onclick="return confirmLogin();">コメントを送信する</a>', false);

        $response = $this->get("/login", [
            'comment' => '未ログインのテストコメント',
        ]);
        $response->assertStatus(200)->assertViewIs('auth.login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $this->item->id,
            'comment' => '未ログインのテストコメント',
        ]);
    }

    public function test_コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertStatus(200);

        $response = $this->post("/comment/{$this->item->id}", [
            'comment' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    public function test_コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertStatus(200);
        $longComment = str_repeat('あ', 256);

        $response = $this->post("/comment/{$this->item->id}", [
            'comment' => $longComment,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以下で入力してください']);
    }

    public function test_プロフィールが未設定の状態で送信ボタンをクリックするとプロフィール設定ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);

        $response = $this->actingAs($user)->get("/item/{$this->item->id}");
        $response->assertStatus(200);

        $response = $this->post("/comment/{$this->item->id}", [
            'comment' => 'テストコメント',
        ]);

        $response->assertRedirect('/mypage/profile');
        $response->assertSessionHas(['error' => 'コメントするにはプロフィールを設定してください']);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント',
        ]);
    }
}
