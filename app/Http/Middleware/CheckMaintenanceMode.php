<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MaintenanceService;
use App\Models\User;
use App\Helpers\PhoneHelper;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string|null  $feature
     */
    public function handle(Request $request, Closure $next, ?string $feature = null): Response
    {
        // 1. Logged in Super Admins are NEVER restricted
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        if (empty($feature)) {
            return $next($request);
        }

        // 2. Check if the specific feature is restricted
        if (!MaintenanceService::isFeatureRestricted($feature)) {
            return $next($request);
        }

        // 3. SPECIAL EXEMPTION: Admin logging in during Login Maintenance
        // If login feature is restricted, allow POST /login if credentials belong to an Admin account
        if (strtolower($feature) === 'login' && $request->isMethod('post') && $request->filled('login')) {
            $loginInput = $request->input('login');
            $phoneFormats = PhoneHelper::getPossibleFormats($loginInput);

            $user = User::where('email', $loginInput)
                ->orWhere(function($query) use ($phoneFormats) {
                    $query->whereNotNull('phone')->whereIn('phone', $phoneFormats);
                })
                ->first();

            // If the user account attempting login is an admin, allow the request
            if ($user && $user->role === 'admin') {
                return $next($request);
            }
        }

        // 4. Feature IS restricted for this user/guest
        $message = MaintenanceService::getMessage();
        $details = MaintenanceService::getDetails();

        // For AJAX / JSON requests, return JSON error response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'maintenance' => true,
                'details' => $details,
            ], 503);
        }

        // For POST form submissions, redirect back with error toast
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            return redirect()->back()
                ->withInput()
                ->with('error', $message);
        }

        // For GET page requests (e.g. GET /login, GET /register), render the dedicated maintenance Blade view
        return response()->view('maintenance', [
            'feature' => $feature,
            'message' => $message,
            'details' => $details,
        ], 503);
    }
}
