<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        for ($id = 1; $id <= 5; $id++) {
            DB::table('comments')->insert([
                'user_id' => mt_rand(1,5),
                'item_id' => $id,
                'comment' => 'コメント失礼します。こちらの商品はお値引き可能でしょうか。',
                'created_at' => $subDay,
                'updated_at' => $subDay,
            ]);
            DB::table('comments')->insert([
                'user_id' => mt_rand(1,5),
                'item_id' => $id,
                'comment' => '傷ありとのことですが、具体的な箇所と傷の程度を教えてください。',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
