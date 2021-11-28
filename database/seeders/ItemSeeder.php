<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Item::count()) { $this->addItems(); }
    }

    private function addItems(){
        $items = [
            ['itemname' => 'Конек плоский 150х150 2м Цинк', 'group_id' => 1, 'color' => 'Цинк'],
            ['itemname' => 'Конек плоский 200х200 2м Цинк', 'group_id' => 1, 'color' => 'Цинк'],
            ['itemname' => 'Конек плоский 150х150 2м RAL8017', 'group_id' => 1, 'color' => 'RAL8017'],
            ['itemname' => 'Конек плоский 150х150 2м RAL8017', 'group_id' => 1, 'color' => 'RAL8017'],
            ['itemname' => 'Торцевая планка 85х85 2м Цинк', 'group_id' => 2, 'color' => 'Цинк'],
            ['itemname' => 'Торцевая планка 85х85 2м RAL8017', 'group_id' => 2, 'color' => 'RAL8017'],
            ['itemname' => 'Саморез кровельный 4,8х35мм Цинк', 'group_id' => 3, 'color' => 'Цинк'],
            ['itemname' => 'Саморез кровельный 4,8х35мм RAL8017', 'group_id' => 3, 'color' => 'RAL8017'],
            ['itemname' => 'Саморез кровельный 5,5х19мм Цинк', 'group_id' => 3, 'color' => 'Цинк'],
            ['itemname' => 'Саморез кровельный 5,5х19мм RAL8017', 'group_id' => 3, 'color' => 'RAL8017'],
            ['itemname' => 'Профильная труба 40х20х1,5мм 6м', 'group_id' => 4, 'color' => ''],
            ['itemname' => 'Профильная труба 60х60х2,0мм 6м', 'group_id' => 4, 'color' => '']
        ];
        foreach ($items as $item) { Item::create($item); }
    }
}
