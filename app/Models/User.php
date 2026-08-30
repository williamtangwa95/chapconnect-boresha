<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 
    'email', 
    'password', 
    'category', 
    'category_label', 
    'description', 
    'phone', 
    'country', 
    'profile_image', 
    'role', 
    'is_blocked',
    'is_published',
    'views_count',
    'social_instagram', 
    'social_facebook', 
    'social_tiktok', 
    'social_youtube',
    'security_question',
    'security_answer'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the media files for the user.
     */
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Get support tickets reported by this user.
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    /**
     * Get support tickets assigned to this staff user.
     */
    public function assignedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    /**
     * Get likes received by this talent user.
     */
    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'talent_id');
    }

    /**
     * Get followers of this talent user.
     */
    public function followersReceived()
    {
        return $this->hasMany(Follower::class, 'talent_id');
    }

    /**
     * Get comments received by this talent user.
     */
    public function commentsReceived()
    {
        return $this->hasMany(Comment::class, 'talent_id');
    }

    /**
     * Get profile views for this talent user.
     */
    public function profileViews()
    {
        return $this->hasMany(ProfileView::class, 'talent_id');
    }

    /**
     * Get failed login attempts for this user.
     */
    public function failedLoginAttempts()
    {
        return $this->hasMany(FailedLoginAttempt::class);
    }

    /**
     * Get login block records for this user.
     */
    public function accountBlocks()
    {
        return $this->hasMany(AccountBlock::class);
    }

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class, 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }

    public function paymentRequests()
    {
        return $this->hasMany(TalentPaymentRequest::class, 'user_id');
    }

    public function hasBeenPaid()
    {
        return $this->paymentRequests()->where('status', 'paid')->exists();
    }

    public function contactRequestsReceived()
    {
        return $this->hasMany(ContactRequest::class, 'target_user_id');
    }

    public function contactRequestsSent()
    {
        return $this->hasMany(ContactRequest::class, 'requester_user_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserPackage::class, 'user_id')
            ->where('status', 'active')
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString());
    }

    /**
     * Get active package details or fallback to default standard package
     */
    public function currentPackageDetails()
    {
        $activeSub = $this->activeSubscription;
        if ($activeSub) {
            return [
                'name' => $activeSub->package_name_snapshot,
                'price' => $activeSub->price_snapshot,
                'duration' => $activeSub->duration_snapshot,
                'duration_unit' => $activeSub->duration_unit_snapshot,
                'phone_visibility' => $activeSub->package ? $activeSub->package->phone_visibility : $activeSub->phone_visibility_snapshot,
                'max_images' => intval($activeSub->package ? $activeSub->package->max_images : $activeSub->max_images_snapshot),
                'max_videos' => intval($activeSub->package ? $activeSub->package->max_videos : $activeSub->max_videos_snapshot),
                'max_news' => intval($activeSub->package ? $activeSub->package->max_news : $activeSub->max_news_snapshot),
                'package_type' => $activeSub->package_type_snapshot,
                'status' => $activeSub->status,
                'start_date' => $activeSub->start_date,
                'end_date' => $activeSub->end_date,
                'is_fallback' => false,
                'is_expired' => false,
            ];
        }

        // Check if there is an active default Free/Standard package in database
        $defaultPackage = Package::where('package_type', 'Free')->where('status', 'Active')->first();
        if ($defaultPackage) {
            return [
                'name' => $defaultPackage->name,
                'price' => $defaultPackage->price,
                'duration' => $defaultPackage->duration,
                'duration_unit' => $defaultPackage->duration_unit,
                'phone_visibility' => $defaultPackage->phone_visibility,
                'max_images' => intval($defaultPackage->max_images),
                'max_videos' => intval($defaultPackage->max_videos),
                'max_news' => intval($defaultPackage->max_news),
                'package_type' => $defaultPackage->package_type,
                'status' => 'active',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(365)->toDateString(),
                'is_fallback' => true,
                'is_expired' => true, // Treat as fallback/expired since no active subscription exists
            ];
        }

        // Absolute fallback constants if database seeders are missing
        return [
            'name' => 'Standard',
            'price' => 0.00,
            'duration' => 365,
            'duration_unit' => 'days',
            'phone_visibility' => 'No',
            'max_images' => 5,
            'max_videos' => 2,
            'max_news' => 3,
            'package_type' => 'Free',
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(365)->toDateString(),
            'is_fallback' => true,
            'is_expired' => true,
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_published' => 'boolean',
        ];
    }
}
