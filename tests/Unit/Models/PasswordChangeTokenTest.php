<?php

namespace Tests\Unit\Models;

use App\Models\PasswordChangeToken;
use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PasswordChangeTokenTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: isExpired Method
    // =========================================================================

    public function test_is_expired_returns_false_for_future_expiry(): void
    {
        $user = UserAccount::factory()->create();

        $token = PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => 'hashed_password',
            'token' => 'test_token_123',
            'expires_at' => Carbon::now()->addHour(),
        ]);

        $this->assertFalse($token->isExpired());
    }

    public function test_is_expired_returns_true_for_past_expiry(): void
    {
        $user = UserAccount::factory()->create();

        $token = PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => 'hashed_password',
            'token' => 'test_token_123',
            'expires_at' => Carbon::now()->subMinute(),
        ]);

        $this->assertTrue($token->isExpired());
    }

    public function test_is_expired_returns_true_when_expired_exactly_now(): void
    {
        $user = UserAccount::factory()->create();

        // Freeze time
        Carbon::setTestNow(Carbon::now());

        $token = PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => 'hashed_password',
            'token' => 'test_token_123',
            'expires_at' => Carbon::now()->subSecond(),
        ]);

        $this->assertTrue($token->isExpired());

        Carbon::setTestNow(); // Reset
    }

    // =========================================================================
    // Test: User Relation
    // =========================================================================

    public function test_user_relation_returns_correct_user(): void
    {
        $user = UserAccount::factory()->create([
            'name' => 'Test User',
        ]);

        $token = PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => 'hashed_password',
            'token' => 'test_token_123',
            'expires_at' => Carbon::now()->addHour(),
        ]);

        $this->assertEquals('Test User', $token->user->name);
        $this->assertEquals($user->id, $token->user->id);
    }

    // =========================================================================
    // Test: Cast - expires_at as datetime
    // =========================================================================

    public function test_expires_at_is_cast_to_carbon(): void
    {
        $user = UserAccount::factory()->create();

        $token = PasswordChangeToken::create([
            'user_id' => $user->id,
            'new_password' => 'hashed_password',
            'token' => 'test_token_123',
            'expires_at' => '2026-12-31 23:59:59',
        ]);

        $this->assertInstanceOf(Carbon::class, $token->expires_at);
    }
}
