<?php

namespace Database\Seeders;

use App\Models\App\FamilyFarm;
use Illuminate\Database\Seeder;

class FamilyFarmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FamilyFarm::create([
            'name' => 'OPG Test',
            'address' => 'Zagreb',
            'place' => 'Zagreb',
            'oib' => '68895955464',
            'owner_id' => 2,
        ]);
        FamilyFarm::create([
            'name' => 'Testni OPG Zagreb - istok',
            'address' => 'Zagreb',
            'place' => 'Zagreb',
            'oib' => '18265931218',
            'owner_id' => 1,
        ]);
        FamilyFarm::create([
            'name' => 'Testni OPG Zagreb - zapad',
            'address' => 'Zagreb',
            'place' => 'Zagreb',
            'oib' => '88961614461',
            'owner_id' => 5,
        ]);
    }
}
