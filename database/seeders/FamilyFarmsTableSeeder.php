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
            'address' => 'Borisa Papandopula 1',
            'place' => 'Sesvete',
            'oib' => '66321597535',
            'owner_id' => 1,
        ]);
    }
}
