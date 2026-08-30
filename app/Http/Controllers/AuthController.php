<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FailedLoginAttempt;
use App\Models\AccountBlock;
use App\Models\Notification;
use App\Mail\ResetPasswordCodeMail;
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
            'phone' => 'required_without:email|nullable|string|max:30|unique:users',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email already taken',
            'phone.unique' => 'Phone number already taken',
        ]);

        $q = $request->input('security_question');
        $a = $request->input('security_answer');
        
        similar_text(\Illuminate\Support\Str::lower($q), \Illuminate\Support\Str::lower($a), $percent);
        if ($percent >= 50) {
            throw ValidationException::withMessages([
                'security_answer' => 'The security question and answer are too similar (must be less than 50% match).',
            ]);
        }

        $category = $request->input('category');
        $categoryLabel = self::categories()[$category];

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?: null,
            'password' => Hash::make($request->password),
            'category' => $category,
            'category_label' => $categoryLabel,
            'phone' => $request->phone ?: null,
            'country' => 'East Africa Tanzania',
            'role' => 'user', // default registration role
            'security_question' => $q,
            'security_answer' => \Illuminate\Support\Facades\Crypt::encryptString(\Illuminate\Support\Str::lower($a)),
        ]);

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

        // Look up user by email or phone
        $user = User::where('email', $login)
            ->orWhere(function($query) use ($login) {
                $query->whereNotNull('phone')->where('phone', $login);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        // Check if the user is blocked
        if ($user->is_blocked) {
            throw ValidationException::withMessages([
                'login' => 'Your account has been blocked due to multiple consecutive failed login attempts. Please contact customer care to unblock.',
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

        if ($failedCount > 3) {
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
                    'title' => "Account Blocked: {$user->name}",
                    'message' => "User {$user->name} blocked due to {$failedCount} failed attempts within {$intervalMinutes} minutes.",
                    'link' => route('customer-care.dashboard') . '#blocked-accounts',
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

        // Look up user by email or phone
        $user = User::where('email', $login)
            ->orWhere(function($query) use ($login) {
                $query->whereNotNull('phone')->where('phone', $login);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => 'We could not find a user with that email address or phone number.',
            ]);
        }

        // Check if user has security question set
        if (empty($user->security_question) || empty($user->security_answer)) {
            throw ValidationException::withMessages([
                'login' => 'This account has not configured a security recovery question. Please contact support.',
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

        $user = User::where('email', $login)
            ->orWhere(function($query) use ($login) {
                $query->whereNotNull('phone')->where('phone', $login);
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

        $user = User::where('email', $login)
            ->orWhere(function($query) use ($login) {
                $query->whereNotNull('phone')->where('phone', $login);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'security_answer' => 'User not found.',
            ]);
        }

        // Verify security answer (case-insensitive check, supports both Encrypted and old Hashed formats)
        $isCorrect = false;
        try {
            $decryptedAnswer = \Illuminate\Support\Facades\Crypt::decryptString($user->security_answer);
            $isCorrect = ($decryptedAnswer === \Illuminate\Support\Str::lower($answer));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $isCorrect = Hash::check(\Illuminate\Support\Str::lower($answer), $user->security_answer);
        }

        if (!$isCorrect) {
            throw ValidationException::withMessages([
                'security_answer' => 'The security answer provided is incorrect.',
            ]);
        }

        // Reset password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear failed login attempts
        $user->failedLoginAttempts()->delete();

        \App\Models\UserActivityLog::log('PASSWORD_RESET', 'Reset password using security question.', null, $user->id);

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please log in with your new password.');
    }
}
