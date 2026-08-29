<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        // Query only regular users that have published their profiles
        $query = User::where('role', 'user')
            ->where('is_published', true)
            ->withCount(['likesReceived', 'followersReceived', 'commentsReceived']);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category_label', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $talents = $query->latest()->get();
        $currentUser = auth()->user();
        $isStaff = $currentUser && in_array($currentUser->role, ['admin', 'customer_care', 'staff']);
        if (!$isStaff) {
            foreach ($talents as $t) {
                if ($t->currentPackageDetails()['phone_visibility'] === 'No') {
                    $t->phone = null;
                }
            }
        }

        // Count published talents per category
        $categoryCounts = User::where('role', 'user')->where('is_published', true)
            ->groupBy('category')
            ->selectRaw('category, count(*) as count')
            ->pluck('count', 'category')
            ->toArray();

        // Count total published talents
        $totalTalents = User::where('role', 'user')->where('is_published', true)->count();

        // Sort categories by talent count descending (most popular first)
        $allCategories = AuthController::categories();
        uksort($allCategories, function($a, $b) use ($categoryCounts) {
            $countA = $categoryCounts[$a] ?? 0;
            $countB = $categoryCounts[$b] ?? 0;
            return $countB <=> $countA; // descending
        });

        return view('home', [
            'talents' => $talents,
            'currentCategory' => $category ?? 'all',
            'search' => $search,
            'categories' => $allCategories,
            'categoryCounts' => $categoryCounts,
            'totalTalents' => $totalTalents
        ]);
    }

    public function category($category)
    {
        $validCategories = AuthController::categories();
        if (!array_key_exists($category, $validCategories)) {
            abort(404);
        }

        $directors = User::where('role', 'user')
            ->where('category', $category)
            ->latest()
            ->get();
        $currentUser = auth()->user();
        $isStaff = $currentUser && in_array($currentUser->role, ['admin', 'customer_care', 'staff']);
        if (!$isStaff) {
            foreach ($directors as $t) {
                if ($t->currentPackageDetails()['phone_visibility'] === 'No') {
                    $t->phone = null;
                }
            }
        }

        return view('category', [
            'talents' => $directors,
            'category' => $category,
            'categoryLabel' => $validCategories[$category]
        ]);
    }

    public function profile(Request $request, $id)
    {
        $talent = User::where('role', 'user')
            ->withCount(['likesReceived', 'followersReceived', 'commentsReceived'])
            ->findOrFail($id);

        $currentUser = auth()->user();
        $isStaff = $currentUser && in_array($currentUser->role, ['admin', 'customer_care', 'staff']);
        
        // Track Unique Profile View & Points
        $userId = auth()->id();
        $ip = $request->ip();

        // Manage Device Fingerprint Cookie
        $deviceFingerprint = $request->cookie('device_token');
        if (!$deviceFingerprint) {
            $deviceFingerprint = md5($ip . ($request->header('User-Agent') ?? '') . uniqid('dev_', true));
            cookie()->queue('device_token', $deviceFingerprint, 525600); // 1 year
        }

        // Check if this visitor has already viewed this profile
        $viewQuery = \App\Models\ProfileView::where('talent_id', $id);
        if ($userId) {
            $viewQuery->where('user_id', $userId);
        } else {
            $viewQuery->where(function($q) use ($ip, $deviceFingerprint) {
                $q->where('ip_address', $ip);
                if ($deviceFingerprint) {
                    $q->orWhere('device_fingerprint', $deviceFingerprint);
                }
            });
        }

        if (!$viewQuery->exists()) {
            \App\Models\ProfileView::create([
                'talent_id' => $id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'device_fingerprint' => $deviceFingerprint,
            ]);
            $talent->increment('views_count');
            $talent->refresh();
        }

        // Load first 4 photos for mini-gallery
        $miniPhotos = $talent->media()->where('type', 'photo')->latest()->take(4)->get();
        // Load top-level comments for talent with eager loaded replies
        $comments = $talent->commentsReceived()
            ->whereNull('parent_id')
            ->with(['replies.user', 'user'])
            ->latest()
            ->get();

        if (!$isStaff) {
            if ($talent->currentPackageDetails()['phone_visibility'] === 'No') {
                $talent->phone = null;
            }
        }

        return view('profile', [
            'talent' => $talent,
            'miniPhotos' => $miniPhotos,
            'comments' => $comments
        ]);
    }

    public function downloadApp()
    {
        $apkPath = public_path('downloads/ChapConnect.apk');
        if (file_exists($apkPath) && filesize($apkPath) > 100000) {
            return response()->download($apkPath, 'ChapConnect.apk', [
                'Content-Type' => 'application/vnd.android.package-archive',
            ]);
        }
        return redirect()->route('home')->with('error', 'Native Android APK build is not uploaded yet. Please use "Add to Home Screen" to install the App instantly.');
    }

    public function photos($id)
    {
        $talent = User::where('role', 'user')->findOrFail($id);
        $currentUser = auth()->user();
        $isStaff = $currentUser && in_array($currentUser->role, ['admin', 'customer_care', 'staff']);
        if (!$isStaff) {
            if ($talent->currentPackageDetails()['phone_visibility'] === 'No') {
                $talent->phone = null;
            }
        }
        $photos = $talent->media()->where('type', 'photo')->latest()->get();

        return view('profile-photos', [
            'talent' => $talent,
            'photos' => $photos
        ]);
    }

    public function videos($id)
    {
        $talent = User::where('role', 'user')->findOrFail($id);
        $currentUser = auth()->user();
        $isStaff = $currentUser && in_array($currentUser->role, ['admin', 'customer_care', 'staff']);
        if (!$isStaff) {
            if ($talent->currentPackageDetails()['phone_visibility'] === 'No') {
                $talent->phone = null;
            }
        }
        $videos = $talent->media()->where('type', 'video')->latest()->get();

        return view('profile-videos', [
            'talent' => $talent,
            'videos' => $videos
        ]);
    }
}
