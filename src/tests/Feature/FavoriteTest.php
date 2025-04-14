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
use App\Models\Brand;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;
    protected $item;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Condition::create([
            'name' => '良好',
        ]);

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

    public function test_いいねアイコンを押下することによって_いいねした商品として登録することができる()
    {
        $initialCount = Favorite::where('item_id', $this->item->id)->count();
        $this->assertEquals(0, $initialCount);

        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertStatus(200)->assertViewIs('detail');

        $response = $this->postJson("/like/{$this->item->id}");

        $response->assertStatus(200)->assertJson(['message' => 'Liked successfully!']);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $newCount = Favorite::where('item_id', $this->item->id)->count();
        $this->assertEquals($initialCount + 1, $newCount);

        $response = $this->get("/item/{$this->item->id}");
        $response->assertSee((string)$newCount);
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertStatus(200)->assertViewIs('detail');

        $response = $this->postJson("/like/{$this->item->id}");

        $response->assertStatus(200)->assertJson(['message' => 'Liked successfully!']);

        $response = $this->get("/item/{$this->item->id}");

        $response->assertSee('/images/star-yellow.svg');
    }

    public function test_同じ商品を2回いいねできない()
    {
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);

        $response = $this->actingAs($this->user)->postJson("/like/{$this->item->id}");

        $response->assertStatus(200)->assertJson(['message' => 'Already liked!']);
        $this->assertDatabaseCount('favorites', 1);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }

    public function test_再度いいねアイコンを押下することによって_いいねを解除することができる()
    {
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);

        $initialCount = Favorite::where('item_id', $this->item->id)->count();
        $this->assertEquals(1, $initialCount);

        $response = $this->actingAs($this->user)->get("/item/{$this->item->id}");
        $response->assertStatus(200)->assertViewIs('detail');

        $response = $this->deleteJson("/unlike/{$this->item->id}");

        $response->assertStatus(200)->assertJson(['message' => 'Unliked successfully']);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $newCount = Favorite::where('item_id', $this->item->id)->count();
        $this->assertEquals($initialCount - 1, $newCount);

        $response = $this->get("/item/{$this->item->id}");
        $response->assertSee((string)$newCount);
        $response->assertSee('/images/star.svg');
    }

    public function test_ログイン前のユーザーはいいねを登録できない()
    {
        $response = $this->get("/item/{$this->item->id}");

        $response->assertStatus(200)->assertViewIs('detail');
        $response->assertSee('<a class="favorite-count__login" href="/login" onclick="return confirmLogin();"><img class="favorite-count__img" src="http://localhost/images/star.svg" alt="" width="22px"></a>', false);

        $response = $this->get("/login");
        $response->assertStatus(200)->assertViewIs('auth.login');
    }
}
