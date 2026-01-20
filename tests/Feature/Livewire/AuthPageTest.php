<?php

namespace Tests\Feature\Livewire;

use App\Livewire\AuthPage;
use App\Models\UserAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthPageTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Test: Component Renders
    // =========================================================================

    public function test_auth_page_component_renders(): void
    {
        Livewire::test(AuthPage::class)
            ->assertStatus(200);
    }

    public function test_auth_page_can_be_initialized_in_login_mode(): void
    {
        Livewire::test(AuthPage::class, ['initialMode' => 'login'])
            ->assertSet('mode', 'login');
    }

    public function test_auth_page_can_be_initialized_in_register_mode(): void
    {
        Livewire::test(AuthPage::class, ['initialMode' => 'register'])
            ->assertSet('mode', 'register');
    }

    // =========================================================================
    // Test: Mode Switching
    // =========================================================================

    public function test_switch_mode_changes_from_login_to_register(): void
    {
        Livewire::test(AuthPage::class)
            ->set('mode', 'login')
            ->call('switchMode', 'register')
            ->assertSet('mode', 'register');
    }

    public function test_switch_mode_resets_form_fields(): void
    {
        Livewire::test(AuthPage::class)
            ->set('email', 'test@test.com')
            ->set('password', 'password')
            ->call('switchMode', 'register')
            ->assertSet('email', '')
            ->assertSet('password', '');
    }

    // =========================================================================
    // Test: Login Action
    // =========================================================================

    public function test_login_with_valid_credentials_succeeds(): void
    {
        $user = UserAccount::factory()->create([
            'email' => 'test@student.unsika.ac.id',
            'password' => Hash::make('Password123!'),
        ]);

        Livewire::test(AuthPage::class)
            ->set('email', 'test@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->call('login')
            ->assertRedirect(route('profile.show'));
    }

    public function test_login_with_invalid_password_fails(): void
    {
        UserAccount::factory()->create([
            'email' => 'test@student.unsika.ac.id',
            'password' => Hash::make('Password123!'),
        ]);

        Livewire::test(AuthPage::class)
            ->set('email', 'test@student.unsika.ac.id')
            ->set('password', 'WrongPassword!')
            ->call('login')
            ->assertHasErrors('email');
    }

    public function test_login_validates_email_format(): void
    {
        Livewire::test(AuthPage::class)
            ->set('email', 'invalid-email')
            ->set('password', 'Password123!')
            ->call('login')
            ->assertHasErrors('email');
    }

    public function test_login_requires_unsika_email_domain(): void
    {
        Livewire::test(AuthPage::class)
            ->set('email', 'test@gmail.com')
            ->set('password', 'Password123!')
            ->call('login')
            ->assertHasErrors('email');
    }

    public function test_unverified_user_is_redirected_after_login(): void
    {
        UserAccount::factory()->unverified()->create([
            'email' => 'unverified@student.unsika.ac.id',
            'password' => Hash::make('Password123!'),
        ]);

        Livewire::test(AuthPage::class)
            ->set('email', 'unverified@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->call('login')
            ->assertRedirect(route('verification.notice'));
    }

    // =========================================================================
    // Test: Register Action
    // =========================================================================

    public function test_register_with_valid_data_creates_user(): void
    {
        Livewire::test(AuthPage::class)
            ->set('mode', 'register')
            ->set('name', 'Test User')
            ->set('email', 'newuser@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register');

        $this->assertDatabaseHas('user_accounts', [
            'email' => 'newuser@student.unsika.ac.id',
        ]);
    }

    public function test_register_validates_name_required(): void
    {
        Livewire::test(AuthPage::class)
            ->set('mode', 'register')
            ->set('name', '')
            ->set('email', 'test@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertHasErrors('name');
    }

    public function test_register_validates_password_confirmation(): void
    {
        Livewire::test(AuthPage::class)
            ->set('mode', 'register')
            ->set('name', 'Test User')
            ->set('email', 'test@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'DifferentPassword!')
            ->call('register')
            ->assertHasErrors('password');
    }

    public function test_register_validates_unique_email(): void
    {
        UserAccount::factory()->create([
            'email' => 'existing@student.unsika.ac.id',
        ]);

        Livewire::test(AuthPage::class)
            ->set('mode', 'register')
            ->set('name', 'Test User')
            ->set('email', 'existing@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertHasErrors('email');
    }

    public function test_register_switches_to_login_mode_after_success(): void
    {
        Livewire::test(AuthPage::class)
            ->set('mode', 'register')
            ->set('name', 'Test User')
            ->set('email', 'switch@student.unsika.ac.id')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertSet('mode', 'login');
    }
}
