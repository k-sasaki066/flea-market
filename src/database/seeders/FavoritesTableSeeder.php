<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        for ($id = 1; $id <= 5; $id++) {
            DB::table('favorites')->insert([
                'user_id' => $id,
                'item_id' => mt_rand(1,10),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('favorites')->insert([
                'user_id' => $id,
                'item_id' => mt_rand(1,10),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
