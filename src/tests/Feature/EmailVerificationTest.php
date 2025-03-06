<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function getVerificationUrl($notification, $user)
    {
        return \URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

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
        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('conditions', [
            'name' => 'テスト状態',
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'price' => 5000,
        ]);
    }

    public function test_会員登録後にメール認証メールが送信される()
    {
        Notification::fake(); // メール送信をモック

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();
        Notification::assertSentTo($user, VerifyEmail::class); // メール認証通知が送信されたか確認
    }

    public function test_ログイン後にメール認証画面にリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'profile_completed' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/mypage/profile');

        $response = $this->get('/mypage/profile');
        $response->assertRedirect('/email/verify');
    }

    public function test_メール認証リンクをクリックすると認証が完了する()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // メール認証通知を送信
        $user->sendEmailVerificationNotification();

        // メール通知からURLを取得
        Notification::assertSentTo($user, VerifyEmail::class, function ($notification) use ($user) {
            $verificationUrl = $this->getVerificationUrl($notification, $user);

            // URLにアクセス
            $response = $this->actingAs($user)->get($verificationUrl);

            $response->assertLocation('/mypage?verified=1'); // 認証後、トップページへリダイレクトされるか
            return $user->fresh()->hasVerifiedEmail();
        });
    }

    public function test_メール認証未完了ユーザーはメール認証画面にリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/mypage');

        $this->assertAuthenticated();
        $response->assertRedirect('/email/verify');
    }

    public function test_メール認証済みユーザーはメール認証画面にリダイレクトされない()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $response = $this->actingAs($user)->get('/mypage');

        $this->assertAuthenticated();
        $response->assertStatus(200)->assertViewIs('mypage');
    }

    public function test_メール再送ボタンをクリックするとメールが再送されるか()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Notification::fake();

        $response = $this->actingAs($user)->post('/email/verification-notification');

        $response->assertRedirect();

        Notification::assertSentTo($user, VerifyEmail::class, function ($notification, $channels) {
            return count($channels) === 1;
        });

        // メールが1回だけ送信されたことを確認
        Notification::assertSentToTimes($user, VerifyEmail::class, 1);
    }

    public function test_メール未認証ユーザーはプロフィール編集ページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertRedirect('/email/verify');
    }

    public function test_メール未認証ユーザーはマイページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/mypage');

        $response->assertRedirect('/email/verify');
    }

    public function test_メール未認証ユーザーは出品ページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/sell');

        $response->assertRedirect('/email/verify');
    }

    public function test_メール未認証ユーザーは購入ページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get("/purchase/{$this->item->id}");

        $response->assertRedirect('/email/verify');
    }

    public function test_メール未認証ユーザーは配送先変更ページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get("/purchase/address/{$this->item->id}");

        $response->assertRedirect('/email/verify');
    }

    public function test_メール未認証ユーザーは決済成功ページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/success');

        $response->assertRedirect('/email/verify');
    }

    public function test_メール未認証ユーザーは決済失敗ページにアクセスするとメール認証ページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/cancel');

        $response->assertRedirect('/email/verify');
    }
}
