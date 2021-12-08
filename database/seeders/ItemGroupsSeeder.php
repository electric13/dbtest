<?php

namespace Database\Seeders;

use App\Models\ItemGroup;
use Illuminate\Database\Seeder;

class ItemGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! ItemGroup::count()) { $this->addItemGroups(); }
    }

    private function addItemGroups() {
        $it_groups = [
            ['id' => 1, 'groupname' => 'Коньки', 'colored' => true],
            ['id' => 2, 'groupname' => 'Торцевые планки', 'colored' => true],
            ['id' => 3, 'groupname' => 'Кровельные саморезы', 'colored' => true],
            ['id' => 4, 'groupname' => 'Профтруба', 'colored' => false]
        ];
        foreach ($it_groups as $it_group) { ItemGroup::create($it_group); }
    }
}
