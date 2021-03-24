<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Language;
use Illuminate\Database\Seeder;

class CountryTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = json_decode(file_get_contents(__DIR__ . '/data/countries/en.json'), true);
        $trs = json_decode(file_get_contents(__DIR__ . '/data/countries/fr.json'), true);

        $fr = Language::whereIsoCode("fr")->first();

        foreach ($countries as $country) {
            $r = Country::create($country);
            foreach ($trs as $tr) {
                if ($r->iso_code == $tr['iso_code']) {
                    $r->translations()->create([
                        'data' => json_encode($tr),
                        'language_id' => $fr->id
                    ]);
                }
            }
        }
    }
}
