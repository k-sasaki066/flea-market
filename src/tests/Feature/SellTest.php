<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SellTest extends TestCase
{
    use RefreshDatabase;
    protected $brand;
    protected $condition;
    protected $categoryIds;
    protected $file;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->brand = Brand::first()->name;
        $this->condition = Condition::first()->id;
        $this->categoryIds = Category::pluck('id')->take(1)->toArray();
        $this->file = UploadedFile::fake()->create('test.png', 500, 'image/png'); // pngファイルを作成
    }

    public function test_テストデータが正しく作成されたか()
    {
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

    public function test_必須項目を未選択場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => '',
            'brand_id' => '',
            'name' => '',
            'image_url' => '',
            'category' => '',
            'description' => '',
            'price' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['image_url' => '商品の画像を選択してください']);
        $response->assertSessionHasErrors(['category' => '商品のカテゴリーを入力してください']);
        $response->assertSessionHasErrors(['condition_id' => '商品の状態を入力してください']);
        $response->assertSessionHasErrors(['name' => '商品名を入力してください']);
        $response->assertSessionHasErrors(['description' => '商品の説明を入力してください']);
        $response->assertSessionHasErrors(['price' => '商品の価格を入力してください']);
    }

    public function test_画像形式が違う場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf');
        // PDFファイルを作成

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $this->brand,
            'name' => 'テスト商品',
            'image_url' => $file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => 1000,
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['image_url' => 'jpegまたはpng形式のファイルを指定してください']);
    }

    public function test_商品の説明が256文字以上の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $this->brand,
            'name' => 'テスト商品',
            'image_url' => $this->file,
            'category' => $this->categoryIds,
            'description' => str_repeat('あ', 256),
            'price' => 1000,
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['description' => '商品の説明は255文字以下で入力してください']);
    }

    public function test_販売価格が全角数字の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $this->brand,
            'name' => 'テスト商品',
            'image_url' => $this->file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => '１０００',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['price' => '商品の価格は半角数字でカンマ( , )を抜いて入力してください']);
    }

    public function test_販売価格がカンマ有りで入力した場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $this->brand,
            'name' => 'テスト商品',
            'image_url' => $this->file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => '1,000',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['price' => '商品の価格は半角数字でカンマ( , )を抜いて入力してください']);
    }

    public function test_販売価格をマイナスの数字で入力した場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $this->brand,
            'name' => 'テスト商品',
            'image_url' => $this->file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => -1000,
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['price' => '商品の価格は0円以上で入力してください']);
    }

    public function test_商品出品画面にて必要な情報が保存できること()
    {
        Storage::fake('public');
        // 仮想ストレージを使用

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('test_image.jpg');

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => 'テストブランド',
            'name' => 'テスト商品',
            'image_url' => $file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => 1000,
        ]);

        $response->assertRedirect('/mypage?page=sell')
        ->assertSessionHas(['result' => '商品を出品しました']);

        $brandId = Brand::where('name', 'テストブランド')->value('id');

        $savedItem = Item::latest()->first();
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $brandId,
            'name' => 'テスト商品',
            'category' => serialize($this->categoryIds),
            'description' => 'テスト説明',
            'price' => 1000,
        ]);

        // ファイルが正しいディレクトリに保存されているか確認
        Storage::disk('public')->assertExists('images/' . basename($savedItem->image_url));

        // `image_url` が正しいURLか
        $expectedUrl = '/storage/images/' . basename($savedItem->image_url);
        $this->assertEquals($expectedUrl, $savedItem->image_url);
    }

    public function test_プロフィールが未設定の状態で出品ボタンをクリックするとプロフィール設定ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);

        $response = $this->actingAs($user)->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => 'テストブランド',
            'name' => 'テスト商品',
            'image_url' => $this->file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => 1000,
        ]);

        $response->assertRedirect('/mypage/profile');
        $response->assertSessionHas(['error' => '商品を出品するにはプロフィールを設定してください']);
    }

    public function test_同じ名前のブランド名は重複して登録されない()
    {
        Storage::fake('public');// 仮想ストレージを使用
        $user = User::factory()->create();
        $sameBrandName = Brand::first()->name;

        $response = $this->actingAs($user)->post('/sell', [
            'user_id' => $user->id,
            'condition_id' => $this->condition,
            'brand_id' => $sameBrandName,
            'name' => 'テスト商品',
            'image_url' => $this->file,
            'category' => $this->categoryIds,
            'description' => 'テスト説明',
            'price' => 1000,
        ]);

        $brandCount = Brand::where('name', $sameBrandName)->count();
        $this->assertEquals(1, $brandCount);
    }
}
