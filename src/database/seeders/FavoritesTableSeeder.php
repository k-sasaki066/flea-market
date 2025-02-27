<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;

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
        for ($i = 1; $i <= 15; $i++) {
            $user = User::inRandomOrder()->first();

            $item = Item::where('user_id', '!=', $user->id)->inRandomOrder()->first();

            if (!$item) {
                continue;
            }

            DB::table('favorites')->insert([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
