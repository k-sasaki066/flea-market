<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Item;

class SelectTest extends DuskTestCase
{
    use DatabaseMigrations;
    protected $user;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        $this->item = Item::first();
    }

    public function test_プルダウンの選択が即時反映される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/purchase/{$this->item->id}") // プルダウンがあるページ
                ->assertSee('選択してください') // 初期値が表示されているか確認
                ->select('#select', '2') // 2番目の選択肢を選択
                ->pause(500) // JavaScriptの処理を待つ
                ->assertSeeIn('#selectValue', 'コンビニ払い'); // 選択した値が即時反映されるか
        });
    }

    public function test_プルダウンの選択がsessionStorageに保存される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/purchase/{$this->item->id}")
                ->select('#select', '1') // 3番目の選択肢を選択
                ->pause(500) // JavaScriptの処理を待つ
                ->refresh() // ページをリロード
                ->assertSelected('#select', '1') // 選択した値が復元されているか
                ->assertSeeIn('#selectValue', 'コンビニ払い'); // 再び表示が正しく反映されるか
        });
    }
}
