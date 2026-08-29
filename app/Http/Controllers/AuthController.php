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
        ], [
            'email.unique' => 'Email already taken',
            'phone.unique' => 'Phone number already taken',
        ]);

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

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'We could not find a user with that email address.',
            ]);
        }

        // Generate 6-digit verification code
        $code = sprintf('%06d', mt_rand(0, 999999));

        // Save token to password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ]
        );

        // Send verification email
        try {
            Mail::to($request->email)->send(new ResetPasswordCodeMail($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send reset code email: ' . $e->getMessage());
        }

        return redirect()->route('password.reset', ['email' => $request->email])
            ->with('success', 'Verification code sent to ' . $request->email . '. Please check your email.');
    }

    public function showResetPassword(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.reset-password', [
            'email' => $request->query('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string|digits:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord) {
            throw ValidationException::withMessages([
                'code' => 'No password reset code requested for this email, or the code has expired.',
            ]);
        }

        // Check if code is expired (valid for 30 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(30)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            throw ValidationException::withMessages([
                'code' => 'Verification code has expired. Please request a new code.',
            ]);
        }

        // Verify token
        if (!Hash::check($request->code, $resetRecord->token)) {
            throw ValidationException::withMessages([
                'code' => 'The verification code provided is incorrect.',
            ]);
        }

        // Reset password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Remove token record
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please log in with your new password.');
    }
}
