<?php

namespace Database\Seeders;

use App\Models\App\Culture;
use Illuminate\Database\Seeder;

class CulturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Culture::create([
            'name' => 'Kukuruz',
        ]);
        Culture::create([
            'name' => 'Pšenica',
        ]);
        Culture::create([
            'name' => 'Zob',
        ]);
        Culture::create([
            'name' => 'Soja',
        ]);
        Culture::create([
            'name' => 'Grašak',
        ]);
        Culture::create([
            'name' => 'Ječam',
        ]);
        Culture::create([
            'name' => 'Jabuka',
        ]);
        Culture::create([
            'name' => 'Kruška',
        ]);
        Culture::create([
            'name' => 'Breskva',
        ]);
        Culture::create([
            'name' => 'Vinova loza',
        ]);
        Culture::create([
            'name' => 'Hmelj',
        ]);
        Culture::create([
            'name' => 'Krumpir',
        ]);
        Culture::create([
            'name' => 'Maslina',
        ]);
        Culture::create([
            'name' => 'Mandarina',
        ]);
        Culture::create([
            'name' => 'Lijeska',
        ]);
        Culture::create([
            'name' => 'Raž',
        ]);
        Culture::create([
            'name' => 'Lubenica',
        ]);
        Culture::create([
            'name' => 'Bundeva',
        ]);
        Culture::create([
            'name' => 'Jagoda',
        ]);
        Culture::create([
            'name' => 'Luk',
        ]);
        Culture::create([
            'name' => 'Salata glavatica',
        ]);
        Culture::create([
            'name' => 'Krastavac',
        ]);
        Culture::create([
            'name' => 'Dinja',
        ]);
        Culture::create([
            'name' => 'Rajčica',
        ]);
        Culture::create([
            'name' => 'Orah',
        ]);
        Culture::create([
            'name' => 'Kupus',
        ]);
        Culture::create([
            'name' => 'Smokva',
        ]);
        Culture::create([
            'name' => 'Oleander',
        ]);
        Culture::create([
            'name' => 'Sjemenski krumpir',
        ]);
        Culture::create([
            'name' => 'Šećerna repa',
        ]);
        Culture::create([
            'name' => 'Šljiva',
        ]);
    }
}
