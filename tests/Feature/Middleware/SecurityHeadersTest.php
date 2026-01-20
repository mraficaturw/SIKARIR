<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_contains_x_frame_options_header(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_response_contains_x_content_type_options_header(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_response_contains_x_xss_protection_header(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    public function test_response_contains_referrer_policy_header(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_response_contains_permissions_policy_header(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertHeader('Permissions-Policy');
    }

    public function test_response_contains_content_security_policy_header(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertHeader('Content-Security-Policy');
    }
}
