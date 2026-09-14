<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AccountBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountBlockExemptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_not_blocked_after_multiple_failed_login_attempts(): void
    {
        $admin = User::create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin_test@chapconnect.com',
            'password' => Hash::make('password123456'),
            'role' => 'admin',
        ]);

        // Attempt login 5 times with wrong password
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'login' => 'superadmin_test@chapconnect.com',
                'password' => 'wrongpassword',
            ]);
        }

        $admin->refresh();

        // Verify admin is NOT blocked
        $this->assertFalse((bool)$admin->is_blocked);
        $this->assertDatabaseMissing('account_blocks', [
            'user_id' => $admin->id,
            'status' => 'blocked',
        ]);
    }

    public function test_blocked_admin_is_automatically_unblocked_on_login(): void
    {
        $admin = User::create([
            'name' => 'Blocked Admin',
            'email' => 'blocked_admin@chapconnect.com',
            'password' => Hash::make('correct_password'),
            'role' => 'admin',
            'is_blocked' => true,
        ]);

        AccountBlock::create([
            'user_id' => $admin->id,
            'attempts_count' => 4,
            'time_interval' => '5 minutes',
            'status' => 'blocked',
        ]);

        // Attempt login with correct password
        $response = $this->post('/login', [
            'login' => 'blocked_admin@chapconnect.com',
            'password' => 'correct_password',
        ]);

        $admin->refresh();

        // Verify admin is automatically unblocked and logged in successfully
        $this->assertFalse((bool)$admin->is_blocked);
        $this->assertDatabaseHas('account_blocks', [
            'user_id' => $admin->id,
            'status' => 'unblocked',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
    }
}
