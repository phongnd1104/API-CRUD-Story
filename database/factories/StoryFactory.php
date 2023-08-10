<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'course' => $this->faker->sentence(3),
            'project' => $this->faker->sentence(3),
            'type' => $this->faker->sentence(3),
            'author_id' => rand(1,10),
            'illustrator_id' => rand(1,10),
            'created_at' => rand(1,1000),
            'updated_at' => rand(1,1000)
        ];
    }
}
