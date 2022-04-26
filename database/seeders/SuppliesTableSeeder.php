<?php

namespace Database\Seeders;

use App\Models\App\Supply;
use Illuminate\Database\Seeder;

class SuppliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Supply::create([
            'name' => 'Aliette false',
            'manufacturer' => 'Bayer AG',
            'description' => 'Sistemični preventivni i kurativni fungicid.',
            'operation_type' => 'zaštita',
            'measure_unit' => 'L',
        ]);
        Supply::create([
            'name' => 'Basagran 480',
            'manufacturer' => 'Basf SE',
            'description' => 'Herbicid namijenjen za suzbijanje jednogodišnjih širokolisnih korova i smanjenje zakorovljenosti nekim višegodišnjim širokolisnim korovima u kukuruzu, soji, lucerni, djetelini, krumpiru, graški, grahu, pšenici, ječmu i raži.',
            'operation_type' => 'zaštita',
            'measure_unit' => 'g',
        ]);
        Supply::create([
            'name' => 'Bordoška juha caffaro 20 WP',
            'manufacturer' => 'Isagro S.p.A.',
            'description' => 'Kontaktni preventivni fungicid za suzbijanje biljnih bolesti.',
            'operation_type' => 'zaštita',
            'measure_unit' => 'g',
        ]);
        Supply::create([
            'name' => 'Bijelo ulje',
            'manufacturer' => 'Iskra Zelina kemijska incustrija d.o.o.',
            'description' => 'Kontaktni insekticid-ovicid za zimsko i ljetno tretiranje voćaka, za zimsku zaštitu vinove loze i za tretiranje na sjemenskom krumpiru.',
            'operation_type' => 'zaštita',
            'measure_unit' => 'L',
        ]);
    }
}
