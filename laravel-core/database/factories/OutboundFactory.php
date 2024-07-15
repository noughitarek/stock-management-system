<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use App\Models\Outbound;
use App\Models\OutboundProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Outbound>
 */
class OutboundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $services = Service::pluck('id')->toArray();
        return [
            "internal_delivery_note_number" => $this->faker->numberBetween(1, 50),
            "service" => $services[array_rand($services)],
            "created_by" => $users[array_rand($users)],
            'updated_by' => $users[array_rand($users)],
            'deleted_by' => null,
            'deleted_at' => null,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Outbound $outbound) {
            $products = Product::pluck('id')->toArray();
            for ($i = 0; $i < rand(1, 10); $i++) {
                OutboundProduct::create([
                    "product" => $products[array_rand($products)],
                    "unit_price_excl_tax" => $this->faker->randomFloat(2, 10, 100),
                    "unit_price_net" => $this->faker->randomFloat(2, 10, 100),
                    "qte" => $this->faker->numberBetween(1, 100),
                    "total_amount_excl_tax" => $this->faker->randomFloat(2, 100, 1000),
                    "total_amount_net" => $this->faker->randomFloat(2, 100, 1000),
                    "outbound" => $outbound->id
                ]);
            }
        });
    }
}
