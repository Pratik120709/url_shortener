<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShortUrlFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'original_url' => $this->faker->url(),
            'short_code' => $this->faker->unique()->regexify('[A-Za-z0-9]{6}'),
            'clicks' => $this->faker->numberBetween(0, 1000),
            'expires_at' => $this->faker->optional()->dateTimeBetween('+1 week', '+1 year'),
            'is_active' => true,
        ];
    }
}
