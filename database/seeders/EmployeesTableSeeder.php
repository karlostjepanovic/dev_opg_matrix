<?php

namespace Database\Seeders;

use App\Models\FamilyFarm\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::create([
            'family_farm_id' => 1,
            'user_id' => 2,
            'admin_role' => true
        ]);
        Employee::create([
            'family_farm_id' => 1,
            'user_id' => 3,
            'admin_role' => true
        ]);
        Employee::create([
            'family_farm_id' => 1,
            'user_id' => 4,
        ]);
        Employee::create([
            'family_farm_id' => 1,
            'user_id' => 5,
        ]);
        Employee::create([
            'family_farm_id' => 2,
            'user_id' => 1,
            'admin_role' => true
        ]);
        Employee::create([
            'family_farm_id' => 2,
            'user_id' => 3,
            'admin_role' => true
        ]);
        Employee::create([
            'family_farm_id' => 2,
            'user_id' => 5,
        ]);
        Employee::create([
            'family_farm_id' => 2,
            'user_id' => 6,
        ]);
        Employee::create([
            'family_farm_id' => 3,
            'user_id' => 5,
        ]);
    }
}
