<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MembershipPackageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default packages
        Package::create([
            'name' => 'Standard',
            'description' => 'Standard Free Tier',
            'package_type' => 'Free',
            'price' => 0.00,
            'duration' => 30,
            'duration_unit' => 'days',
            'phone_visibility' => 'No',
            'max_images' => 5,
            'max_videos' => 2,
            'max_news' => 3,
            'status' => 'Active'
        ]);

        Package::create([
            'name' => 'Premium',
            'description' => 'Premium Tier',
            'package_type' => 'To Pay',
            'price' => 20000.00,
            'duration' => 30,
            'duration_unit' => 'days',
            'phone_visibility' => 'Yes',
            'max_images' => 20,
            'max_videos' => 10,
            'max_news' => 15,
            'status' => 'Active'
        ]);
    }

    /**
     * Test phone privacy enforcement.
     */
    public function test_standard_user_phone_is_private_to_public()
    {
        // Create standard user (Standard is free default assigned on creation)
        $user = User::create([
            'name' => 'Talent Standard',
            'email' => 'standard@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0712345678',
            'country' => 'Tanzania',
            'category' => 'musician',
            'is_published' => true
        ]);

        // Access profile public route as guest
        $response = $this->get(route('profile', $user->id));
        $response->assertStatus(200);

        // Verify phone is not visible
        $this->assertFalse(str_contains($response->getContent(), '0712345678'));
    }

    /**
     * Test phone visibility for premium package user.
     */
    public function test_premium_user_phone_is_visible_to_public()
    {
        $user = User::create([
            'name' => 'Talent Premium',
            'email' => 'premium@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0712345678',
            'country' => 'Tanzania',
            'category' => 'musician',
            'is_published' => true
        ]);

        $premium = Package::where('name', 'Premium')->first();

        // Assign premium package
        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $premium->id,
            'package_name_snapshot' => $premium->name,
            'price_snapshot' => $premium->price,
            'duration_snapshot' => $premium->duration,
            'duration_unit_snapshot' => $premium->duration_unit,
            'phone_visibility_snapshot' => $premium->phone_visibility,
            'max_images_snapshot' => $premium->max_images,
            'max_videos_snapshot' => $premium->max_videos,
            'max_news_snapshot' => $premium->max_news,
            'package_type_snapshot' => $premium->package_type,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'active'
        ]);

        // Access profile as guest
        $response = $this->get(route('profile', $user->id));
        $response->assertStatus(200);

        // Verify phone is visible
        $this->assertTrue(str_contains($response->getContent(), '0712345678'));
    }

    /**
     * Test upload limit enforcement for Standard user.
     */
    public function test_standard_user_cannot_exceed_image_limits()
    {
        $user = User::create([
            'name' => 'Talent Standard Limit',
            'email' => 'standardlimit@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0712345678',
            'country' => 'Tanzania',
            'category' => 'musician',
            'is_published' => true
        ]);

        // Login user
        $this->actingAs($user);

        // Create 5 images (Standard limit is 5)
        for ($i = 0; $i < 5; $i++) {
            $user->media()->create([
                'type' => 'photo',
                'file_path' => '/uploads/test' . $i . '.jpg',
                'title' => 'Test Image ' . $i
            ]);
        }

        // Try to upload the 6th image
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->post(route('dashboard.photos.store'), [
            'photo_file' => $file,
            'title' => 'Excess Image'
        ]);

        // Should redirect back with error/warning
        $response->assertRedirect();
        $response->assertSessionHasErrors(['photo']);

        // Assert count remains 5
        $this->assertEquals(5, $user->media()->where('type', 'photo')->count());
    }

    /**
     * Test admin package creation.
     */
    public function test_admin_can_create_new_package()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $response = $this->post('/admin/packages', [
            'name' => 'VIP Gold',
            'description' => 'Unlimited Gold Tier',
            'package_type' => 'To Pay',
            'price' => 150000,
            'duration' => 12,
            'duration_unit' => 'months',
            'phone_visibility' => 'Yes',
            'max_images' => 999,
            'max_videos' => 999,
            'max_news' => 999,
            'status' => 'Active'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('packages', [
            'name' => 'VIP Gold',
            'price' => 150000
        ]);
    }

    /**
     * Test assigning package to a user and generating invoices.
     */
    public function test_admin_can_assign_package_which_generates_invoice()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $user = User::create([
            'name' => 'Talent Target',
            'email' => 'target@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $premium = Package::where('name', 'Premium')->first();

        $this->actingAs($admin);

        $response = $this->post("/admin/user/{$user->id}/assign-package", [
            'package_id' => $premium->id,
            'start_date' => now()->toDateString(),
            'months' => 2
        ]);

        $response->assertRedirect();
        
        // Assert UserPackage created
        $this->assertDatabaseHas('user_packages', [
            'user_id' => $user->id,
            'package_id' => $premium->id,
            'status' => 'active',
            'price_snapshot' => 40000.00
        ]);

        // Assert Invoice created
        $this->assertDatabaseHas('invoices', [
            'user_id' => $user->id,
            'amount' => 40000,
            'payment_status' => 'Unpaid'
        ]);
    }

    /**
     * Test recording invoice payment updates outstanding balance and status.
     */
    public function test_admin_can_log_invoice_payment()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $user = User::create([
            'name' => 'Talent Target',
            'email' => 'target@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // Create UserPackage snapshot manually
        $userPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => null,
            'package_name_snapshot' => 'VIP',
            'price_snapshot' => 50000.00,
            'duration_snapshot' => 30,
            'duration_unit_snapshot' => 'days',
            'phone_visibility_snapshot' => 'Yes',
            'max_images_snapshot' => 50,
            'max_videos_snapshot' => 25,
            'max_news_snapshot' => 30,
            'package_type_snapshot' => 'To Pay',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'status' => 'active'
        ]);

        // Create invoice manually
        $invoice = Invoice::create([
            'invoice_number' => 'INV-2026-000001',
            'user_id' => $user->id,
            'user_package_id' => $userPackage->id,
            'package_id' => null,
            'package_name' => 'VIP',
            'amount' => 50000,
            'amount_paid' => 0,
            'due_date' => now()->addDays(7),
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'payment_status' => 'Unpaid',
            'max_images' => 50,
            'max_videos' => 25,
            'max_news' => 30,
            'phone_visibility' => 'Yes',
            'duration' => 30,
            'duration_unit' => 'days',
            'invoice_date' => now()->toDateString()
        ]);

        $this->actingAs($admin);

        // Record partial payment
        $response1 = $this->post("/admin/invoices/{$invoice->id}/pay", [
            'amount_paid' => 20000,
            'payment_method' => 'Bank Transfer',
            'payment_reference' => 'TXN001',
            'notes' => 'First installment'
        ]);

        $response1->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'amount_paid' => 20000,
            'payment_status' => 'Partially Paid'
        ]);

        // Record final payment
        $response2 = $this->post("/admin/invoices/{$invoice->id}/pay", [
            'amount_paid' => 30000,
            'payment_method' => 'Cash Payment',
            'payment_reference' => 'TXN002',
            'notes' => 'Final payment'
        ]);

        $response2->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'amount_paid' => 50000,
            'payment_status' => 'Paid'
        ]);
    }

    /**
     * Test admin can delete package with no active users.
     */
    public function test_admin_can_delete_package_with_no_active_users()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->actingAs($admin);

        $vip = Package::create([
            'name' => 'VIP Gold',
            'description' => 'Unlimited Gold Tier',
            'package_type' => 'To Pay',
            'price' => 150000,
            'duration' => 12,
            'duration_unit' => 'months',
            'phone_visibility' => 'Yes',
            'max_images' => 999,
            'max_videos' => 999,
            'max_news' => 999,
            'status' => 'Active'
        ]);

        $response = $this->delete("/admin/packages/{$vip->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Package deleted successfully.');
        $this->assertDatabaseMissing('packages', ['id' => $vip->id]);
    }

    /**
     * Test admin cannot delete package with active users.
     */
    public function test_admin_cannot_delete_package_with_active_users()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $user = User::create([
            'name' => 'Talent Target',
            'email' => 'target@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $premium = Package::where('name', 'Premium')->first();

        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $premium->id,
            'package_name_snapshot' => $premium->name,
            'price_snapshot' => $premium->price,
            'duration_snapshot' => $premium->duration,
            'duration_unit_snapshot' => $premium->duration_unit,
            'phone_visibility_snapshot' => $premium->phone_visibility,
            'max_images_snapshot' => $premium->max_images,
            'max_videos_snapshot' => $premium->max_videos,
            'max_news_snapshot' => $premium->max_news,
            'package_type_snapshot' => $premium->package_type,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'active'
        ]);

        $this->actingAs($admin);

        $response = $this->delete("/admin/packages/{$premium->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cannot delete package because it has active users.');
        $this->assertDatabaseHas('packages', ['id' => $premium->id]);
    }
}
