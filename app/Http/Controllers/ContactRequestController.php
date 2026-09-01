<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ContactRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactRequestController extends Controller
{
    /**
     * Public visitor submits connection request.
     */
    public function store(Request $request, $targetUserId)
    {
        $targetUser = User::where('role', 'user')->where('is_published', true)->findOrFail($targetUserId);

        // Check if contact information is private
        if ($targetUser->currentPackageDetails()['phone_visibility'] !== 'No') {
            return redirect()->back()->withErrors(['connection' => 'This talent\'s phone number is already publicly visible.']);
        }

        // Validate requester inputs
        $validator = Validator::make($request->all(), [
            'requester_full_name' => 'required|string|max:255',
            'contact_type' => 'required|string|in:email,phone,whatsapp',
            'contact_value' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $contactType = $request->contact_type;
        $contactValue = $request->contact_value;

        // Custom validation based on contact type
        if ($contactType === 'email') {
            if (!filter_var($contactValue, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withErrors(['contact_value' => 'Please enter a valid email address.'])->withInput();
            }
        } elseif (in_array($contactType, ['phone', 'whatsapp'])) {
            // Strip spaces, dashes, etc. and check digits
            $cleaned = preg_replace('/[^0-9+]/', '', $contactValue);
            if (strlen($cleaned) < 8 || strlen($cleaned) > 20) {
                return redirect()->back()->withErrors(['contact_value' => 'Please enter a valid phone number (8-20 digits).'])->withInput();
            }
        }

        // IP-based Rate Limiting (Spam protection) - max 5 requests per hour
        $ipAddress = $request->ip();
        $recentIpRequestsCount = ContactRequest::where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentIpRequestsCount >= 5) {
            return redirect()->back()->withErrors(['spam' => 'Too many connection requests from your IP. Please try again in an hour.'])->withInput();
        }

        // Throttling Duplicate Requests: check for identical pending request from same sender contact to same target user
        $duplicateExists = ContactRequest::where('target_user_id', $targetUser->id)
            ->where('status', 'Pending')
            ->where('contact_value', $contactValue)
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->exists();

        if ($duplicateExists) {
            return redirect()->back()->withErrors(['spam' => 'You have already submitted an identical connection request to this talent recently.'])->withInput();
        }

        // Create the connection request
        $contactRequest = ContactRequest::create([
            'target_user_id' => $targetUser->id,
            'requester_user_id' => Auth::id(), // null if guest
            'requester_type' => 'guest', // always guest for this feature
            'requester_full_name' => $request->requester_full_name,
            'contact_type' => $contactType,
            'contact_value' => $contactValue,
            'region' => $request->region,
            'message' => $request->message,
            'status' => 'Pending',
            'ip_address' => $ipAddress,
        ]);

        // 1. Notify Target User
        Notification::create([
            'user_id' => $targetUser->id,
            'type' => 'ContactRequestNotification',
            'title' => 'New Connection Request',
            'message' => "A guest named {$contactRequest->requester_full_name} wants to connect with you via {$contactRequest->contact_type}.",
            'link' => route('dashboard') . '?tab=requests',
        ]);

        // 2. Notify Admin and Customer Care staff
        $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($staffMembers as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'type' => 'ContactRequestNotification',
                'title' => "📬 New Guest Contact Request",
                'message' => "Guest '{$contactRequest->requester_full_name}' requested connection with talent '{$targetUser->name}' ({$contactRequest->contact_type}).",
                'link' => ($staff->role === 'admin') 
                    ? route('admin.dashboard') . '#requests' 
                    : route('customer-care.dashboard') . '#requests',
            ]);
        }

        return redirect()->back()->with('success', 'Your request to connect has been sent successfully. The user will review your details.');
    }

    /**
     * Target user handles a connection request (Approve/Reject).
     */
    public function userAction(Request $request, $id)
    {
        $contactRequest = ContactRequest::findOrFail($id);

        // IDOR / Security check: verify this request is addressed to the authenticated user
        if ($contactRequest->target_user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this connection request.');
        }

        $request->validate([
            'action' => 'required|string|in:approve,reject',
        ]);

        $action = $request->action;
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';

        $contactRequest->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Notify Admin & Customer Care about target user action
        $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($staffMembers as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'type' => 'ContactRequestNotification',
                'title' => "Connection Request {$status}",
                'message' => "Talent {$contactRequest->targetUser->name} has {$action}d guest {$contactRequest->requester_full_name}'s request.",
                'link' => ($staff->role === 'admin') 
                    ? route('admin.dashboard') . '#requests' 
                    : route('customer-care.dashboard') . '#requests',
            ]);
        }

        return redirect()->back()->with('success', "Connection request has been successfully " . strtolower($status) . ".");
    }

    /**
     * Admin/Customer Care updates a connection request status or internal notes.
     */
    public function adminAction(Request $request, $id)
    {
        // Security check: only admin or customer care
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->role, ['admin', 'customer_care'])) {
            abort(403, 'Unauthorized administrative action.');
        }

        $contactRequest = ContactRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:Pending,Approved,Rejected,Completed',
            'admin_notes' => 'nullable|string',
            'staff_notes' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
            'reviewed_by' => $currentUser->id,
            'reviewed_at' => now(),
            'staff_notes' => $request->staff_notes,
        ];

        // Only Admin can modify admin-specific notes
        if ($currentUser->role === 'admin') {
            $data['admin_notes'] = $request->admin_notes;
        }

        $contactRequest->update($data);

        // Notify target user of the administrative update
        Notification::create([
            'user_id' => $contactRequest->target_user_id,
            'type' => 'ContactRequestNotification',
            'title' => "Connection Request Updated",
            'message' => "Admin has updated your connection request status from guest {$contactRequest->requester_full_name} to '{$request->status}'.",
            'link' => route('dashboard') . '?tab=requests',
        ]);

        return redirect()->back()->with('success', 'Connection request updated successfully.');
    }
}
