<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'responsible_name' => $this->faker->name,
            'responsible_phone' => $this->faker->phoneNumber,
            'created_by' => $users[array_rand($users)],
            'updated_by' => $users[array_rand($users)],
            'deleted_by' => null,
            'deleted_at' => null,
        ];
    }
}
