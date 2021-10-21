<?php

namespace Database\Seeders;

use App\Models\OrderLine;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderLineTableSeeder extends Seeder
{
    public function run()
    {
        if (! OrderLine::count()) { $this->addSampleOL(); }
    }

    public function addSampleOL(){
        $ols = [
            ['order_id' => 1, 'material_id' => 1, 'product_id' => 1, 'item_id' => 0, 'length' => 1000, 'amount' => 3 ],
            ['order_id' => 1, 'material_id' => 2, 'product_id' => 2, 'item_id' => 0, 'length' => 2000, 'amount' => 5 ],
            ['order_id' => 1, 'material_id' => 4, 'product_id' => 4, 'item_id' => 0, 'length' => 1800, 'amount' => 7 ],
            ['order_id' => 2, 'material_id' => 3, 'product_id' => 1, 'item_id' => 0, 'length' => 1000, 'amount' => 2 ],
            ['order_id' => 2, 'material_id' => 5, 'product_id' => 2, 'item_id' => 0, 'length' => 2000, 'amount' => 4 ],
            ['order_id' => 2, 'material_id' => 6, 'product_id' => 5, 'item_id' => 0, 'length' => 1800, 'amount' => 6 ]
        ];
        foreach ($ols as $ol) {
            $prd = OrderLine::create($ol);
        }
    }
}
