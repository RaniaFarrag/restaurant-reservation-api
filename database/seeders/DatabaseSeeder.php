<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Meal;
use App\Models\Table;
use App\Models\Customer;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'test123456',
        ]);

        Meal::factory()->create([
            'price' => '100',
            'description' => 'First Meal',
            'available_quantity' => 5,
            'discount' => '10',
        ]);

         Meal::factory()->create([
            'price' => '200',
            'description' => 'Second Meal',
            'available_quantity' => 10,
            'discount' => '15',
        ]);

        User::factory(5)->create();
        Meal::factory(5)->create();
        Table::factory(5)->create();
        Customer::factory(5)->create();

        
    }
}
