<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が入力されていない場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function test_メールアドレスが入力されていない場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_メールアドレスが無効な場合_バリデーションメッセージが表示される_1()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);
    }

    public function test_メールアドレスが無効な場合_バリデーションメッセージが表示される_2()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'invalid@@email.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);
    }

    public function test_パスワードが入力されていない場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '12345678',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_パスワードが7文字以下の場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_パスワードが確認用パスワードと一致しない場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword!',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    public function test_確認パスワードが未入力の場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => '確認用パスワードを入力してください']);
    }

    public function test_確認用パスワードが7文字以下の場合_バリデーションメッセージが表示される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => '確認用パスワードは8文字以上で入力してください']);
    }

    public function test_全ての項目が入力されている場合_会員情報が登録され_ログイン画面に遷移される()
    {
        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertDatabaseHas('users', ['name' => 'テストユーザー', 'email' => 'test@example.com', 'email_verified_at' => null]);
        $response->assertRedirect('/login');
    }

    public function test_すでに登録されたメールアドレスでは登録失敗()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $this->assertGuest();
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email' => '指定のemailは既に使用されています。']);
        $this->assertEquals(1, User::count());
    }
}
