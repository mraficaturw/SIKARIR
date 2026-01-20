<?php

namespace Tests;

use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Create a verified user account for testing.
     */
    protected function createVerifiedUser(array $attributes = []): UserAccount
    {
        return UserAccount::factory()->create($attributes);
    }

    /**
     * Create an unverified user account for testing.
     */
    protected function createUnverifiedUser(array $attributes = []): UserAccount
    {
        return UserAccount::factory()->unverified()->create($attributes);
    }

    /**
     * Act as authenticated user with guard.
     */
    protected function actingAsUser(UserAccount $user): static
    {
        return $this->actingAs($user, 'user_accounts');
    }
}
