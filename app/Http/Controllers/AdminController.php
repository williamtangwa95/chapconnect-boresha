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

        $users = $usersQuery->paginate(20);

        // Fetch categories list
        $categories = Category::orderBy('name')->get();

        return view('admin.index', [
            'users' => $users,
            'categories' => $categories,
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
        $user = User::where('role', 'user')->findOrFail($id);
        $user->update([
            'password' => Hash::make('password123')
        ]);

        return redirect()->back()->with('success', "Password for talent '{$user->name}' has been successfully reset to 'password123'.");
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'category' => 'required|string|exists:categories,slug',
            'phone' => 'nullable|string|max:30',
        ]);

        $categorySlug = $request->input('category');
        $categoryRecord = Category::where('slug', $categorySlug)->firstOrFail();

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'category' => $categorySlug,
            'category_label' => $categoryRecord->name,
            'phone' => $request->input('phone'),
        ]);

        return redirect()->back()->with('success', "Profile details for talent '{$user->name}' updated successfully.");
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
}
