<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->text(),
            'status' => fake()->randomElement(['pendent', 'in-progress', 'completed']),
            'project_id' => null,
            'responsible_id' => null,
            'due_date' => fake()->date(),
        ];
    }
}
