<?php

namespace Database\Seeders;

use App\Models\FamilyFarm\FamilyFarmCulture;
use Illuminate\Database\Seeder;

class FamilyFarmCulturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 1
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 7
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 8
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 10
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 27
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 30
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 1,
            'culture_id' => 31
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 2,
            'culture_id' => 1
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 2,
            'culture_id' => 2
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 2,
            'culture_id' => 3
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 2,
            'culture_id' => 4
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 2,
            'culture_id' => 6
        ]);
        FamilyFarmCulture::create([
            'family_farm_id' => 2,
            'culture_id' => 7
        ]);
    }
}
