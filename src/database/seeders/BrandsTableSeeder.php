<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = ['EMPORIO-AMANI', 'Crockett&Jones', 'Windows', 'MAXIM', 'NINE WEST', 'THERMOS', 'ちふれ',];

        foreach ($brands as $brand) {
            DB::table('brands')->insert(['name' => $brand]);
        }
    }
}
