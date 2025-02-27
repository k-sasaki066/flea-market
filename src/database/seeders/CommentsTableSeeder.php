<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Item;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $subDay = Carbon::now()->subDay(1)->format('Y-m-d');
        for ($i = 1; $i <= 5; $i++) {
            DB::table('comments')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'item_id' => Item::inRandomOrder()->first()->id,
                'comment' => 'コメント失礼します。こちらの商品はお値引き可能でしょうか。',
                'created_at' => $subDay,
                'updated_at' => $subDay,
            ]);
        }
    }
}
