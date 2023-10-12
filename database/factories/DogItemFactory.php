<?php

namespace Database\Factories;

use App\Models\DogItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DogItem>
 */
class DogItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DogItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'DogName' => $this->faker->name,
            'DogSpecies' => $this->faker->randomDigit,
            'Price' => $this->faker->randomNumber(2),
            'Color' => $this->faker->colorName,
            'Sex' => $this->faker->randomElement(['Male', 'Female']),
            'Age' => $this->faker->numberBetween(1, 10),
            'Origin' => $this->faker->country,
            'HealthStatus' => $this->faker->word,
            'Description' => $this->faker->sentence,
            'Images' => json_encode([$this->faker->imageUrl(), $this->faker->imageUrl()]),
            'IsInStock' => $this->faker->boolean
        ];
    }
}
