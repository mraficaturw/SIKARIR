<?php

namespace Database\Factories;

use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAccount>
 */
class UserAccountFactory extends Factory
{
    protected $model = UserAccount::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('Password123!'),
            'avatar' => null,
        ];
    }

    /**
     * State untuk user yang belum verifikasi email.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State untuk user dengan email UNSIKA.
     */
    public function unsika(): static
    {
        return $this->state(fn(array $attributes) => [
            'email' => fake()->userName() . '@student.unsika.ac.id',
        ]);
    }

    /**
     * State untuk user dengan avatar.
     */
    public function withAvatar(): static
    {
        return $this->state(fn(array $attributes) => [
            'avatar' => 'avatars/test_avatar_' . time() . '.webp',
        ]);
    }
}
