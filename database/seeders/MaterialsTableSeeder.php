<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Material::count()) { $this->addMaterials(); }
    }

    static materials = [
        ['material' => 'Цинк', 'thickness' => 0.35, 'color' => '#000000', 'actual' => true, 'type' => 1 ],
        ['material' => 'Цинк', 'thickness' => 0.4,  'color' => '#000000', 'actual' => true, 'type' => 1  ],
        ['material' => 'Цинк', 'thickness' => 0.45, 'color' => '#000000', 'actual' => true, 'type' => 1  ],
        ['material' => 'RAL8017', 'thickness' => 0.35, 'color' => '#000000', 'actual' => true, 'type' => 2  ],
        ['material' => 'RAL8017', 'thickness' => 0.4, 'color' => '#000000', 'actual' => true, 'type' => 2  ],
        ['material' => 'RAL8017', 'thickness' => 0.45, 'color' => '#000000', 'actual' => true, 'type' => 2  ]
    ]

    public function addMaterials(){
    }
}
