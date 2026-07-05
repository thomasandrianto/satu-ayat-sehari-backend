<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'device_id' => fake()->uuid(),
            'push_token' => fake()->uuid(),
            'platform' => 'android',
            'last_seen_at' => now(),
        ];
    }
}