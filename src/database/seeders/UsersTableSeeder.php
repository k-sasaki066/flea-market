<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::factory()->create([
            'nickname' => '出品者1',
            'email' => 'seller1@example.com',
            'password' => Hash::make('password1'),
        ]);

        $user2 = User::factory()->create([
            'nickname' => '出品者2',
            'email' => 'seller2@example.com',
            'password' => Hash::make('password2'),
        ]);

        User::factory()->create([
            'nickname' => '未出品ユーザー',
            'email' => 'noitems@example.com',
            'password' => Hash::make('password3'),
        ]);

        $otherUsers = User::factory()->count(2)->create();

        // 他Seederに渡すため保存
        cache()->put('seller1_id', $user1->id);
        cache()->put('seller2_id', $user2->id);
        cache()->put('other_user_ids', $otherUsers->pluck('id')->toArray());
    }
}
