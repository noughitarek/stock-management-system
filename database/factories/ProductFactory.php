<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Rubrique;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $rubriques = Rubrique::pluck('id')->toArray();
        return [
            'designation' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'rubrique' => $rubriques[array_rand($rubriques)],
            'pictures' => null,
            'created_by' => $users[array_rand($users)],
            'updated_by' => $users[array_rand($users)],
            'deleted_by' => null,
            'deleted_at' => null,
        ];
    }
}
