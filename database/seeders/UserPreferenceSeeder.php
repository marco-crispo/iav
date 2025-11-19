<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserPreference;
use App\Models\User;
use Faker\Factory;

class UserPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 user positions with random coordinates
        // UserPreference::factory()->count(50)->create();

        $faker = Factory::create();
        User::all()->each(function ($user) use ($faker) {

            UserPreference::factory()->create([
                'user_id' => $user->id,
                'preference_key' => $faker->word(),
                'preference_value' => $faker->sentence(),
                'is_active' => $faker->boolean(80),
            ]);
        });
    }
}
