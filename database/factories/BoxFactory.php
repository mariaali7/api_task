<?php

namespace Database\Factories;

use App\Models\Box;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoxFactory extends Factory
{
    protected $model = Box::class;

    public function definition()
    {
        // Get a random user ID from the users table
        $userIds = User::pluck('id')->toArray();

        return [
            'delivery_date' => $this->faker->date(),
            'user_id' => $this->faker->randomElement($userIds),
        ];
    }
}