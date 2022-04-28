<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(FamilyFarmsTableSeeder::class);
        $this->call(CulturesTableSeeder::class);
        $this->call(SuppliesTableSeeder::class);
        $this->call(SupplyCulturesTableSeeder::class);
        $this->call(EmployeesTableSeeder::class);
        $this->call(CadastralParcelsTableSeeder::class);
        $this->call(FamilyFarmCulturesTableSeeder::class);
        $this->call(MatricesTableSeeder::class);
    }
}
