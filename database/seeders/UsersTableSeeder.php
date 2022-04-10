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
            'oib' => '66321597535',
            'admin_role' => true,
            'otp_token' => 'U6PALZ7FIN4HVXWZ'
        ]);
        User::create([
            'firstname' => 'Matrix',
            'lastname' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('1234'),
            'oib' => '92694019506',
            'admin_role' => true,
            'otp_token' => 'U6PALZ7FIN4HVXWZ'
        ]);
        User::create([
            'firstname' => 'Matrix',
            'lastname' => 'Test',
            'username' => 'test',
            'password' => Hash::make('1234'),
            'oib' => '46458542643'
        ]);
        User::create([
            'firstname' => 'Mirko',
            'lastname' => 'Slavko',
            'username' => 'mirko.slavko',
            'password' => Hash::make('1234'),
            'oib' => '46620721145'
        ]);
        User::create([
            'firstname' => 'Ivan',
            'lastname' => 'Horvat',
            'username' => 'ivan.horvat',
            'password' => Hash::make('1234'),
            'oib' => '29271446459'
        ]);
        User::create([
            'firstname' => 'Ivana',
            'lastname' => 'Prohaska',
            'username' => 'ivana.prohaska',
            'password' => Hash::make('1234'),
            'oib' => '89458713556'
        ]);
    }
}
