<?php

namespace Database\Factories;

use App\Models\App;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = App::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company;
        $type = Arr::random(["app", "game"])[0];
        return [
            'title' => $name,
            'iap' => $this->faker->boolean(),
            'email' => $this->faker->unique()->companyEmail,
            'developper_id' => random_int(1, 30),
            'category_id' => $type == "app" ? random_int(1, 32) : random_int(33, 48),
            'price' => random_int(0, 99),
            'short_desc' => $this->faker->text,
            'description' => $this->faker->realText,
            'package_name' => 'com.' . Str::slug($name, '.'),
            "website" => $this->faker->url,
            "icon" => $this->faker->imageUrl(72, 72),
        ];
    }
}
