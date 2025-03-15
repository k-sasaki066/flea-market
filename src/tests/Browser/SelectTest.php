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
                ->visit("/purchase/{$this->item->id}")
                ->assertSee('選択してください')
                ->select('#select', '2')
                ->waitForText('カード支払い')
                ->assertValue('#selectValue', 'カード支払い');
        });
    }

    public function test_プルダウンの選択がsessionStorageに保存される()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/purchase/{$this->item->id}")
                ->select('#select', '1')
                ->waitForText('コンビニ払い')
                ->script("sessionStorage.setItem('selectedOption', document.querySelector('#select').value);")

                ->refresh()

                ->waitForText('コンビニ払い')
                ->assertSelected('#select', '1')
                ->assertSeeIn('#selectValue', 'コンビニ払い')

                ->script("return sessionStorage.getItem('selectedOption');", function ($value) {
                    $this->assertEquals('1', $value);
                });
        });
    }
}
