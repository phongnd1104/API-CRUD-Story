<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'background' => $this->faker->sentence(3),
            'story_id' => rand(1,50),
            'created_at' => rand(10,1000),
            'updated_at' => rand(10,1000)
        ];
    }
}
