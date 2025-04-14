<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Condition;
use App\Models\Brand;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1Id = cache()->get('seller1_id');
        $user2Id = cache()->get('seller2_id');
        $otherUserIds = cache()->get('other_user_ids');

        $items = [
            [
                'user_id' => $user1Id,
                'condition_id' => Condition::first()->id,
                'brand_id' => Brand::where('name', 'EMPORIO-AMANI')->value('id'),
                'name' => '腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'category' => serialize([0=>"1", 1=>"5"]),
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => '15000',
                'status' => '1',
                'created_at' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::where('name', '目立った傷や汚れなし')->value('id'),
                'brand_id' => null,
                'name' => 'HDD',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'category' => serialize([0=>"8"]),
                'description' => '高速で信頼性の高いハードディスク',
                'price' => '5000',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(1)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(1)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::where('name', 'やや傷や汚れあり')->value('id'),
                'brand_id' => null,
                'name' => '玉ねぎ3束',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'category' => serialize([0=>"10"]),
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => '300',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(2)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(2)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::where('name', '状態が悪い')->value('id'),
                'brand_id' => Brand::where('name', 'Crockett&Jones')->value('id'),
                'name' => '革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'category' => serialize([0=>"1", 1=>"5"]),
                'description' => 'クラシックなデザインの革靴',
                'price' => '4000',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(3)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(3)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::first()->id,
                'brand_id' => Brand::where('name', 'Windows')->value('id'),
                'name' => 'ノートPC',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'category' => serialize([0=>"8"]),
                'description' => '高性能なノートパソコン',
                'price' => '45000',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(4)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(4)->format('Y-m-d'),
            ],
            [
                'user_id' => $user2Id,
                'condition_id' => Condition::where('name', '目立った傷や汚れなし')->value('id'),
                'brand_id' => Brand::where('name', 'MAXIM')->value('id'),
                'name' => 'マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'category' => serialize([0=>"3"]),
                'description' => '高音質のレコーディング用マイク',
                'price' => '8000',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(5)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(5)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::where('name', 'やや傷や汚れあり')->value('id'),
                'brand_id' => Brand::where('name', 'NINE WEST')->value('id'),
                'name' => 'ショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'category' => serialize([0=>"4"]),
                'description' => 'おしゃれなショルダーバッグ',
                'price' => '3500',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(6)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(6)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::where('name', '状態が悪い')->value('id'),
                'brand_id' => Brand::where('name', 'THERMOS')->value('id'),
                'name' => 'タンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'category' => serialize([0=>"10"]),
                'description' => '使いやすいタンブラー',
                'price' => '500',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(7)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(7)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::first()->id,
                'brand_id' => null,
                'name' => 'コーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'category' => serialize([0=>"10"]),
                'description' => '手動のコーヒーミル',
                'price' => '4000',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(8)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(8)->format('Y-m-d'),
            ],
            [
                'user_id' => collect($otherUserIds)->random(),
                'condition_id' => Condition::where('name', '目立った傷や汚れなし')->value('id'),
                'brand_id' => Brand::where('name', 'ちふれ')->value('id'),
                'name' => 'メイクセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'category' => serialize([0=>"6"]),
                'description' => '便利なメイクアップセット',
                'price' => '2500',
                'status' => '1',
                'created_at' => Carbon::now()->subDay(9)->format('Y-m-d'),
                'updated_at' => Carbon::now()->subDay(9)->format('Y-m-d'),
            ],
        ];
        foreach ($items as $item) {
            DB::table('items')->insert($item);
        }
    }
}
