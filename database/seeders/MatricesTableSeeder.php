<?php

namespace Database\Seeders;

use App\Models\FamilyFarm\Matrix;
use Illuminate\Database\Seeder;

class MatricesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Matrix::create([
            'name' => 'Voćnjak',
            'culture_id' => 7,
            'cadastral_parcel_id' => 3,
            'tracking_type' => 'k',
        ]);
        Matrix::create([
            'name' => 'Polje #1',
            'culture_id' => 1,
            'cadastral_parcel_id' => 4,
            'tracking_type' => 'k',
        ]);
        Matrix::create([
            'name' => 'Polje #2',
            'culture_id' => 2,
            'cadastral_parcel_id' => 5,
            'tracking_type' => 'k',
        ]);
    }
}
