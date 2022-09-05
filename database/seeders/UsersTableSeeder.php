<?php

namespace Database\Seeders;

use App\Models\App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'firstname' => 'Karlo',
            'lastname' => 'Stjepanović',
            'username' => 'karlo.stjepanovic',
            'password' => Hash::make('1234'),
            'email' => 'karlo.stjepanovic3@gmail.com',
            'phone' => '092 346 8604',
            'admin_role' => true,
            'otp_token' => 'U6PALZ7FIN4HVXWZ'
        ]);
    }
}
