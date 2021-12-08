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

    public function addMaterials(){
        $mDescrs = [
            ['material' => 'Цинк', 'thickness' => 0.35, 'color' => '#CDCDCD', 'actual' => true, 'mtype' => 1 ],
            ['material' => 'Цинк', 'thickness' => 0.4,  'color' => '#CDCDCD', 'actual' => true, 'mtype' => 1  ],
            ['material' => 'Цинк', 'thickness' => 0.45, 'color' => '#CDCDCD', 'actual' => true, 'mtype' => 1  ],
            ['material' => 'RAL8017', 'thickness' => 0.35, 'color' => '#534542', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL8017', 'thickness' => 0.4, 'color' => '#534542', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL8017', 'thickness' => 0.45, 'color' => '#534542', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL8017', 'thickness' => 0.5, 'color' => '#534542', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL9003', 'thickness' => 0.45, 'color' => '#F7FAFC', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL3005', 'thickness' => 0.45, 'color' => '#663D43', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL6005', 'thickness' => 0.45, 'color' => '#365148', 'actual' => true, 'mtype' => 2  ],
            ['material' => 'RAL7004', 'thickness' => 0.45, 'color' => '#CDCDCE', 'actual' => false, 'mtype' => 2  ],
            ['material' => 'RAL7024', 'thickness' => 0.45, 'color' => '#56575A', 'actual' => false, 'mtype' => 2  ],
            ['material' => 'RAL5005', 'thickness' => 0.45, 'color' => '#2B5D8E', 'actual' => false, 'mtype' => 2  ]




        ];
        foreach ($mDescrs as $mDesc) {
            Material::create($mDesc);
        }
    }
}
