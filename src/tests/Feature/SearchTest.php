<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class SearchTest extends TestCase
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

    public function test_部分一致検索ができる()
    {
        $user = User::firstOrFail();
        $response = $this->actingAs($user)
        ->followingRedirects()
        ->get('/search?keyword=時計');

        $items = $response->viewData('items');

        $response->assertStatus(200)->assertViewHas('items');
        $this->assertTrue(collect($items)->every(fn($item) => str_contains($item->name, '時計')));

        $response->assertSessionHas('search_keyword', '時計');
    }

    public function test_トップページで検索したキーワードがマイリストページでも保持される()
    {
        $user = User::firstOrFail();
        $response = $this->actingAs($user)->followingRedirects()->get('/search?keyword=時計');
        $response->assertSessionHas('search_keyword', '時計');

        $response = $this->actingAs($user)->followingRedirects()->get('/?page=mylist');
        $response->assertSessionHas('search_keyword', '時計');
    }

    public function test_マイリストページで検索したキーワードがトップページでも保持される()
    {
        $user = User::firstOrFail();
        $response = $this->actingAs($user)
        ->followingRedirects()->get('/search?keyword=コーヒー');
        $response->assertSessionHas('search_keyword', 'コーヒー');

        $response = $this->actingAs($user)->followingRedirects()->get('/');
        $response->assertSessionHas('search_keyword', 'コーヒー');
    }

    public function test_検索キーワードを空で送信すると全件表示される()
    {
        $this->assertDatabaseCount('items', 10);
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $response = $this->actingAs($user)
        ->followingRedirects()
        ->get('/search?keyword=');

        $response->assertStatus(200)
        ->assertViewHas('items', function ($items) {
            return count($items) === 10;
        });

        $response->assertSessionHas('search_keyword', '');
    }
}
