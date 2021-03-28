<?php

namespace Database\Factories;

use App\Models\Developper;
use Illuminate\Database\Eloquent\Factories\Factory;

class DevelopperFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Developper::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->companyEmail,
            'country_id' => random_int(1, 250),
            'user_id' => random_int(1, 99),
            'bio' => $this->faker->text,
            'slug' => Developper::slug(),
            "website" => $this->faker->url,
            "poster" => $this->faker->imageUrl(),
            "backdrop" => $this->faker->imageUrl(),
        ];
    }
}
