<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\TalentPaymentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPaymentPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_manage_payment_methods(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin_pm_test@chapconnect.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // 1. Create Payment Method
        $response = $this->post(route('admin.payment-methods.store'), [
            'company' => 'CRDB Bank',
            'account_name' => 'CHAPCONNECT LIMITED',
            'account_number' => '0150123456700',
            'instructions' => 'CRDB SimBanking instruction',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payment_methods', [
            'company' => 'CRDB Bank',
            'account_number' => '0150123456700',
        ]);

        $pm = PaymentMethod::where('company', 'CRDB Bank')->first();

        // 2. Update Payment Method
        $this->post(route('admin.payment-methods.update', $pm->id), [
            'company' => 'CRDB Bank Updated',
            'account_name' => 'CHAPCONNECT LTD',
            'account_number' => '0150999999900',
            'instructions' => 'Updated instruction',
            'is_active' => '1',
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'id' => $pm->id,
            'company' => 'CRDB Bank Updated',
        ]);
    }

    public function test_new_talent_cannot_publish_without_confirmed_payment(): void
    {
        $talent = User::create([
            'name' => 'New Unpaid Talent',
            'email' => 'unpaid_talent@chapconnect.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'category' => 'musician',
            'category_label' => 'Musician / Artist',
            'description' => 'Professional musician bio and background details.',
            'phone' => '0712345678',
            'country' => 'Tanzania',
            'profile_image' => 'https://via.placeholder.com/150',
            'is_published' => false,
            'created_at' => now(), // Registered today
        ]);

        $this->actingAs($talent);

        // Attempt publish
        $response = $this->post(route('dashboard.publish'));

        $talent->refresh();

        // Verify account remains unpublished
        $this->assertFalse((bool)$talent->is_published);
        $response->assertSessionHas('error');
    }

    public function test_talent_can_publish_after_payment_is_confirmed(): void
    {
        $talent = User::create([
            'name' => 'Paid Talent',
            'email' => 'paid_talent@chapconnect.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'category' => 'musician',
            'category_label' => 'Musician / Artist',
            'description' => 'Professional musician bio and background details.',
            'phone' => '0712345678',
            'country' => 'Tanzania',
            'profile_image' => 'https://via.placeholder.com/150',
            'is_published' => false,
            'created_at' => now(),
        ]);

        // Create paid payment request for talent
        TalentPaymentRequest::create([
            'user_id' => $talent->id,
            'amount' => 10000.00,
            'status' => 'paid',
            'payment_reference' => 'REF-PAID-100',
            'likes_count' => 100,
            'followers_count' => 50,
            'comments_count' => 20,
            'views_count' => 500,
        ]);

        $this->actingAs($talent);

        // Attempt publish
        $response = $this->post(route('dashboard.publish'));

        $talent->refresh();

        // Verify account is now live and published
        $this->assertTrue((bool)$talent->is_published);
        $response->assertSessionHas('success');
    }
}
