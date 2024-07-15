<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Inbound;
use App\Models\Product;
use App\Models\Service;
use App\Models\Outbound;
use App\Models\Rubrique;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Tarek NOUGHI',
            'email' => 'noughitarek@gmail.com',
            'password' => Hash::make('password'),
        ]);
        Rubrique::factory()->count(10)->create();
        Product::factory()->count(10)->create();
        Service::factory()->count(10)->create();
        Supplier::factory()->count(10)->create();
        Inbound::factory()->count(100)->create();
        Outbound::factory()->count(100)->create();
    }
}
