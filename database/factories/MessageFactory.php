<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{

    public function definition(): array
    {
        return [
            'recipient_id'=>User::all()->random()->id,
            'sender_id'=>User::all()->random()->id,
            'content'=>fake()->sentence(5),
        ];
    }
}
