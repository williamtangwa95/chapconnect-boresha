<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\AccountBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSupportTicketRequest;
use App\Http\Requests\StaffSupportTicketRequest;

class CustomerCareController extends Controller
{
    /**
     * Display Customer Care Support Issues Dashboard.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $priorityFilter = $request->query('priority');
        $searchQuery = $request->query('search');

        $query = SupportTicket::with(['user', 'assignedStaff'])->latest();

        if ($statusFilter && in_array($statusFilter, ['open', 'pending', 'in_progress', 'approved', 'resolved', 'cancelled', 'closed'])) {
            $query->where('status', $statusFilter);
        }

        if ($priorityFilter && in_array($priorityFilter, ['low', 'medium', 'high', 'urgent'])) {
            $query->where('priority', $priorityFilter);
        }

        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('ticket_number', 'like', "%{$searchQuery}%")
                  ->orWhere('subject', 'like', "%{$searchQuery}%")
                  ->orWhere('reporter_name', 'like', "%{$searchQuery}%")
                  ->orWhere('reporter_email', 'like', "%{$searchQuery}%");
            });
        }

        $tickets = $query->get();

        // System Statistics
        $totalTickets = SupportTicket::count();
        $openTickets = SupportTicket::whereIn('status', ['open', 'pending'])->count();
        $inProgressTickets = SupportTicket::whereIn('status', ['in_progress', 'approved'])->count();
        $resolvedTickets = SupportTicket::whereIn('status', ['resolved', 'closed', 'cancelled'])->count();
        $urgentTickets = SupportTicket::where('priority', 'urgent')->whereNotIn('status', ['closed', 'resolved', 'cancelled'])->count();

        // Fetch staff list for assignment dropdown
        $staffMembers = User::whereIn('role', ['admin', 'customer_care', 'staff'])->orderBy('name')->get();

        $blockedAccounts = AccountBlock::with('user')->latest()->get();
        $allUsers = User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email', 'phone', 'role', 'security_question', 'security_answer']);
        $contactRequests = \App\Models\ContactRequest::with(['targetUser', 'requesterUser'])->latest()->get();
        $paymentRequests = \App\Models\TalentPaymentRequest::with(['user', 'payer'])->latest()->get();

        return view('customer_care.index', compact(
            'tickets',
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'urgentTickets',
            'staffMembers',
            'statusFilter',
            'priorityFilter',
            'searchQuery',
            'blockedAccounts',
            'allUsers',
            'contactRequests',
            'paymentRequests'
        ));
    }

    /**
     * Store a new support ticket / issue from Customer Care Portal.
     */
    public function store(StaffSupportTicketRequest $request)
    {
        

        $ticket = SupportTicket::create([
            'reporter_name' => $request->reporter_name,
            'reporter_email' => $request->reporter_email,
            'reporter_phone' => $request->reporter_phone,
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->input('status', 'pending'),
            'description' => $request->description,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);
        if ($request->assigned_to) {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->save();
        }

        // Send notification to assigned staff member if set
        if ($request->assigned_to) {
            Notification::create([
                'user_id' => $request->assigned_to,
                'type' => 'ticket_assigned',
                'title' => "Assigned Ticket #{$ticket->ticket_number}",
                'message' => "You have been assigned to handle support issue: '{$ticket->subject}'",
                'link' => route('admin.dashboard') . '#assigned-tickets',
            ]);
        }

        return redirect()->back()->with('success', "Support ticket '{$ticket->ticket_number}' created successfully.");
    }

    /**
     * Submit support ticket / issue directly from User Dashboard.
     */
    public function userSubmit(StoreSupportTicketRequest $request)
    {
        $user = Auth::user();

        $ticket = SupportTicket::create([
            'user_id' => $user ? $user->id : null,
            'reporter_name' => $user ? $user->name : ($request->reporter_name ?? 'Guest User'),
            'reporter_email' => $user ? $user->email : ($request->reporter_email ?? 'guest@chapconnect.com'),
            'reporter_phone' => $user ? $user->phone : $request->reporter_phone,
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->input('status', 'pending'),
            'description' => $request->description,
        ]);

        // Notify All Customer Care & Admin Staff of New Public Ticket
        $adminStaff = User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($adminStaff as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'type' => 'new_ticket',
                'title' => "New Ticket Logged: #{$ticket->ticket_number}",
                'message' => "'{$ticket->reporter_name}' submitted issue: {$ticket->subject}",
                'link' => route('customer-care.dashboard'),
            ]);
        }

        return redirect()->back()->with('success', "Your issue has been logged under ticket #{$ticket->ticket_number}. Our Customer Care team will review it shortly!");
    }

    /**
     * Update an issue's status, priority, assignment, recommendations, or resolution notes.
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $oldAssignedTo = $ticket->assigned_to;
        $oldStatus = $ticket->status;

        $request->validate([
            'status' => 'required|string|in:open,pending,in_progress,approved,resolved,cancelled,closed',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $ticket->status = $request->status;
            $ticket->priority = $request->priority;
            $ticket->assigned_to = $request->assigned_to;
            $ticket->resolution_notes = $request->resolution_notes;
            $ticket->recommendations = $request->recommendations;
            $ticket->save();

        // Notify assigned staff if newly assigned
        if ($request->assigned_to && $request->assigned_to != $oldAssignedTo) {
            Notification::create([
                'user_id' => $request->assigned_to,
                'type' => 'ticket_assigned',
                'title' => "Assigned Ticket #{$ticket->ticket_number}",
                'message' => "You have been assigned to handle support issue: '{$ticket->subject}'",
                'link' => route('admin.dashboard') . '#assigned-tickets',
            ]);
        }

        // Notify reporter user if registered and status changed
        if ($ticket->user_id && $oldStatus !== $request->status) {
            $formattedStatus = ucfirst(str_replace('_', ' ', $request->status));
            Notification::create([
                'user_id' => $ticket->user_id,
                'type' => 'ticket_status',
                'title' => "Ticket #{$ticket->ticket_number} Status Updated",
                'message' => "Your ticket status has been changed to '{$formattedStatus}'.",
                'link' => route('dashboard'),
            ]);
        }

        return redirect()->back()->with('success', "Support ticket '{$ticket->ticket_number}' updated successfully.");
    }

    /**
     * Staff specific action on assigned ticket (Approving, Canceling, Pending, Recommending).
     */
    public function staffAction(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        // Ensure user is assigned staff or admin
        if ($ticket->assigned_to !== Auth::id() && !in_array(Auth::user()->role, ['admin', 'customer_care'])) {
            return redirect()->back()->with('error', 'You are not authorized to take action on this ticket.');
        }

        $request->validate([
            'status' => 'required|string|in:pending,approved,in_progress,resolved,cancelled,closed',
            'recommendations' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        $ticket->status = $request->status;
            $ticket->recommendations = $request->recommendations;
            $ticket->resolution_notes = $request->resolution_notes ?? $ticket->resolution_notes;
            $ticket->save();

        // Notify reporter user if registered
        if ($ticket->user_id) {
            $formattedStatus = ucfirst(str_replace('_', ' ', $request->status));
            Notification::create([
                'user_id' => $ticket->user_id,
                'type' => 'ticket_status',
                'title' => "Ticket #{$ticket->ticket_number} Update ({$formattedStatus})",
                'message' => "Assigned staff updated ticket #{$ticket->ticket_number} to '{$formattedStatus}'.",
                'link' => route('dashboard'),
            ]);
        }

        return redirect()->back()->with('success', "Action saved for assigned ticket #{$ticket->ticket_number}.");
    }

    /**
     * Delete a support ticket.
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $num = $ticket->ticket_number;
        $ticket->delete();

        return redirect()->back()->with('success', "Support ticket '{$num}' deleted.");
    }

    /**
     * Unblock a user account and log details.
     */
    public function unblockAccount(Request $request, $id)
    {
        $block = AccountBlock::findOrFail($id);

        $request->validate([
            'customer_complaint' => 'required|string',
            'requested_by' => 'required|string|max:255',
            'status' => 'required|string|in:unblocked',
        ]);

        $block->update([
            'customer_complaint' => $request->customer_complaint,
            'requested_by' => $request->requested_by,
            'issued_by' => auth()->user()->name,
            'status' => 'unblocked',
            'unblocked_at' => now(),
        ]);

        $user = $block->user;
        if ($user) {
            $user->update(['is_blocked' => false]);
            $user->failedLoginAttempts()->delete();

            // Notify Admin & Customer Care staff of Account Unblock
            $adminStaff = User::whereIn('role', ['admin', 'customer_care'])->get();
            foreach ($adminStaff as $staff) {
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'account_unblocked',
                    'title' => "🔓 Account Unblocked: {$user->name}",
                    'message' => "Account {$user->name} was unblocked by " . auth()->user()->name . ".",
                    'link' => ($staff->role === 'admin') ? route('admin.dashboard') . '#customer-care' : route('customer-care.dashboard') . '#blocked',
                ]);
            }
        }

        return redirect()->back()->with('success', "User account '" . ($user ? $user->name : 'Unknown') . "' unblocked successfully.");
    }
}
