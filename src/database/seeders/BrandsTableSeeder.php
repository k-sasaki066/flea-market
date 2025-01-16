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
        $brands = [
            [
                'item_id' => '1',
                'name' => 'EMPORIO-AMANI',
            ],
            [
                'item_id' => '4',
                'name' => 'Crockett&Jones',
            ],
            [
                'item_id' => '5',
                'name' => 'Windows',
            ],
            [
                'item_id' => '6',
                'name' => 'MAXIM',
            ],
            [
                'item_id' => '7',
                'name' => 'NINE WEST',
            ],
        ];
        foreach ($brands as $brand) {
            DB::table('brands')->insert($brand);
        }
    }
}
