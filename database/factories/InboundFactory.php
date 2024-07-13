<?php

namespace Database\Factories;

use App\Models\Inbound;
use App\Models\InboundProduct;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InboundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::pluck('id')->toArray();
        $suppliers = Supplier::pluck('id')->toArray();

        return [
            "commande_note_number" => $this->faker->numberBetween(1, 50),
            "delivery_note_number" => $this->faker->numberBetween(1, 50),
            "invoice_number" => $this->faker->numberBetween(1, 50),
            "supplier" => $suppliers[array_rand($suppliers)],
            "created_by" => $users[array_rand($users)],
            'updated_by' => $users[array_rand($users)],
            'deleted_by' => null,
            'deleted_at' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Inbound $inbound) {
            $products = Product::pluck('id')->toArray();
            for ($i = 0; $i < rand(1, 10); $i++) {
                InboundProduct::create([
                    "product" => $products[array_rand($products)],
                    "unit_price_excl_tax" => $this->faker->randomFloat(2, 10, 100),
                    "unit_price_net" => $this->faker->randomFloat(2, 10, 100),
                    "qte" => $this->faker->numberBetween(1, 100),
                    "total_amount_excl_tax" => $this->faker->randomFloat(2, 100, 1000),
                    "total_amount_net" => $this->faker->randomFloat(2, 100, 1000),
                    "inbound" => $inbound->id
                ]);
            }
        });
    }
}
