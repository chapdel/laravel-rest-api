<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = json_decode(file_get_contents(__DIR__ . '/data/categories/en.json'), true);
        $categories_fr = json_decode(file_get_contents(__DIR__ . '/data/categories/fr.json'), true);

        $fr = Language::whereIsoCode("fr")->first();

        foreach ($categories as $key => $category) {
            $r = Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'type' => isset($category['type']) ? "game" : "app"
            ]);

            $r->translations()->create([
                'language_id' => $fr->id,
                "data" => json_encode([
                    "name" => $categories_fr[$key]["name"]
                ])
            ]);
        }
    }
}
