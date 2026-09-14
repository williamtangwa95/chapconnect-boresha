<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FailedLoginAttempt;
use App\Models\AccountBlock;
use App\Models\Notification;
use App\Mail\ResetPasswordCodeMail;
use App\Helpers\PhoneHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Category mapping helper.
     */
    public static function categories()
    {
        return \App\Models\Category::pluck('name', 'slug')->toArray();
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register', [
            'categories' => self::categories()
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'category' => 'required|string|in:' . implode(',', array_keys(self::categories())),
            'phone' => 'required_without:email|nullable|string|max:30',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255',
            'phone_visibility' => 'required|string|in:Yes,No',
        ], [
            'email.unique' => __('Email already taken'),
            'email.email' => __('Please enter a valid email address'),
        ]);

        if ($request->filled('phone')) {
            if (!PhoneHelper::isValidTanzanianPhone($request->phone)) {
                throw ValidationException::withMessages([
                    'phone' => __('Please enter a valid Tanzanian phone number starting with 06, 07, +255, or 255 (e.g. 0678429492 or +255678429492).'),
                ]);
            }

            $possibleFormats = PhoneHelper::getPossibleFormats($request->phone);
            if (User::whereIn('phone', $possibleFormats)->exists()) {
                throw ValidationException::withMessages([
                    'phone' => __('Phone number already taken'),
                ]);
            }
        }

        $q = $request->input('security_question');
        $a = $request->input('security_answer');
        
        similar_text(\Illuminate\Support\Str::lower($q), \Illuminate\Support\Str::lower($a), $percent);
        if ($percent >= 50) {
            throw ValidationException::withMessages([
                'security_answer' => __('The security question and answer are too similar (must be less than 50% match).'),
            ]);
        }

        $category = $request->input('category');
        $categoryLabel = self::categories()[$category];
        $phoneVisibility = $request->input('phone_visibility', 'Yes');

        $normalizedPhone = $request->filled('phone') ? PhoneHelper::normalizeToLocal($request->phone) : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?: null,
            'password' => Hash::make($request->password),
            'category' => $category,
            'category_label' => $categoryLabel,
            'phone' => $normalizedPhone,
            'country' => 'East Africa Tanzania',
            'role' => 'user', // default registration role
            'security_question' => $q,
            'security_answer' => \Illuminate\Support\Facades\Crypt::encryptString(\Illuminate\Support\Str::lower($a)),
        ]);

        // Auto-match Package based on phone visibility choice
        // 'Yes' -> Standard Package (Visible Contacts), 'No' -> VIP Package (Hidden Contacts)
        $matchedPackage = \App\Models\Package::where('status', 'Active')
            ->where('phone_visibility', $phoneVisibility)
            ->first();

        if (!$matchedPackage) {
            $matchedPackage = \App\Models\Package::where('status', 'Active')->first();
        }

        if ($matchedPackage) {
            $startDate = now()->toDateString();
            if ($matchedPackage->duration_unit === 'lifetime' || $matchedPackage->duration == -1) {
                $endDate = '2099-12-31';
            } else {
                $baseDays = intval($matchedPackage->duration);
                if ($matchedPackage->duration_unit === 'months') {
                    $baseDays = $baseDays * 30;
                } elseif ($matchedPackage->duration_unit === 'years') {
                    $baseDays = $baseDays * 365;
                }
                $endDate = date('Y-m-d', strtotime($startDate . " + {$baseDays} days"));
            }

            // Create UserPackage subscription
            $userPackage = \App\Models\UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $matchedPackage->id,
                'package_name_snapshot' => $matchedPackage->name,
                'price_snapshot' => $matchedPackage->price,
                'duration_snapshot' => $matchedPackage->duration,
                'duration_unit_snapshot' => $matchedPackage->duration_unit,
                'phone_visibility_snapshot' => $matchedPackage->phone_visibility,
                'max_images_snapshot' => $matchedPackage->max_images,
                'max_videos_snapshot' => $matchedPackage->max_videos,
                'max_news_snapshot' => $matchedPackage->max_news,
                'package_type_snapshot' => $matchedPackage->package_type,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            // Generate Invoice if package is "To Pay" or price > 0
            if ($matchedPackage->package_type === 'To Pay' || $matchedPackage->price > 0) {
                $invoiceNumber = \App\Models\Invoice::generateInvoiceNumber();
                \App\Models\Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $user->id,
                    'user_package_id' => $userPackage->id,
                    'package_id' => $matchedPackage->id,
                    'package_name' => $matchedPackage->name,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'duration' => $matchedPackage->duration,
                    'duration_unit' => $matchedPackage->duration_unit,
                    'amount' => $matchedPackage->price,
                    'amount_paid' => 0.00,
                    'payment_status' => 'Unpaid',
                    'invoice_date' => $startDate,
                    'due_date' => date('Y-m-d', strtotime($startDate . ' + 7 days')),
                ]);
            }
        }

        // Notify Admin and Customer Care of New Talent Registration and Package Invoice
        $pkgName = $matchedPackage ? $matchedPackage->name : 'Standard';
        $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($staffMembers as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'type' => 'new_talent_registration',
                'title' => "🌟 New Talent Registered: {$user->name}",
                'message' => "Talent '{$user->name}' registered under '{$user->category_label}' with '{$pkgName}' package (Contact Visibility: {$phoneVisibility}).",
                'link' => ($staff->role === 'admin') ? route('admin.dashboard') . '#invoices' : route('customer-care.dashboard') . '#invoices',
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $phoneFormats = PhoneHelper::getPossibleFormats($login);

        // Look up user by email or any phone format (06/07..., +255..., or 255...)
        $user = User::where('email', $login)
            ->orWhere(function($query) use ($phoneFormats) {
                $query->whereNotNull('phone')->whereIn('phone', $phoneFormats);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        // Auto-unblock Super Admin / Admin accounts if they were previously blocked
        if ($user->isAdmin() && ($user->is_blocked || AccountBlock::where('user_id', $user->id)->where('status', 'blocked')->exists())) {
            $user->update(['is_blocked' => false]);
            AccountBlock::where('user_id', $user->id)
                ->where('status', 'blocked')
                ->update([
                    'status' => 'unblocked',
                    'unblocked_at' => now(),
                    'issued_by' => 'System (Super Admin Exemption)',
                ]);
        }

        // Check if the user is blocked (non-admin users only)
        if ($user->is_blocked && !$user->isAdmin()) {
            $latestBlock = \App\Models\AccountBlock::where('user_id', $user->id)
                ->where('status', 'blocked')
                ->latest()
                ->first();

            $msgKey = 'Your account has been blocked due to violation of content guidelines. Please contact Customer Care to unblock.';

            if ($latestBlock) {
                if (!empty($latestBlock->reason) && (str_contains(strtolower($latestBlock->reason), 'explicit') || str_contains(strtolower($latestBlock->reason), 'prohibited') || str_contains(strtolower($latestBlock->reason), 'moderation') || str_contains(strtolower($latestBlock->reason), 'nude'))) {
                    $msgKey = 'Your account has been blocked due to violation of content guidelines. Please contact Customer Care to unblock.';
                } elseif ($latestBlock->attempts_count > 0) {
                    $msgKey = 'Your account has been blocked due to multiple consecutive failed login attempts. Please contact Customer Care to unblock.';
                } else {
                    $msgKey = 'Your account has been suspended by administration. Please contact Customer Care to unblock.';
                }
            }

            throw ValidationException::withMessages([
                'login' => __($msgKey),
            ]);
        }

        // Verify password
        if (Hash::check($password, $user->password)) {
            // Reset/clear failed login attempts
            $user->failedLoginAttempts()->delete();

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            \App\Models\UserActivityLog::log('LOGIN', 'Logged into the system.', null, $user->id);

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Admin login successful.');
            }

            if (Auth::user()->role === 'customer_care') {
                return redirect()->route('customer-care.dashboard')->with('success', 'Customer Care Portal login successful.');
            }

            return redirect()->route('dashboard')->with('success', 'Login successful.');
        }

        // Password failed - record attempt
        $user->failedLoginAttempts()->create([
            'attempted_at' => now(),
        ]);

        // Check attempts count in the last 5 minutes
        $intervalMinutes = 5;
        $failedCount = $user->failedLoginAttempts()
            ->where('attempted_at', '>=', now()->subMinutes($intervalMinutes))
            ->count();

        // Block non-admin accounts if failed count > 3 (Super Admin/Admin accounts are exempt)
        if ($failedCount > 3 && !$user->isAdmin()) {
            $user->update(['is_blocked' => true]);

            // Create block record
            AccountBlock::create([
                'user_id' => $user->id,
                'attempts_count' => $failedCount,
                'time_interval' => "{$intervalMinutes} minutes",
                'status' => 'blocked',
            ]);

            // Notify staff
            $adminStaff = User::whereIn('role', ['admin', 'customer_care'])->get();
            foreach ($adminStaff as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'account_blocked',
                    'title' => "🔒 Account Blocked: {$user->name}",
                    'message' => "User {$user->name} was blocked due to {$failedCount} failed attempts within {$intervalMinutes} minutes.",
                    'link' => ($staff->role === 'admin') ? route('admin.dashboard') . '#customer-care' : route('customer-care.dashboard') . '#blocked',
                ]);
            }

            throw ValidationException::withMessages([
                'login' => 'Your account has been blocked due to multiple consecutive failed login attempts. Please contact customer care to unblock.',
            ]);
        }

        throw ValidationException::withMessages([
            'login' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\UserActivityLog::log('LOGOUT', 'Logged out of the system.', null, $user->id);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->input('login');
        $phoneFormats = PhoneHelper::getPossibleFormats($login);

        // Look up user by email or any phone format (06/07..., +255..., or 255...)
        $user = User::where('email', $login)
            ->orWhere(function($query) use ($phoneFormats) {
                $query->whereNotNull('phone')->whereIn('phone', $phoneFormats);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => __('We could not find a user with that email address or phone number.'),
            ]);
        }

        // Check if user has security question set
        if (empty($user->security_question) || empty($user->security_answer)) {
            throw ValidationException::withMessages([
                'login' => __('This account has not configured a security recovery question. Please contact support.'),
            ]);
        }

        return redirect()->route('password.verify-question', ['login' => $login])
            ->with('success', 'Security question retrieved successfully.');
    }

    public function showSecurityQuestionRecovery(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $login = $request->query('login');

        if (empty($login)) {
            return redirect()->route('password.request')->with('error', 'Please enter your email or phone first.');
        }

        $phoneFormats = PhoneHelper::getPossibleFormats($login);

        $user = User::where('email', $login)
            ->orWhere(function($query) use ($phoneFormats) {
                $query->whereNotNull('phone')->whereIn('phone', $phoneFormats);
            })
            ->first();

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'User not found.');
        }

        return view('auth.verify-security-question', [
            'login' => $login,
            'security_question' => $user->security_question,
        ]);
    }

    public function verifySecurityQuestionAndReset(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'security_answer' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $login = $request->input('login');
        $answer = $request->input('security_answer');
        $phoneFormats = PhoneHelper::getPossibleFormats($login);

        $user = User::where('email', $login)
            ->orWhere(function($query) use ($phoneFormats) {
                $query->whereNotNull('phone')->whereIn('phone', $phoneFormats);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'security_answer' => __('User not found.'),
            ]);
        }

        // Verify security answer (case-insensitive check, supports Encrypted, Bcrypt Hash, and Plain Text formats)
        $isCorrect = false;
        $cleanAnswer = trim(\Illuminate\Support\Str::lower($answer));

        if (!empty($user->security_answer)) {
            try {
                $decryptedAnswer = \Illuminate\Support\Facades\Crypt::decryptString($user->security_answer);
                $isCorrect = (trim(\Illuminate\Support\Str::lower($decryptedAnswer)) === $cleanAnswer);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                if (\Illuminate\Support\Str::startsWith($user->security_answer, ['$2y$', '$2a$', '$2b$'])) {
                    $isCorrect = Hash::check($cleanAnswer, $user->security_answer);
                } else {
                    $isCorrect = (trim(\Illuminate\Support\Str::lower($user->security_answer)) === $cleanAnswer);
                }
            }
        }

        if (!$isCorrect) {
            throw ValidationException::withMessages([
                'security_answer' => __('The security answer provided is incorrect.'),
            ]);
        }

        // Upgrade security answer to encrypted format
        $user->security_answer = \Illuminate\Support\Facades\Crypt::encryptString($cleanAnswer);

        // Reset password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear failed login attempts
        $user->failedLoginAttempts()->delete();

        \App\Models\UserActivityLog::log('PASSWORD_RESET', 'Reset password using security question.', null, $user->id);

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please log in with your new password.');
    }
}
