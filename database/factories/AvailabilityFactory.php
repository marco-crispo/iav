<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    public $status = [
        'I feel lonely' => 'Descrizione I feel lonely',
        'I need to chat' => 'Descrizione I need to chat',
        'Let\'s get to know each other' => 'Descrizione Let\'s get to know each other',
        'Buy me a coffee!' => 'Descrizione Buy me a coffee!',
        'Surprise me!' => 'Descrizione Surprise me!',
        'Looking for something' => 'Descrizione Looking for something',
        'Looking for something more' => 'Descrizione Looking for something more',
        'Shall we take a room?' => 'Descrizione Shall we take a room?',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rndStatus = array_rand($this->status);
        $rndDescription = $this->status[$rndStatus];

        return [
            'status' => $rndStatus,
            'description' => $rndDescription,
            'active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
