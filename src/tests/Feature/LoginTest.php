<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('password'),
            'profile_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->user = User::first();
    }

    public function test_テストデータが正しく作成されたか()
    {
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    public function test_メールアドレスが入力されていない場合_バリデーションメッセージが表示される()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_メールアドレスが無効な場合_バリデーションメッセージが表示される_1()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'testexamplecom',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);
    }

    public function test_メールアドレスが無効な場合_バリデーションメッセージが表示される_2()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);
    }

    public function test_パスワードが入力されていない場合_バリデーションメッセージが表示される()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@@example.com',
            'password' => '',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_パスワードを7文字で入力した場合_バリデーションメッセージが表示される()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@@example.com',
            'password' => 'passwor',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_入力情報が間違っている場合_バリデーションメッセージが表示される_メールアドレス()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_入力情報が間違っている場合_バリデーションメッセージが表示される_パスワード()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_正しい情報が入力された場合_ログイン処理が実行される()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'profile_completed' => false,
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/email/verify');
    }

    public function test_レートリミットが適用される()
    {
        RateLimiter::clear('login:test@example.com' . request()->ip());

        for ($i = 0; $i < 10; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    public function test_メール認証済みのユーザーがログインした場合_プロフィール設定画面にリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => false,
        ]);
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/mypage/profile');
    }

    public function test_メール認証済み且つプロフィール設定済みのユーザーがログインした場合_マイページにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'image_url' => '/images/test.jpg',
            'nickname' => 'test',
            'post_cord' => '123-4567',
            'address' => 'テスト住所102',
            'profile_completed' => true,
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/mypage');
    }

    public function test_ログアウトができる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/logout', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}