<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\AvailabilityFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->createOne([
            'email' => 'marco.crispo.71@gmail.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);
        User::factory(100)->create();

        $av = new AvailabilityFactory();
        foreach ($av->status as $status => $description) {
            Availability::factory()->create([
                'status' => $status,
                'description' => $description,
                'active' => true, // Assuming you want all created availabilities to be active
            ]);
        }
    }
}
