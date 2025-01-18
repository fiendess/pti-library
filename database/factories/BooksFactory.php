<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Books>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
         'title' => $this->faker->sentence,
         'author' => $this->faker->name,
         'description' => $this->faker->paragraph,
         'publisher' => $this->faker->company,
         'publish_date' => $this->faker->date,
         'isbn' => $this->faker->isbn13,
         'cover_image' => $this->faker->imageUrl,
         'quantity' => $this->faker->numberBetween(1, 100),
         'availability' => $this->faker->boolean,
       ];
    }
}
