<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '1',
                'name' => '腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'category' => serialize([0=>"1", 1=>"5"]),
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => '15000',
                'status' => '2',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '2',
                'name' => 'HDD',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'category' => serialize([0=>"8"]),
                'description' => '高速で信頼性の高いハードディスク',
                'price' => '5000',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '3',
                'name' => '玉ねぎ3束',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'category' => serialize([0=>"10"]),
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => '300',
                'status' => '2',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '4',
                'name' => '革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'category' => serialize([0=>"1", 1=>"5"]),
                'description' => 'クラシックなデザインの革靴',
                'price' => '4000',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '1',
                'name' => 'ノートPC',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'category' => serialize([0=>"8"]),
                'description' => '高性能なノートパソコン',
                'price' => '45000',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '2',
                'name' => 'マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'category' => serialize([0=>"3"]),
                'description' => '高音質のレコーディング用マイク',
                'price' => '8000',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '3',
                'name' => 'ショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'category' => serialize([0=>"4"]),
                'description' => 'おしゃれなショルダーバッグ',
                'price' => '3500',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '4',
                'name' => 'タンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'category' => serialize([0=>"10"]),
                'description' => '使いやすいタンブラー',
                'price' => '500',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '1',
                'name' => 'コーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'category' => serialize([0=>"10"]),
                'description' => '手動のコーヒーミル',
                'price' => '4000',
                'status' => '1',
            ],
            [
                'user_id' => mt_rand(1,5),
                'condition_id' => '2',
                'name' => 'メイクセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'category' => serialize([0=>"6"]),
                'description' => '便利なメイクアップセット',
                'price' => '2500',
                'status' => '1',
            ],
        ];
        foreach ($items as $item) {
            DB::table('items')->insert($item);
        }
    }
}
