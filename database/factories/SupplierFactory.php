<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
            'name' => $this->faker->company,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'description' => $this->faker->paragraph,
            'created_by' => $users[array_rand($users)],
            'updated_by' => $users[array_rand($users)],
            'deleted_by' => null,
            'deleted_at' => null,
        ];
    }
}
