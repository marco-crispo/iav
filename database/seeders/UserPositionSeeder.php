<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use App\Models\UserPosition;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Arr;
use App\Models\Availability;

class UserPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 user positions with random coordinates
        // UserPosition::factory()->count(50)->create();

        $faker = Factory::create();
        User::all()->each(function ($user) use ($faker) {

            // UserPosition::factory()->create([
            //     'user_id' => $user->id,
            //     'latitude' => $faker->latitude(),
            //     'longitude' => $faker->longitude(),
            // ]);

            $user->location()->create([
                'latitude' => $faker->latitude(-90, 90, 14),
                'longitude' => $faker->longitude(-180, 180, 14),
            ]);

            $user->profile()->create([
                'availability_id' => Arr::random(Availability::pluck('id')->toArray()),
                'bio' => $faker->sentence(),
                'interests' => $faker->words(3, true),
                'city' => $faker->city(),
                'country' => $faker->country(),
                'relationship_status' => Arr::random([
                    'Single',
                    'In a relationship',
                    'In a open relationship',
                    'In more than one relationship',
                    'It\'s complicated',
                    'Married',
                    'Widowed',
                    'Divorced',
                    'Separated',
                    'dontwannasay'
                ]),
                'birthdate' => $faker->date(),
                'sexual_orientation' => Arr::random(['Lesbian', 'Gay', 'Bisexual', 'Transgender', 'Queer', 'Intersex', 'Asexual', '+','dontwannasay', 'Hetero']),
                'name' => $faker->name(),
                'surname' => $faker->lastName(),
                'avatar' => 'uploads/avatar_11.jpg',
                'cover_photo' => 'uploads/cover_photo_11.jpg',
                'languages' => Arr::join(Arr::random(['Italian', 'English', 'Spanish', 'French', 'German', 'Chinese', 'Japanese', 'Korean'], 2), ', '),
            ]);
        });
    }
}
