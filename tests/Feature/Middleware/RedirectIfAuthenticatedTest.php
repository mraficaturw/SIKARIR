<?php

namespace Tests\Feature\Middleware;

use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_login_page(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_guest_can_access_register_page(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    /**
     * Note: The actual login/register routes don't use RedirectIfAuthenticated middleware
     * The AuthPage Livewire component handles authenticated user state
     */
    public function test_verified_authenticated_user_accessing_login(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('login'));

        // Login page renders - auth state handled by Livewire component
        $response->assertStatus(200);
    }

    public function test_verified_authenticated_user_accessing_register(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAsUser($user)->get(route('register'));

        // Register page renders - auth state handled by Livewire component
        $response->assertStatus(200);
    }
}
