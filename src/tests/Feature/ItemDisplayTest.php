<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Condition;

class ItemDisplayTest extends TestCase
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

    public function test_トップページで商品詳細ページのリンクが正しく表示される()
    {
        $item = Item::firstOrFail();

        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee("/item/{$item->id}");
    }

    public function test_未認証ユーザーは全商品を閲覧できる()
    {
        $this->assertDatabaseCount('items', 10);

        $response = $this->get('/');

        $response->assertStatus(200)
        ->assertViewHas('items', function ($items) {
            return count($items) === 10;
        });
    }

    public function test_認証ユーザーは自分が出品した商品は表示されない()
    {
        $user = User::whereHas('items')->firstOrFail();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('items');
        $items = $response->viewData('items');

        $this->assertTrue(
            $items->every(fn ($item) => $item->user_id !== $user->id)
        );
    }

    public function test_購入済み商品は「Sold」と表示される()
    {
        $item = Item::firstOrFail();
        $item->update(['status' => 2]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 2
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $items = $response->viewData('items');
        $response->assertSee('Sold');
        foreach ($items->where('id', '!=', $item->id) as $availableItem) {
            $response->assertDontSee('<p class="sold-out bold">Sold</p>');
        }
    }

    public function test_認証ユーザーがsuggestページを開くとお気に入り商品のカテゴリーに基づいて商品を取得する()
    {
        $user = User::factory()->create();

        $item = Item::inRandomOrder()->first();
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?page=suggest');
        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) use ($item, $user) {
        if ($items->isEmpty()) {
            return true;
        }

        return $items->every(fn($suggestedItem) =>
            unserialize($suggestedItem->category) === unserialize($item->category) &&
            $suggestedItem->user_id !== $user->id &&
            $suggestedItem->status == 1
            );
        });
    }

    public function test_お気に入り登録をしていない認証ユーザーがsuggestページを開くと状態の良い商品を取得する()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/?page=suggest');
        $response->assertStatus(200);

        $conditionId = Condition::where('name', '良好')->firstOrFail()->id;

        $response->assertViewHas('items', function ($items) use ($user, $conditionId) {
        if ($items->isEmpty()) {
            return true;
        }

        return $items->every(fn($suggestedItem) =>
            $suggestedItem->user_id !== $user->id &&
            $suggestedItem->status == 1 && $suggestedItem->condition_id == $conditionId
            );
        });
    }

    public function test_mylistページではいいねした商品だけが表示される()
    {
        $user = User::whereHas('favorites')->firstOrFail();
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
        ]);

        $favoriteItemIds = Favorite::where('user_id', $user->id)->pluck('item_id');

        $response = $this->actingAs($user)->get('/?page=mylist');
        $response->assertStatus(200);

        $response->assertViewHas('items');
        $items = $response->viewData('items');
        $this->assertTrue($items->every(fn($item) => $favoriteItemIds->contains($item->id)));

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_mylistページでは購入済みの商品はSoldと表示される()
    {
        $user = User::whereHas('favorites')->firstOrFail();
        $favorite = Favorite::where('user_id', $user->id)->firstOrFail();

        $item = Item::findOrFail($favorite->item_id);
        $item->update(['status' => 2]);
        $this->assertEquals(2, $item->fresh()->status);

        $this->assertDatabaseHas('items', [
            'id' => $favorite->item_id,
            'status' => 2,
        ]);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertViewHas('items');
        $items = $response->viewData('items');
        $response->assertStatus(200)->assertSee('Sold');

        foreach ($items->where('id', '!=', $item->id) as $availableItem) {
            $response->assertDontSee('<p class="sold-out bold">Sold</p>');
        }
    }

    public function test_mylistページでは自分が出品した商品は表示されない()
    {
        $user = User::whereHas('items')->firstOrFail();

        $response = $this->actingAs($user)->get('/?page=mylist');$response->assertStatus(200);

        $response->assertViewHas('items', function ($items) use ($user) {
            return $items->every(fn ($item) => $item->user_id !== $user->id);
        });
    }

    public function test_mylistページでは未認証の場合は何も表示されない()
    {
        $response = $this->get('/');

        $response = $this->get('/?page=mylist');
        $response->assertStatus(200)->assertDontSee('マイリスト');
    }
}
