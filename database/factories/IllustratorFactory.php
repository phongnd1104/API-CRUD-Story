<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Illustrator>
 */
class IllustratorFactory extends Factory
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
            'birthday' =>$this->faker->date(),
            'gender' => $this->faker->randomElement(['woman','man']),
            'created_at' => rand(10,1000),
            'updated_at' => rand(10,1000)
        ];
    }
}
