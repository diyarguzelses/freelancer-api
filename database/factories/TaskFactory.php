<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'duration' => $this->faker->numberBetween(10, 300),
            'status' => $this->faker->randomElement([0, 1, 2]), // 0: todo, 1: in progress, 2: done
            'user_id' => User::inRandomOrder()->first()->id, // Rastgele bir kullanıcı
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
