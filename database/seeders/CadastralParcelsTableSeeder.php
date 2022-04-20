<?php

namespace Database\Seeders;

use App\Models\FamilyFarm\CadastralParcel;
use Illuminate\Database\Seeder;

class CadastralParcelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CadastralParcel::create([
            'number' => '413/1',
            'arcode' => 'HR56372',
            'area' => '2120',
            'community' => 'Sesvete',
            'family_farm_id' => 1,
        ]);
        CadastralParcel::create([
            'number' => '413/2',
            'arcode' => 'HR56373',
            'area' => '3050',
            'community' => 'Sesvete',
            'family_farm_id' => 1,
        ]);
        CadastralParcel::create([
            'number' => '414',
            'arcode' => 'HR56374',
            'area' => '41800',
            'community' => 'Sesvete',
            'family_farm_id' => 2,
        ]);
        CadastralParcel::create([
            'number' => '415/1',
            'arcode' => 'HR56375',
            'area' => '6700',
            'community' => 'Sesvete',
            'family_farm_id' => 2,
        ]);
        CadastralParcel::create([
            'number' => '415/2',
            'arcode' => 'HR56376',
            'area' => '3855',
            'community' => 'Sesvete',
            'family_farm_id' => 2,
        ]);
    }
}
