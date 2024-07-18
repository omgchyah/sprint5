<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $role = fake()->randomElement(['user', 'guest']);

        if($role == 'user') {
            $name = fake()->unique->name();
            $nameArray = explode(' ', $name);
            $email = $nameArray[0] . (isset($nameArray[1]) ? $nameArray[1] : '') . fake()->randomNumber() . "@example.com";
        } else {
            $name = 'Anonymous';
            $email = fake()->unique()->email;
        }
  
        return [
            'nickname' => fake()->unique()->userName(),
            'name' => $name,
            'email' => strtolower($email),
            'email_verified_at' => now(),
            'role' => $role,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
