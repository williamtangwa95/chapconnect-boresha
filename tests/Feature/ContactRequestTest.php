<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\ContactRequest;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function createPrivateTalent(): User
    {
        $user = User::create([
            'name' => 'Mary Smith',
            'email' => 'mary@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0712345678',
            'country' => 'Tanzania',
            'category' => 'musician',
            'is_published' => true
        ]);

        // Assign Standard package (phone private)
        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => null,
            'package_name_snapshot' => 'Standard',
            'price_snapshot' => 0,
            'duration_snapshot' => 365,
            'duration_unit_snapshot' => 'days',
            'phone_visibility_snapshot' => 'No',
            'max_images_snapshot' => 5,
            'max_videos_snapshot' => 2,
            'max_news_snapshot' => 3,
            'package_type_snapshot' => 'Free',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(365)->toDateString(),
            'status' => 'active',
        ]);

        return $user;
    }

    /**
     * Test anonymous guest can submit request without logging in.
     */
    public function test_anonymous_guest_can_submit_contact_request()
    {
        $target = $this->createPrivateTalent();

        $response = $this->post(route('profile.connect', $target->id), [
            'requester_full_name' => 'John Doe',
            'contact_type' => 'whatsapp',
            'contact_value' => '+255712345678',
            'message' => 'I would like to connect with you.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_requests', [
            'target_user_id' => $target->id,
            'requester_user_id' => null,
            'requester_type' => 'guest',
            'requester_full_name' => 'John Doe',
            'contact_type' => 'whatsapp',
            'contact_value' => '+255712345678',
            'status' => 'Pending',
        ]);
    }

    /**
     * Test logged-in user can submit request as guest.
     */
    public function test_logged_in_user_submits_as_guest()
    {
        $target = $this->createPrivateTalent();

        $loggedInUser = User::create([
            'name' => 'Jane Visitor',
            'email' => 'jane@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'country' => 'Tanzania',
            'category' => 'dancer',
            'is_published' => true
        ]);

        $this->actingAs($loggedInUser);

        $response = $this->post(route('profile.connect', $target->id), [
            'requester_full_name' => 'Jane Visitor',
            'contact_type' => 'email',
            'contact_value' => 'jane@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check requester_user_id is stored but type is still "guest"
        $this->assertDatabaseHas('contact_requests', [
            'target_user_id' => $target->id,
            'requester_user_id' => $loggedInUser->id,
            'requester_type' => 'guest',
            'status' => 'Pending',
        ]);
    }

    /**
     * Test that target user receives a notification.
     */
    public function test_target_user_receives_notification_on_request()
    {
        $target = $this->createPrivateTalent();

        $this->post(route('profile.connect', $target->id), [
            'requester_full_name' => 'John Doe',
            'contact_type' => 'phone',
            'contact_value' => '+255712345678',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $target->id,
            'type' => 'ContactRequestNotification',
        ]);
    }

    /**
     * Test that admin/customer care staff receive notifications.
     */
    public function test_admin_and_customer_care_receive_notifications()
    {
        $target = $this->createPrivateTalent();

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        $cc = User::create([
            'name' => 'Care Agent',
            'email' => 'cc@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'customer_care'
        ]);

        $this->post(route('profile.connect', $target->id), [
            'requester_full_name' => 'John Doe',
            'contact_type' => 'whatsapp',
            'contact_value' => '+255712345678',
        ]);

        // Admin notification
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'ContactRequestNotification',
        ]);

        // Customer care notification
        $this->assertDatabaseHas('notifications', [
            'user_id' => $cc->id,
            'type' => 'ContactRequestNotification',
        ]);
    }

    /**
     * Test normal user with role "user" cannot access admin contact requests list.
     */
    public function test_normal_user_cannot_access_admin_contact_requests()
    {
        $target = $this->createPrivateTalent();

        $cr = ContactRequest::create([
            'target_user_id' => $target->id,
            'requester_user_id' => null,
            'requester_type' => 'guest',
            'requester_full_name' => 'John Doe',
            'contact_type' => 'whatsapp',
            'contact_value' => '+255712345678',
            'status' => 'Pending',
        ]);

        // A normal "user" tries to use the admin action route
        $normalUser = User::create([
            'name' => 'Normal Guy',
            'email' => 'normal@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'country' => 'Tanzania',
            'category' => 'singer',
        ]);

        $this->actingAs($normalUser);
        $response = $this->post("/admin/contact-requests/{$cr->id}/action", ['status' => 'Approved']);
        $response->assertStatus(403);
    }

    /**
     * Test target user can approve/reject requests addressed to them.
     */
    public function test_target_user_can_approve_own_request()
    {
        $target = $this->createPrivateTalent();

        $cr = ContactRequest::create([
            'target_user_id' => $target->id,
            'requester_user_id' => null,
            'requester_type' => 'guest',
            'requester_full_name' => 'John Doe',
            'contact_type' => 'whatsapp',
            'contact_value' => '+255712345678',
            'status' => 'Pending',
        ]);

        $this->actingAs($target);
        $response = $this->post(route('dashboard.contact-requests.action', $cr->id), ['action' => 'approve']);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_requests', [
            'id' => $cr->id,
            'status' => 'Approved',
        ]);
    }

    /**
     * Test IDOR: target user cannot approve requests addressed to other users.
     */
    public function test_idor_target_user_cannot_action_others_requests()
    {
        $target = $this->createPrivateTalent();

        $otherUser = User::create([
            'name' => 'Another Talent',
            'email' => 'other@chapconnect.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'country' => 'Tanzania',
            'category' => 'singer',
            'is_published' => true
        ]);

        // Create request for OTHER user, not this user
        $cr = ContactRequest::create([
            'target_user_id' => $otherUser->id,
            'requester_user_id' => null,
            'requester_type' => 'guest',
            'requester_full_name' => 'Attacker',
            'contact_type' => 'email',
            'contact_value' => 'attack@email.com',
            'status' => 'Pending',
        ]);

        // Login as target (not otherUser) and try to action request belonging to otherUser
        $this->actingAs($target);
        $response = $this->post(route('dashboard.contact-requests.action', $cr->id), ['action' => 'approve']);
        $response->assertStatus(403);
    }

    /**
     * Test private phone number never appears in public HTML response.
     */
    public function test_private_phone_never_appears_in_public_html()
    {
        $target = $this->createPrivateTalent();

        $response = $this->get(route('profile', $target->id));
        $response->assertStatus(200);

        // Private phone must NOT be in page HTML
        $this->assertFalse(str_contains($response->getContent(), '0712345678'));
    }

    /**
     * Test invalid contact type is rejected.
     */
    public function test_invalid_contact_type_is_rejected()
    {
        $target = $this->createPrivateTalent();

        $response = $this->post(route('profile.connect', $target->id), [
            'requester_full_name' => 'John Doe',
            'contact_type' => 'telegram', // invalid
            'contact_value' => '+255712345678',
        ]);

        $response->assertSessionHasErrors(['contact_type']);
        $this->assertDatabaseMissing('contact_requests', [
            'target_user_id' => $target->id,
        ]);
    }

    /**
     * Test invalid email contact value is rejected server-side.
     */
    public function test_invalid_email_value_is_rejected()
    {
        $target = $this->createPrivateTalent();

        $response = $this->post(route('profile.connect', $target->id), [
            'requester_full_name' => 'John Doe',
            'contact_type' => 'email',
            'contact_value' => 'not-a-valid-email',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['contact_value']);
    }
}
