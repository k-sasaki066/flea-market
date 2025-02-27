<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
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
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/email/verify');
    }

    public function test_メール認証リンクをクリックすると認証が完了する()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $user = User::factory()->create([
            'email_verified_at' => null, // メール未認証
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

}
