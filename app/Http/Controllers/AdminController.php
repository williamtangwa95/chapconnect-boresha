<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreCategoryRequest;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalPhotos = Media::where('type', 'photo')->count();
        $totalVideos = Media::where('type', 'video')->count();

        // Query
        $usersQuery = User::where('role', 'user')->latest();

        if ($search) {
            $usersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('category_label', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->get();

        // Fetch categories list
        $categories = Category::orderBy('name')->get();

        // Fetch staff members list (Admin, Customer Care)
        $staffUsers = User::whereIn('role', ['admin', 'customer_care', 'staff'])->latest()->get();

        // Fetch tickets assigned to currently logged in staff member
        $assignedTickets = \App\Models\SupportTicket::with(['user'])->where('assigned_to', auth()->id())->latest()->get();

        // System notification sound & global settings
        $systemSettings = [
            'site_title' => \App\Models\SystemSetting::get('site_title', 'ChapConnect'),
            'whatsapp_number' => \App\Models\SystemSetting::get('whatsapp_number', '0710383352'),
            'support_email' => \App\Models\SystemSetting::get('support_email', 'support@chapconnect.com'),
            'auto_publish_talents' => \App\Models\SystemSetting::get('auto_publish_talents', '1'),
            'notification_sound_enabled' => \App\Models\SystemSetting::get('notification_sound_enabled', '1'),
            'notification_sound' => \App\Models\SystemSetting::get('notification_sound', 'https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'),
            'welcome_text' => \App\Models\SystemSetting::get('welcome_text', 'Karibu sana ChapConnect...'),
            'welcome_typing_speed' => \App\Models\SystemSetting::get('welcome_typing_speed', '55'),
            'welcome_delay' => \App\Models\SystemSetting::get('welcome_delay', '300'),
            'welcome_sound' => \App\Models\SystemSetting::get('welcome_sound', '/sounds/welcome_default.wav'),
            'support_phone' => \App\Models\SystemSetting::get('support_phone', '0710383352'),
            'site_summary' => \App\Models\SystemSetting::get('site_summary', 'ChapConnect connects talented artists with opportunities. Discover photos, videos, bullet bulletins and book your next favorite talent.'),
            'site_facebook' => \App\Models\SystemSetting::get('site_facebook', 'https://facebook.com/chapconnect'),
            'site_instagram' => \App\Models\SystemSetting::get('site_instagram', 'https://instagram.com/chapconnect'),
            'site_tiktok' => \App\Models\SystemSetting::get('site_tiktok', 'https://tiktok.com/@chapconnect'),
            'site_youtube' => \App\Models\SystemSetting::get('site_youtube', 'https://youtube.com/chapconnect'),
            'site_logo' => \App\Models\SystemSetting::get('site_logo', '/logo.png'),
        ];
        $notificationSound = $systemSettings['notification_sound'];

        // Billing & Subscription Stats
        $billingStats = [
            'total_billed' => \App\Models\Invoice::sum('amount'),
            'total_paid' => \App\Models\Invoice::sum('amount_paid'),
            'total_outstanding' => \App\Models\Invoice::sum('amount') - \App\Models\Invoice::sum('amount_paid'),
            'unpaid_bills_count' => \App\Models\Invoice::where('payment_status', 'Unpaid')->count(),
            'paid_bills_count' => \App\Models\Invoice::where('payment_status', 'Paid')->count(),
            'active_subs' => \App\Models\UserPackage::where('status', 'active')->where('start_date', '<=', now()->toDateString())->where('end_date', '>=', now()->toDateString())->count(),
            'expired_subs' => \App\Models\UserPackage::where(function($q) {
                $q->where('status', 'expired')
                  ->orWhere('end_date', '<', now()->toDateString());
            })->count(),
            'standard_count' => \App\Models\UserPackage::where('status', 'active')
                ->where('start_date', '<=', now()->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->where('package_name_snapshot', 'Standard')->count(),
            'premium_count' => \App\Models\UserPackage::where('status', 'active')
                ->where('start_date', '<=', now()->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->where('package_name_snapshot', 'Premium')->count(),
            'vip_count' => \App\Models\UserPackage::where('status', 'active')
                ->where('start_date', '<=', now()->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->where('package_name_snapshot', 'VIP')->count(),
            'free_count' => \App\Models\UserPackage::where('status', 'active')
                ->where('start_date', '<=', now()->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->where('package_type_snapshot', 'Free')->count(),
            'paid_count' => \App\Models\UserPackage::where('status', 'active')
                ->where('start_date', '<=', now()->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->where('package_type_snapshot', 'To Pay')->count(),
        ];

        $packages = \App\Models\Package::withCount(['userPackages' => function($q) {
            $q->where('status', 'active')->where('start_date', '<=', now()->toDateString())->where('end_date', '>=', now()->toDateString());
        }])->orderBy('name')->get();

        $invoices = \App\Models\Invoice::with(['user'])->latest()->get();
        $contactRequests = \App\Models\ContactRequest::with(['targetUser', 'requesterUser'])->latest()->get();

        // Auto-seed mock data if empty
        if (\App\Models\VisitorActivity::count() === 0) {
            $this->seedMockVisitorData();
        }

        // Visitor Analytics Query & Filters
        $analyticsQuery = \App\Models\VisitorActivity::query();

        // 1. Timeframe Filter
        $selectedTimeframe = $request->input('timeframe', 'all_time');
        if ($selectedTimeframe === 'today') {
            $analyticsQuery->whereDate('created_at', now()->toDateString());
        } elseif ($selectedTimeframe === 'last_7_days') {
            $analyticsQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($selectedTimeframe === 'last_30_days') {
            $analyticsQuery->where('created_at', '>=', now()->subDays(30));
        }

        // 2. Location Filter
        $selectedLocation = $request->input('location_filter');
        if ($selectedLocation && $selectedLocation !== 'all') {
            $analyticsQuery->where('location', $selectedLocation);
        }

        // 3. User Filter
        $selectedUser = $request->input('user_filter');
        if ($selectedUser && $selectedUser !== 'all') {
            $analyticsQuery->where(function($q) use ($selectedUser) {
                $q->where('user_id', $selectedUser)
                  ->orWhereHas('user', function($uq) use ($selectedUser) {
                      $uq->where('name', 'like', "%{$selectedUser}%");
                  });
            });
        }

        // Clone query for stats calculations
        $statsQuery = clone $analyticsQuery;

        // Today's Visits (unique session IDs created today)
        $todaysVisits = \App\Models\VisitorActivity::whereDate('created_at', now()->toDateString())
            ->distinct('session_id')
            ->count('session_id');

        // Total Page Views
        $totalPageViews = $statsQuery->count();

        // Unique Visitors (filtered)
        $uniqueVisitors = $statsQuery->distinct('session_id')->count('session_id');

        // Top Device
        $topDeviceResult = (clone $statsQuery)
            ->select('device_type', \DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->orderBy('count', 'desc')
            ->first();
        $topDevice = $topDeviceResult ? $topDeviceResult->device_type : 'Desktop';

        // Top Country
        $topCountryResult = (clone $statsQuery)
            ->select('location', \DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->get();
        
        $countryCounts = [];
        foreach ($topCountryResult as $row) {
            $parts = explode(', ', $row->location);
            $country = count($parts) > 1 ? end($parts) : 'Unknown';
            if (!isset($countryCounts[$country])) {
                $countryCounts[$country] = 0;
            }
            $countryCounts[$country] += $row->count;
        }
        arsort($countryCounts);
        $topCountry = count($countryCounts) > 0 ? key($countryCounts) : 'Tanzania';

        // Top Visitor Locations
        $topLocations = (clone $statsQuery)
            ->select('location', \DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Device Stats
        $deviceStats = (clone $statsQuery)
            ->select('device_type', \DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->orderBy('count', 'desc')
            ->get();

        // Browser Stats
        $browserStats = (clone $statsQuery)
            ->select('browser', \DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();

        // Request Log - Last 1000 hits
        $requestLogs = (clone $statsQuery)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();

        // All distinct locations for filter dropdown
        $allLocations = \App\Models\VisitorActivity::select('location')
            ->whereNotNull('location')
            ->groupBy('location')
            ->orderBy('location')
            ->get();

        // All distinct users for filter dropdown
        $allUsersWithActivity = \App\Models\User::orderBy('name')->get();

        // Auto-seed mock activity data if empty
        if (\App\Models\UserActivityLog::count() === 0) {
            $this->seedMockActivityLogs();
        }

        // Activity Logs query & filters
        $activityLogsQuery = \App\Models\UserActivityLog::with(['user'])->latest();

        $selectedActivityUser = $request->input('activity_user', 'all');
        if ($selectedActivityUser && $selectedActivityUser !== 'all') {
            $activityLogsQuery->where('user_id', $selectedActivityUser);
        }

        $activityTimeframe = $request->input('activity_timeframe', 'all_time');
        $activityStart = $request->input('activity_start_date');
        $activityEnd = $request->input('activity_end_date');

        if ($activityTimeframe === 'today') {
            $activityLogsQuery->whereDate('created_at', now()->toDateString());
        } elseif ($activityTimeframe === 'last_7_days') {
            $activityLogsQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($activityTimeframe === 'last_30_days') {
            $activityLogsQuery->where('created_at', '>=', now()->subDays(30));
        } elseif ($activityTimeframe === 'custom' && $activityStart && $activityEnd) {
            $activityLogsQuery->whereBetween('created_at', [
                \Carbon\Carbon::parse($activityStart)->startOfDay(),
                \Carbon\Carbon::parse($activityEnd)->endOfDay()
            ]);
        }

        $activityLogs = $activityLogsQuery->limit(1000)->get();

        return view('admin.index', [
            'activityLogs' => $activityLogs,
            'selectedActivityUser' => $selectedActivityUser,
            'activityTimeframe' => $activityTimeframe,
            'activityStart' => $activityStart,
            'activityEnd' => $activityEnd,
            'users' => $users,
            'categories' => $categories,
            'staffUsers' => $staffUsers,
            'assignedTickets' => $assignedTickets,
            'notificationSound' => $notificationSound,
            'systemSettings' => $systemSettings,
            'totalUsers' => $totalUsers,
            'totalPhotos' => $totalPhotos,
            'totalVideos' => $totalVideos,
            'search' => $search,
            'billingStats' => $billingStats,
            'packages' => $packages,
            'invoices' => $invoices,
            'contactRequests' => $contactRequests,
            // Analytics view variables
            'todaysVisits' => $todaysVisits,
            'totalPageViews' => $totalPageViews,
            'uniqueVisitors' => $uniqueVisitors,
            'topDevice' => $topDevice,
            'topCountry' => $topCountry,
            'topLocations' => $topLocations,
            'deviceStats' => $deviceStats,
            'browserStats' => $browserStats,
            'requestLogs' => $requestLogs,
            'allLocations' => $allLocations,
            'allUsersWithActivity' => $allUsersWithActivity,
            'selectedTimeframe' => $selectedTimeframe,
            'selectedLocation' => $selectedLocation,
            'selectedUser' => $selectedUser,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        // Delete profile photo
        if ($user->profile_image && !str_starts_with($user->profile_image, 'http')) {
            $relativePath = str_replace('/storage/', '', $user->profile_image);
            Storage::disk('public')->delete($relativePath);
        }

        // Delete all associated media files
        foreach ($user->media as $mediaItem) {
            if (!str_starts_with($mediaItem->file_path, 'http')) {
                $relativePath = str_replace('/storage/', '', $mediaItem->file_path);
                Storage::disk('public')->delete($relativePath);
            }
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $userRole = $user->role;

        $user->delete(); // cascade deletes DB records for media

        \App\Models\UserActivityLog::log('DELETED', "Deleted user account: {$userName}", [
            'old' => ['name' => $userName, 'email' => $userEmail, 'role' => $userRole]
        ], null, 'User', $id);

        return redirect()->route('admin.dashboard')->with('success', "User account {$userName} has been deleted.");
    }

    public function deleteMedia($id)
    {
        $media = Media::findOrFail($id);

        if (!str_starts_with($media->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $media->file_path);
            Storage::disk('public')->delete($relativePath);
        }

$media->delete();

        return redirect()->back()->with('success', 'Media item deleted by administrator.');
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $name = $request->input('name');
        $slug = strtolower(str_replace([' ', '-', '_', '/'], '', $name));

        // Check if slug is unique
        if (Category::where('slug', $slug)->exists()) {
            return redirect()->back()->withInput()->with('error', "A category with a similar name yielding slug '{$slug}' already exists.");
        }

        $category = Category::create([
            'name' => $name,
            'slug' => $slug
        ]);

        \App\Models\UserActivityLog::log('CREATED', "Created Category: {$category->name}", [
            'new' => ['name' => $category->name, 'slug' => $category->slug]
        ], null, 'Category', $category->id);

        return redirect()->back()->with('success', 'New category created successfully!');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Count users using this category
        $userCount = User::where('category', $category->slug)->count();
        if ($userCount > 0) {
            return redirect()->back()->with('error', "Cannot delete category '{$category->name}' because it is assigned to {$userCount} talent(s).");
        }

        $categoryName = $category->name;
        $categorySlug = $category->slug;

        $category->delete();

        \App\Models\UserActivityLog::log('DELETED', "Deleted Category: {$categoryName}", [
            'old' => ['name' => $categoryName, 'slug' => $categorySlug]
        ], null, 'Category', $id);

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make('password123')
        ]);

        return redirect()->back()->with('success', "Password for account '{$user->name}' has been successfully reset to 'password123'.");
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $oldValues = $user->only(['name', 'email', 'category', 'phone', 'role']);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
        ];

        if ($user->role === 'user') {
            $rules['category'] = 'required|string|exists:categories,slug';
            $request->validate($rules);

            $categorySlug = $request->input('category');
            $categoryRecord = Category::where('slug', $categorySlug)->firstOrFail();
            $categoryLabel = $categoryRecord->name;

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'category' => $categorySlug,
                'category_label' => $categoryLabel,
                'phone' => $request->input('phone'),
            ]);
        } else {
            $rules['role'] = 'required|string|in:admin,customer_care';
            $request->validate($rules);

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'phone' => $request->input('phone'),
            ]);
        }

        $newValues = $user->fresh()->only(['name', 'email', 'category', 'phone', 'role']);
        $diff = [];
        foreach ($newValues as $key => $val) {
            if ($oldValues[$key] != $val) {
                $diff[$key] = [
                    'old' => $oldValues[$key] ?? '[empty]',
                    'new' => $val ?? '[empty]'
                ];
            }
        }

        \App\Models\UserActivityLog::log('UPDATED', "Updated user account: {$user->name}", [
            'diff' => $diff
        ], null, 'User', $user->id);

        return redirect()->back()->with('success', "Profile details for '{$user->name}' updated successfully.");
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No talents selected for bulk deletion.');
        }

        $users = User::where('role', 'user')->whereIn('id', $ids)->get();
        $deletedCount = 0;

        foreach ($users as $user) {
            // Delete profile photo
            if ($user->profile_image && !str_starts_with($user->profile_image, 'http')) {
                $relativePath = str_replace('/storage/', '', $user->profile_image);
                Storage::disk('public')->delete($relativePath);
            }

            // Delete all associated media files
            foreach ($user->media as $mediaItem) {
                if (!str_starts_with($mediaItem->file_path, 'http')) {
                    $relativePath = str_replace('/storage/', '', $mediaItem->file_path);
                    Storage::disk('public')->delete($relativePath);
                }
            }

            $user->delete(); // cascade deletes DB records for media
            $deletedCount++;
        }

        return redirect()->back()->with('success', "Successfully deleted {$deletedCount} selected talent profile(s).");
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $oldName = $category->name;
        $oldSlug = $category->slug;
        $newName = $request->input('name');
        $newSlug = strtolower(str_replace([' ', '-', '_', '/'], '', $newName));

        // If the slug changed, check uniqueness
        if ($newSlug !== $oldSlug && Category::where('slug', $newSlug)->exists()) {
            return redirect()->back()->with('error', "A category with a similar name yielding slug '{$newSlug}' already exists.");
        }

        // Update all users who have the old category slug/label!
        if ($newSlug !== $oldSlug) {
            User::where('category', $oldSlug)->update([
                'category' => $newSlug,
                'category_label' => $newName
            ]);
        } else {
            User::where('category', $oldSlug)->update([
                'category_label' => $newName
            ]);
        }

        $category->update([
            'name' => $newName,
            'slug' => $newSlug
        ]);

        \App\Models\UserActivityLog::log('UPDATED', "Updated Category: {$newName}", [
            'old' => ['name' => $oldName, 'slug' => $oldSlug],
            'new' => ['name' => $newName, 'slug' => $newSlug]
        ], null, 'Category', $id);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Toggle a talent's published status (Admin override).
     */
    public function togglePublish($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->update(['is_published' => !$user->is_published]);
        $status = $user->is_published ? 'published' : 'unpublished';
        return redirect()->back()->with('success', "Talent profile has been {$status}.");
    }

    /**
     * Bulk publish selected talent profiles.
     */
    public function bulkPublish(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No talents selected.');
        }
        User::where('role', 'user')->whereIn('id', $ids)->update(['is_published' => true]);
        return redirect()->back()->with('success', count($ids) . ' talent profile(s) published successfully.');
    }

    /**
     * Bulk unpublish selected talent profiles.
     */
    public function bulkUnpublish(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No talents selected.');
        }
        User::where('role', 'user')->whereIn('id', $ids)->update(['is_published' => false]);
        return redirect()->back()->with('success', count($ids) . ' talent profile(s) unpublished successfully.');
    }

    /**
     * Store a newly created talent account by administrator.
     */
    public function storeUser(StoreUserRequest $request)
    {
        $categoryObj = Category::where('slug', $request->category)->first();
        $categoryLabel = $categoryObj ? $categoryObj->name : ucfirst($request->category);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'category' => $request->category,
            'category_label' => $categoryLabel,
            'phone' => $request->phone,
            'country' => $request->country ?? 'Tanzania',
            'is_published' => true,
        ]);

        \App\Models\UserActivityLog::log('CREATED', "Registered new talent account: {$user->name}", [
            'new' => ['name' => $user->name, 'email' => $user->email, 'role' => 'user']
        ], null, 'User', $user->id);

        return redirect()->back()->with('success', "Talent account '{$request->name}' registered successfully.");
    }

    /**
     * Store a newly created staff account (admin, customer care).
     */
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,customer_care',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
        ]);

        $roleLabel = $request->role === 'admin' ? 'Administrator' : 'Customer Care';

        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'category' => 'staff',
            'category_label' => 'System Staff',
            'phone' => $request->phone,
            'country' => $request->country ?? 'Tanzania',
            'is_published' => true,
        ]);

        \App\Models\UserActivityLog::log('CREATED', "Registered new staff account: {$staff->name} ({$roleLabel})", [
            'new' => ['name' => $staff->name, 'email' => $staff->email, 'role' => $staff->role]
        ], null, 'User', $staff->id);

        return redirect()->back()->with('success', "{$roleLabel} staff account '{$request->name}' registered successfully.");
    }

    /**
     * Upload custom notification sound file (.mp3, .wav, .ogg).
     */
    public function uploadNotificationSound(Request $request)
    {
        $request->validate([
            'notification_sound' => 'required|file|mimes:mp3,wav,ogg,audio/mpeg,audio/wav,audio/ogg|max:5120',
        ]);

        if ($request->hasFile('notification_sound')) {
            $file = $request->file('notification_sound');
            $filename = 'notification_sound_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sounds', $filename, 'public');

            \App\Models\SystemSetting::set('notification_sound', '/storage/' . $path);

            return redirect()->back()->with('success', 'Custom in-app notification sound uploaded and saved successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload notification sound file.');
    }

    /**
     * Update dedicated system settings.
     */
    public function updateSystemSettings(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:50',
            'support_email' => 'required|email|max:255',
            'welcome_text' => 'nullable|string|max:255',
            'welcome_typing_speed' => 'nullable|integer|min:10|max:1000',
            'welcome_delay' => 'nullable|integer|min:0|max:5000',
            'welcome_sound_file' => 'nullable|file|mimes:mp3,wav,ogg,audio/mpeg,audio/wav,audio/ogg|max:5120',
            'support_phone' => 'nullable|string|max:50',
            'site_summary' => 'nullable|string|max:1000',
            'site_facebook' => 'nullable|url|max:255',
            'site_instagram' => 'nullable|url|max:255',
            'site_tiktok' => 'nullable|url|max:255',
            'site_youtube' => 'nullable|url|max:255',
            'site_logo_file' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:5120',
        ]);

        \App\Models\SystemSetting::set('site_title', $request->site_title);
        \App\Models\SystemSetting::set('whatsapp_number', $request->whatsapp_number);
        \App\Models\SystemSetting::set('support_email', $request->support_email);
        \App\Models\SystemSetting::set('auto_publish_talents', $request->has('auto_publish_talents') ? '1' : '0');
        \App\Models\SystemSetting::set('notification_sound_enabled', $request->has('notification_sound_enabled') ? '1' : '0');

        \App\Models\SystemSetting::set('support_phone', $request->support_phone);
        \App\Models\SystemSetting::set('site_summary', $request->site_summary);
        \App\Models\SystemSetting::set('site_facebook', $request->site_facebook);
        \App\Models\SystemSetting::set('site_instagram', $request->site_instagram);
        \App\Models\SystemSetting::set('site_tiktok', $request->site_tiktok);
        \App\Models\SystemSetting::set('site_youtube', $request->site_youtube);

        if ($request->filled('welcome_text')) {
            \App\Models\SystemSetting::set('welcome_text', $request->welcome_text);
        }
        if ($request->filled('welcome_typing_speed')) {
            \App\Models\SystemSetting::set('welcome_typing_speed', $request->welcome_typing_speed);
        }
        if ($request->filled('welcome_delay')) {
            \App\Models\SystemSetting::set('welcome_delay', $request->welcome_delay);
        }

        // Check if sound file uploaded for notification sound
        if ($request->hasFile('notification_sound')) {
            $file = $request->file('notification_sound');
            $filename = 'notification_sound_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sounds', $filename, 'public');

            \App\Models\SystemSetting::set('notification_sound', '/storage/' . $path);
        }

        // Check if sound file uploaded for welcome audio
        if ($request->hasFile('welcome_sound_file')) {
            $file = $request->file('welcome_sound_file');
            $filename = 'welcome_sound_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sounds', $filename, 'public');

            \App\Models\SystemSetting::set('welcome_sound', '/storage/' . $path);
        }

        // Check if logo image uploaded
        if ($request->hasFile('site_logo_file')) {
            $file = $request->file('site_logo_file');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');

            \App\Models\SystemSetting::set('site_logo', '/storage/' . $path);
        }

        \App\Models\UserActivityLog::log('UPDATED', 'Updated dedicated System & Platform Settings.', [
            'new' => [
                'site_title' => $request->site_title,
                'whatsapp_number' => $request->whatsapp_number,
                'support_email' => $request->support_email,
                'support_phone' => $request->support_phone,
                'site_summary' => $request->site_summary
            ]
        ], null, 'SystemSetting');

        return redirect()->back()->with('success', 'System settings saved and updated successfully.');
    }

    /**
     * Reset welcome audio sound to default female voice sound.
     */
    public function resetWelcomeSound()
    {
        \App\Models\SystemSetting::set('welcome_sound', '/sounds/welcome_default.wav');

        return redirect()->back()->with('success', 'Welcome audio sound reset to default female voice sound successfully.');
    }

    /**
     * Clear system cache.
     */
    public function clearCache()
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        return redirect()->back()->with('success', 'System application cache cleared successfully.');
    }

    public function storePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone_visibility' => 'required|string|in:Yes,No',
            'max_images' => 'required|integer|min:0',
            'max_videos' => 'required|integer|min:0',
            'max_news' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_unit' => 'required|string|in:days,months,years',
            'package_type' => 'required|string|in:Free,To Pay',
            'status' => 'required|string|in:Active,Inactive',
        ]);

        \App\Models\Package::create($request->all());

        return redirect()->back()->with('success', 'Package created successfully.');
    }

    public function updatePackage(Request $request, $id)
    {
        $package = \App\Models\Package::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone_visibility' => 'required|string|in:Yes,No',
            'max_images' => 'required|integer|min:0',
            'max_videos' => 'required|integer|min:0',
            'max_news' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_unit' => 'required|string|in:days,months,years',
            'package_type' => 'required|string|in:Free,To Pay',
            'status' => 'required|string|in:Active,Inactive',
        ]);

        $package->update($request->all());

        return redirect()->back()->with('success', 'Package updated successfully.');
    }

    public function deletePackage($id)
    {
        $package = \App\Models\Package::findOrFail($id);

        // Check if there are active subscriptions
        $hasActiveUsers = \App\Models\UserPackage::where('package_id', $id)
            ->where('status', 'active')
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->exists();

        if ($hasActiveUsers) {
            return redirect()->back()->with('error', 'Cannot delete package because it has active users.');
        }

        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully.');
    }

    public function assignPackage(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $packageId = $request->input('package_id');
        $package = \App\Models\Package::where('status', 'Active')->findOrFail($packageId);

        $request->validate([
            'package_id' => 'required',
            'start_date' => 'required|date',
            'months' => 'required|integer|min:1|max:12',
        ]);

        $monthsMultiplier = intval($request->input('months', 1));
        $startDate = $request->input('start_date', now()->toDateString());
        
        $baseDays = intval($package->duration);
        if ($package->duration_unit === 'months') {
            $baseDays = $baseDays * 30;
        } elseif ($package->duration_unit === 'years') {
            $baseDays = $baseDays * 365;
        }
        
        $days = $baseDays * $monthsMultiplier;
        $endDate = date('Y-m-d', strtotime($startDate . " + {$days} days"));

        // Deactivate previous active subscriptions for this user
        \App\Models\UserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        // Create the new Subscription/UserPackage record
        $userPackage = \App\Models\UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_name_snapshot' => $package->name,
            'price_snapshot' => $package->price * $monthsMultiplier,
            'duration_snapshot' => $package->duration * $monthsMultiplier,
            'duration_unit_snapshot' => $package->duration_unit,
            'phone_visibility_snapshot' => $package->phone_visibility,
            'max_images_snapshot' => $package->max_images,
            'max_videos_snapshot' => $package->max_videos,
            'max_news_snapshot' => $package->max_news,
            'package_type_snapshot' => $package->package_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'assigned_by' => auth()->id(),
        ]);

        // Generate invoice if package type is "To Pay"
        if ($package->package_type === 'To Pay') {
            $invoiceNumber = \App\Models\Invoice::generateInvoiceNumber();
            \App\Models\Invoice::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'user_package_id' => $userPackage->id,
                'package_id' => $package->id,
                'package_name' => $package->name . " ({$monthsMultiplier} " . ($monthsMultiplier === 1 ? 'Month' : 'Months') . ")",
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration' => $package->duration * $monthsMultiplier,
                'duration_unit' => $package->duration_unit,
                'amount' => $package->price * $monthsMultiplier,
                'amount_paid' => 0.00,
                'payment_status' => 'Unpaid',
                'invoice_date' => now()->toDateString(),
                'due_date' => date('Y-m-d', strtotime(now()->toDateString() . ' + 7 days')),
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', "Package '{$package->name}' assigned to user '{$user->name}' successfully.");
    }

    public function recordInvoicePayment(Request $request, $invoiceId)
    {
        $invoice = \App\Models\Invoice::findOrFail($invoiceId);

        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:100',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $newAmountPaid = $invoice->amount_paid + floatval($request->amount_paid);

        // Check if amount paid exceeds invoice amount
        if ($newAmountPaid > $invoice->amount) {
            return redirect()->back()->withErrors(['amount_paid' => 'Recorded payment exceeds the outstanding invoice balance.']);
        }

        $paymentStatus = 'Unpaid';
        if ($newAmountPaid >= $invoice->amount) {
            $paymentStatus = 'Paid';
        } elseif ($newAmountPaid > 0) {
            $paymentStatus = 'Partially Paid';
        }

        $invoice->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $paymentStatus,
            'paid_at' => $paymentStatus === 'Paid' ? now() : null,
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Payment logged successfully.');
    }

    /**
     * Seed realistic mock visitor analytics logs to matching targets.
     */
    private function seedMockVisitorData()
    {
        $activities = [];
        $now = now();
        
        // Define browser weights (matching target counts)
        $browsers = [
            'Chrome' => 2344,
            'Safari' => 573,
            'Unknown' => 308,
            'Firefox' => 115,
            'Edge' => 72
        ];
        
        // Define device weights
        $devices = [
            'Desktop' => 2406,
            'Mobile' => 1021
        ];
        
        // Define location weights
        $locations = [
            'Morogoro, Tanzania' => 1608,
            'Unknown, Unknown' => 251,
            'Santa Clara, United States' => 154,
            'Ashburn, United States' => 140,
            'Dar es Salaam, Tanzania' => 137,
            'Dodoma, Tanzania' => 500,
            'Arusha, Tanzania' => 387,
            'Mwanza, Tanzania' => 250
        ];
        
        // Prepare weighted lists
        $weightedBrowsers = [];
        foreach ($browsers as $browser => $weight) {
            $weightedBrowsers = array_merge($weightedBrowsers, array_fill(0, $weight, $browser));
        }
        
        $weightedDevices = [];
        foreach ($devices as $device => $weight) {
            $weightedDevices = array_merge($weightedDevices, array_fill(0, $weight, $device));
        }
        
        $weightedLocations = [];
        foreach ($locations as $location => $weight) {
            $weightedLocations = array_merge($weightedLocations, array_fill(0, $weight, $location));
        }
        
        // Pre-generate unique session IDs
        $sessions = [];
        for ($i = 0; $i < 984; $i++) {
            $sessions[] = 'sess_' . md5('sess_mock_' . $i);
        }
        
        // Pre-generate diverse IPs
        $ips = [];
        for ($i = 0; $i < 300; $i++) {
            if ($i % 10 === 0) {
                $ips[] = '198.51.100.' . rand(1, 254);
            } else {
                $ips[] = '197.250.' . rand(0, 255) . '.' . rand(1, 254);
            }
        }
        
        $urls = [
            '/',
            '/',
            '/',
            '/profile/24',
            '/profile/18',
            '/profile/20',
            '/category/Music',
            '/category/Acting',
            '/category/Dancing',
            '/dashboard',
            '/home',
            '/about'
        ];
        
        $userIds = \App\Models\User::pluck('id')->toArray();
        $totalRecords = 3427;
        
        for ($i = 0; $i < $totalRecords; $i++) {
            // Allocate timestamps: 61 today, rest distributed over last 30 days
            if ($i < 61) {
                $time = clone $now;
                $time->subMinutes(rand(0, $now->hour * 60 + $now->minute));
            } else {
                $time = clone $now;
                $time->subDays(rand(1, 30))->subMinutes(rand(0, 1440));
            }
            
            $sessionId = $sessions[$i % count($sessions)];
            $ip = $ips[$i % count($ips)];
            
            $device = $weightedDevices[$i % count($weightedDevices)];
            $browser = $weightedBrowsers[$i % count($weightedBrowsers)];
            $location = $weightedLocations[$i % count($weightedLocations)];
            
            $ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36";
            if ($device === 'Mobile') {
                $ua = "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Mobile Safari/537.36";
            }
            
            $userId = (rand(1, 100) > 85 && count($userIds) > 0) ? $userIds[rand(0, count($userIds) - 1)] : null;
            
            $activities[] = [
                'ip_address' => $ip,
                'location' => $location,
                'url' => $urls[rand(0, count($urls) - 1)],
                'method' => 'GET',
                'user_agent' => $ua,
                'device_type' => $device,
                'browser' => $browser,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'created_at' => $time,
                'updated_at' => $time
            ];
        }
        
        // Chunk inserts for database efficiency
        $chunks = array_chunk($activities, 500);
        foreach ($chunks as $chunk) {
            \App\Models\VisitorActivity::insert($chunk);
        }
    }

    /**
     * Export filtered visitor log data to compliant CSV format.
     */
    public function exportAnalyticsExcel(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all_time');
        $location = $request->input('location_filter');
        $user = $request->input('user_filter');

        $query = \App\Models\VisitorActivity::query();

        if ($timeframe === 'today') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($timeframe === 'last_7_days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($timeframe === 'last_30_days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        if ($location && $location !== 'all') {
            $query->where('location', $location);
        }

        if ($user && $user !== 'all') {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user)
                  ->orWhereHas('user', function($uq) use ($user) {
                      $uq->where('name', 'like', "%{$user}%");
                  });
            });
        }

        $logs = $query->with('user')->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="visitor_analytics_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Time', 'IP Address', 'Location', 'Device / Browser', 'Request / URL', 'User Account']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->ip_address,
                    $log->location ?? 'Unknown',
                    $log->device_type . ' / ' . $log->browser,
                    $log->url,
                    $log->user ? $log->user->name : 'Guest Visitor'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Render print-ready HTML template for visitor analytics PDF.
     */
    public function downloadAnalyticsPdf(Request $request)
    {
        $timeframe = $request->input('timeframe', 'all_time');
        $location = $request->input('location_filter');
        $user = $request->input('user_filter');

        $query = \App\Models\VisitorActivity::query();

        if ($timeframe === 'today') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($timeframe === 'last_7_days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($timeframe === 'last_30_days') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        if ($location && $location !== 'all') {
            $query->where('location', $location);
        }

        if ($user && $user !== 'all') {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user)
                  ->orWhereHas('user', function($uq) use ($user) {
                      $uq->where('name', 'like', "%{$user}%");
                  });
            });
        }

        $logs = $query->with('user')->orderBy('created_at', 'desc')->get();
        $totalPageViews = $logs->count();
        $uniqueVisitors = $logs->distinct('session_id')->count('session_id');

        return view('admin.analytics_pdf', [
            'logs' => $logs,
            'totalPageViews' => $totalPageViews,
            'uniqueVisitors' => $uniqueVisitors,
            'timeframe' => $timeframe,
            'location' => $location ?: 'All',
            'user' => $user ?: 'All'
        ]);
    }

    /**
     * Seed mock user activity logs matching the user's mockup.
     */
    private function seedMockActivityLogs()
    {
        $admin = \App\Models\User::where('email', 'clemence@chapconnect.com')->first();
        if (!$admin) {
            $admin = \App\Models\User::create([
                'name' => 'Clemence Simon',
                'email' => 'clemence@chapconnect.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'admin',
                'category' => 'management',
                'category_label' => 'Administration',
                'country' => 'Tanzania',
            ]);
        }

        $now = now();
        $ip = '45.221.196.65';
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36';

        // 1. Logout
        \App\Models\UserActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'LOGOUT',
            'description' => 'Logged out of the system.',
            'properties' => null,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => $now->copy()->subDays(2)->setHour(11)->setMinute(41)->setSecond(23),
            'updated_at' => $now->copy()->subDays(2)->setHour(11)->setMinute(41)->setSecond(23),
        ]);

        // 2. Updated product/user
        \App\Models\UserActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'UPDATED',
            'description' => 'Updated Product: Full HD 1080P USB Web Camera',
            'properties' => [
                'diff' => [
                    'price' => ['old' => '15000.00', 'new' => '35000'],
                    'is_from_price' => ['old' => '[empty]', 'new' => '[empty]'],
                    'in_stock' => ['old' => '1', 'new' => '1'],
                ]
            ],
            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => $now->copy()->subDays(2)->setHour(10)->setMinute(38)->setSecond(0),
            'updated_at' => $now->copy()->subDays(2)->setHour(10)->setMinute(38)->setSecond(0),
        ]);

        // 3. Login
        \App\Models\UserActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'LOGIN',
            'description' => 'Logged into the system.',
            'properties' => null,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => $now->copy()->subDays(2)->setHour(9)->setMinute(51)->setSecond(53),
            'updated_at' => $now->copy()->subDays(2)->setHour(9)->setMinute(51)->setSecond(53),
        ]);

        // 4. Created another talent
        \App\Models\UserActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'CREATED',
            'description' => 'Registered new talent account: William Tangwa',
            'properties' => [
                'new' => ['name' => 'William Tangwa', 'email' => 'william@chapconnect.com', 'role' => 'user']
            ],
            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => $now->copy()->subDays(3)->setHour(14)->setMinute(20)->setSecond(10),
            'updated_at' => $now->copy()->subDays(3)->setHour(14)->setMinute(20)->setSecond(10),
        ]);

        // 5. Updated system settings
        \App\Models\UserActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'UPDATED',
            'description' => 'Updated dedicated System & Platform Settings.',
            'properties' => [
                'diff' => [
                    'site_title' => ['old' => 'ChapConnect', 'new' => 'ChapConnect Pro'],
                    'support_email' => ['old' => 'support@chapconnect.com', 'new' => 'help@chapconnect.com']
                ]
            ],
            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => $now->copy()->subDays(3)->setHour(16)->setMinute(45)->setSecond(00),
            'updated_at' => $now->copy()->subDays(3)->setHour(16)->setMinute(45)->setSecond(00),
        ]);
    }

    /**
     * Export User Activity Logs to CSV.
     */
    public function exportActivityExcel(Request $request)
    {
        $activityLogsQuery = \App\Models\UserActivityLog::with(['user'])->latest();

        $selectedActivityUser = $request->input('activity_user', 'all');
        if ($selectedActivityUser && $selectedActivityUser !== 'all') {
            $activityLogsQuery->where('user_id', $selectedActivityUser);
        }

        $activityTimeframe = $request->input('activity_timeframe', 'all_time');
        $activityStart = $request->input('activity_start_date');
        $activityEnd = $request->input('activity_end_date');

        if ($activityTimeframe === 'today') {
            $activityLogsQuery->whereDate('created_at', now()->toDateString());
        } elseif ($activityTimeframe === 'last_7_days') {
            $activityLogsQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($activityTimeframe === 'last_30_days') {
            $activityLogsQuery->where('created_at', '>=', now()->subDays(30));
        } elseif ($activityTimeframe === 'custom' && $activityStart && $activityEnd) {
            $activityLogsQuery->whereBetween('created_at', [
                \Carbon\Carbon::parse($activityStart)->startOfDay(),
                \Carbon\Carbon::parse($activityEnd)->endOfDay()
            ]);
        }

        $logs = $activityLogsQuery->limit(1000)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="system_activity_logs_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Time', 'User Name', 'Role', 'Action', 'Activity Details', 'IP Address', 'User Agent']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'System/Guest',
                    $log->user ? $log->user->role : 'Guest',
                    $log->action,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download User Activity Logs print/pdf view.
     */
    public function downloadActivityPdf(Request $request)
    {
        $activityLogsQuery = \App\Models\UserActivityLog::with(['user'])->latest();

        $selectedActivityUser = $request->input('activity_user', 'all');
        if ($selectedActivityUser && $selectedActivityUser !== 'all') {
            $activityLogsQuery->where('user_id', $selectedActivityUser);
        }

        $activityTimeframe = $request->input('activity_timeframe', 'all_time');
        $activityStart = $request->input('activity_start_date');
        $activityEnd = $request->input('activity_end_date');

        if ($activityTimeframe === 'today') {
            $activityLogsQuery->whereDate('created_at', now()->toDateString());
        } elseif ($activityTimeframe === 'last_7_days') {
            $activityLogsQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($activityTimeframe === 'last_30_days') {
            $activityLogsQuery->where('created_at', '>=', now()->subDays(30));
        } elseif ($activityTimeframe === 'custom' && $activityStart && $activityEnd) {
            $activityLogsQuery->whereBetween('created_at', [
                \Carbon\Carbon::parse($activityStart)->startOfDay(),
                \Carbon\Carbon::parse($activityEnd)->endOfDay()
            ]);
        }

        $logs = $activityLogsQuery->limit(1000)->get();

        return view('admin.activity_pdf', [
            'logs' => $logs,
            'selectedActivityUser' => $selectedActivityUser,
            'activityTimeframe' => $activityTimeframe,
            'activityStart' => $activityStart,
            'activityEnd' => $activityEnd
        ]);
    }
}
