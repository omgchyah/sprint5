<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $dice1 = fake()->numberBetween(1, 9);
        $dice2 = fake()->numberBetween(1, 9);
        $result = ($dice1 + $dice2 == 7) ? 'W' : 'L';


        return [
            'customer_id' => User::factory(),
            'dice1' => $this->$dice1,
            'dice2' => $this->$dice2,
            'result' => $this->$result
        ];
    }
}
