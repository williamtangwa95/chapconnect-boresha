<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        ];
        $notificationSound = $systemSettings['notification_sound'];

        return view('admin.index', [
            'users' => $users,
            'categories' => $categories,
            'staffUsers' => $staffUsers,
            'assignedTickets' => $assignedTickets,
            'notificationSound' => $notificationSound,
            'systemSettings' => $systemSettings,
            'totalUsers' => $totalUsers,
            'totalPhotos' => $totalPhotos,
            'totalVideos' => $totalVideos,
            'search' => $search
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

        $user->delete(); // cascade deletes DB records for media

        return redirect()->route('admin.dashboard')->with('success', "User account {$user->name} has been deleted.");
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

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        $name = $request->input('name');
        $slug = strtolower(str_replace([' ', '-', '_', '/'], '', $name));

        // Check if slug is unique
        if (Category::where('slug', $slug)->exists()) {
            return redirect()->back()->withInput()->with('error', "A category with a similar name yielding slug '{$slug}' already exists.");
        }

        Category::create([
            'name' => $name,
            'slug' => $slug
        ]);

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

        $category->delete();

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
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'category' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
        ]);

        $categoryObj = Category::where('slug', $request->category)->first();
        $categoryLabel = $categoryObj ? $categoryObj->name : ucfirst($request->category);

        User::create([
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

        User::create([
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
        ]);

        \App\Models\SystemSetting::set('site_title', $request->site_title);
        \App\Models\SystemSetting::set('whatsapp_number', $request->whatsapp_number);
        \App\Models\SystemSetting::set('support_email', $request->support_email);
        \App\Models\SystemSetting::set('auto_publish_talents', $request->has('auto_publish_talents') ? '1' : '0');
        \App\Models\SystemSetting::set('notification_sound_enabled', $request->has('notification_sound_enabled') ? '1' : '0');

        // Check if sound file uploaded here
        if ($request->hasFile('notification_sound')) {
            $file = $request->file('notification_sound');
            $filename = 'notification_sound_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('sounds', $filename, 'public');

            \App\Models\SystemSetting::set('notification_sound', '/storage/' . $path);
        }

        return redirect()->back()->with('success', 'System settings saved and updated successfully.');
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
}
