<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // mot de passe par défaut
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Attribuer le rôle 'client' après la création.
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('client');
        });
    }
}
