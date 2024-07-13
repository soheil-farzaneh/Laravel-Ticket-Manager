<?php

namespace Database\Factories;

use App\Models\BankAccount;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{

    protected $model = BankAccount::class;

    

    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        return [
            'fullname' => $faker->name,
            'iban' => $faker->iban,
            'bank_id' => $faker->uuid,
            'user_id' => $faker->uuid,
            'type' => $faker->randomElement(['self', 'partner']),
            'status' => $faker->randomElement(['rejected', 'accepted', 'pending', 'removed']), // Corrected typo
        ];
    }
}
