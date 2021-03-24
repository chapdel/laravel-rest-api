<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $locales = json_decode(file_get_contents(__DIR__ . '/data/locales/en.json'), true);
        $translations = json_decode(file_get_contents(__DIR__ . '/data/locales/fr.json'), true);


        foreach ($locales as $en) {
            $item = explode('_', $en['iso_code']);
            $en['iso_639_1'] = $item[0];
            $en['active'] = 1;
            Language::create($en);
        }

        $lfr = Language::whereIsoCode("fr")->first();

        foreach ($translations as $fr) {

            $lang = Language::whereIsoCode($fr["iso_code"])->first();
            $lang->translations()->create([
                'data' => json_encode($fr),
                'language_id' => $lfr->id
            ]);
        }
    }
}
