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


class ItemDetailTest extends TestCase
{
    use RefreshDatabase;
    protected $item;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        User::factory()->count(3)->create();
        $this->user = User::inRandomOrder()->first();

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
        $this->assertDatabaseCount('users', 3);

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

    public function test_商品詳細ページに必要な情報が表示される()
    {
        $user = User::factory()->create();

        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $this->item->id,
        ]);

        Comment::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント',
        ]);

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント2',
        ]);

        $this->assertDatabaseCount('favorites', 2);
        $this->assertDatabaseHas('favorites', [
            'item_id' => $this->item->id,
        ]);

        $this->assertDatabaseCount('comments', 2);
        $this->assertDatabaseHas('comments', [
            'comment' => 'テストコメント',
        ]);

        $response = $this->actingAs($user)->get('/item/' . $this->item->id);

        $response->assertStatus(200);

        $response->assertViewIs('detail')
        ->assertViewHas('item')
        ->assertViewHas('category')
        ->assertViewHas('favorite');

        // 必要な情報が含まれているか確認
        $response->assertSee($this->item->image_url);
        $response->assertSee($this->item->name);
        $response->assertSee($this->item->brand->name);
        $response->assertSee(number_format($this->item->price));
        $response->assertSee($this->item->favorites_count);
        $response->assertSee($this->item->comments_count);
        $response->assertSee($this->item->description);
        $response->assertSee($this->item->condition->name);
        $response->assertSee('ファッション');

        // コメントデータが正しく表示されているか確認
        foreach ($this->item->comments as $comment) {
            $response->assertSee($comment->user->nickname);
            $response->assertSee($comment->comment);
        }
    }

    public function test_お気に入り登録の有無が正しく取得される()
    {
        $user = User::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->actingAs($user)->get('/item/' . $this->item->id);

        $response->assertViewHas('favorite', function ($favorite) {
            return $favorite !== null;
        });
    }

    public function test_複数選択されたカテゴリが表示されているか()
    {
        Category::create(['name' => '家電']);
        $this->assertDatabaseCount('categories', 2);
        $testIds = Category::pluck('id')->take(2)->toArray();

        $item = Item::create([
            'user_id' => $this->user->id,
            'condition_id' => Condition::first()->id,
            'brand_id' => Brand::first()->id,
            'name' => 'テスト商品2',
            'image_url' => '/images/test.jpg',
            'category' => serialize($testIds),
            'description' => 'これはテスト用の商品です。',
            'price' => 3000,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品2',
        ]);

        $response = $this->actingAs($this->user)->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response->assertSee('ファッション');
        $response->assertSee('家電');
    }

    public function test_購入済み商品には購入ボタンが表示されない()
    {
        $user = User::factory()->create();

        $this->item->update([
            'status' => 2,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'status' => 2
        ]);

        $response = $this->actingAs($user)->get('/item/' . $this->item->id);
        $response->assertStatus(200);
        $response->assertViewIs('detail');

        $response->assertSee('purchased');
        $response->assertSee('Sold');
        // 購入済みの商品の場合、購入ボタンのクラスにpurchasedを追加し非表示にしている
    }

    public function test_未購入商品には購入ボタンが表示される()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'status' => 1
        ]);

        $response = $this->actingAs($user)->get('/item/' . $this->item->id);
        $response->assertStatus(200);
        $response->assertViewIs('detail');

        $response->assertSee('<a class="item-purchase__btn form-btn bold " href="/purchase/' . $this->item->id . '">購入手続きへ</a>', false);
        $response->assertDontSee('Sold');
    }

    public function ログイン前のユーザーは購入できない()
    {
        $response = $this->get("/item/{$this->item->id}");

        $response->assertStatus(200)->assertViewIn('detail');
        $response->assertSee('<a class="item-purchase__btn form-btn bold " href="#modal">購入手続きへ</a>', false);

        $response = $this->get("/item/{$this->item->id}#modal", $commentData);
        $response->assertSee('商品を購入するには、ログインが必要です。');
    }
}
