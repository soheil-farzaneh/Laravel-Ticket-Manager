<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        return [
            'fullname' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->unique()->phoneNumber,
            'password' => bcrypt('password'), 
            'avatar' => $faker->imageUrl(), 
            'avatar_color' => $faker->hexColor,
            'activation_code' => $faker->randomNumber(4),
            'telegram_id' => $faker->randomNumber(8),
            'twoverify' => $faker->randomElement(['active', 'unactive']),
            'opts' => $faker->word,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
