<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Store a new booking request for a talent.
     */
    public function store(Request $request, $talentId)
    {
        $talent = User::where('role', 'user')->where('is_published', true)->findOrFail($talentId);

        $validator = Validator::make($request->all(), [
            'client_name'   => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'client_phone'  => 'required|string|max:50',
            'booking_date'  => 'required|date|after_or_equal:today',
            'message'       => 'nullable|string|max:1500',
        ], [
            'client_name.required'  => __('Please enter your full name.'),
            'location.required'     => __('Please specify the event / meeting location.'),
            'client_phone.required' => __('Please enter your phone number.'),
            'booking_date.required' => __('Please select the booking date.'),
            'booking_date.after_or_equal' => __('Booking date must be today or a future date.'),
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator, 'booking')->withInput();
        }

        // Validate phone number format (at least 8 numeric digits)
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->client_phone);
        if (strlen($cleanPhone) < 8 || strlen($cleanPhone) > 20) {
            $msg = __('Please enter a valid phone number (at least 8 digits).');
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => ['client_phone' => [$msg]]], 422);
            }
            return redirect()->back()->withErrors(['client_phone' => $msg], 'booking')->withInput();
        }

        $ipAddress = $request->ip();

        // Spam rate limit: max 5 bookings per hour from same IP
        $recentCount = Booking::where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentCount >= 8) {
            $spamMsg = __('Too many booking requests from your connection. Please wait an hour before trying again.');
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $spamMsg], 429);
            }
            return redirect()->back()->withErrors(['spam' => $spamMsg], 'booking')->withInput();
        }

        // Check for duplicate pending booking for same talent and phone submitted in past 10 minutes
        $duplicate = Booking::where('talent_id', $talent->id)
            ->where('client_phone', $request->client_phone)
            ->where('booking_date', $request->booking_date)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if ($duplicate) {
            $dupMsg = __('You have already submitted a booking request for this talent on this date recently.');
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $dupMsg], 422);
            }
            return redirect()->back()->withErrors(['duplicate' => $dupMsg], 'booking')->withInput();
        }

        $booking = Booking::create([
            'talent_id'    => $talent->id,
            'client_name'  => trim($request->client_name),
            'client_phone' => trim($request->client_phone),
            'location'     => trim($request->location),
            'booking_date' => $request->booking_date,
            'message'      => $request->message ? trim($request->message) : null,
            'status'       => 'Pending',
            'ip_address'   => $ipAddress,
        ]);

        $formattedDate = \Carbon\Carbon::parse($booking->booking_date)->format('d M Y');

        // 1. Notify the Talent
        Notification::create([
            'user_id' => $talent->id,
            'type'    => 'BookingNotification',
            'title'   => '📅 ' . __('New Talent Booking Request!'),
            'message' => "Client {$booking->client_name} has booked you for {$formattedDate} at {$booking->location}. Phone: {$booking->client_phone}.",
            'link'    => route('dashboard') . '#tab-bookings',
        ]);

        // 2. Notify Admin and Customer Care staff
        $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($staffMembers as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'type'    => 'BookingNotification',
                'title'   => "📅 New Booking for '{$talent->name}'",
                'message' => "Client '{$booking->client_name}' placed a booking for talent '{$talent->name}' on {$formattedDate} ({$booking->location}).",
                'link'    => ($staff->role === 'admin')
                    ? route('admin.dashboard') . '#tab-bookings'
                    : route('customer-care.dashboard') . '#tab-bookings',
            ]);
        }

        $successMessage = __('Your booking has been submitted successfully! The talent and customer care team will review your request.');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'booking' => $booking,
            ]);
        }

        return redirect()->back()->with('success_booking', $successMessage);
    }

    /**
     * Act on a booking (Talent, Customer Care, or Admin).
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::with('talent')->findOrFail($id);
        $user = Auth::user();

        // Authorization check: User must be either the talent owner, admin, or customer care
        $isTalentOwner = ($booking->talent_id === $user->id);
        $isStaff = in_array($user->role, ['admin', 'customer_care']);

        if (!$isTalentOwner && !$isStaff) {
            abort(403, __('You are not authorized to manage this booking.'));
        }

        $request->validate([
            'status'      => 'required|string|in:Pending,Confirmed,Cancelled,Completed',
            'action_note' => 'nullable|string|max:1000',
        ]);

        $previousStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update([
            'status'            => $newStatus,
            'action_by_user_id' => $user->id,
            'action_note'       => $request->action_note ? trim($request->action_note) : $booking->action_note,
            'action_at'         => now(),
        ]);

        $formattedDate = \Carbon\Carbon::parse($booking->booking_date)->format('d M Y');

        // Notify other parties about the action taken
        if ($isTalentOwner) {
            // Talent acted -> Notify Admin and Customer Care
            $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
            foreach ($staffMembers as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type'    => 'BookingNotification',
                    'title'   => "📅 Talent Updated Booking #{$booking->id}",
                    'message' => "Talent '{$booking->talent->name}' marked booking with client '{$booking->client_name}' ({$formattedDate}) as '{$newStatus}'.",
                    'link'    => ($staff->role === 'admin')
                        ? route('admin.dashboard') . '#tab-bookings'
                        : route('customer-care.dashboard') . '#tab-bookings',
                ]);
            }
        } else {
            // Staff acted -> Notify the Talent
            Notification::create([
                'user_id' => $booking->talent_id,
                'type'    => 'BookingNotification',
                'title'   => "📅 Booking #{$booking->id} Status Updated",
                'message' => "Staff member '{$user->name}' updated your booking from '{$booking->client_name}' ({$formattedDate}) to '{$newStatus}'.",
                'link'    => route('dashboard') . '#tab-bookings',
            ]);
        }

        $successMsg = __('Booking status updated successfully to') . ' ' . __($newStatus);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'status'  => $newStatus,
            ]);
        }

        return redirect()->back()->with('success', $successMsg);
    }
}
