<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Notifications\NewTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $tickets = Ticket::where('customer_id', $customer->id)
            ->with('department', 'replies')
            ->latest()
            ->paginate(10);

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $departments = TicketDepartment::where('is_active', true)->get();
        return view('customer.tickets.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $customer = auth()->user()->customer;

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'department_id' => 'nullable|exists:ticket_departments,id',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'department_id' => $validated['department_id'],
            'subject' => $validated['subject'],
            'status' => 'open',
            'priority' => 'medium',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        // Notify admins
        $admins = \App\Models\User::role(['super-admin', 'admin', 'support'])->get();
        Notification::send($admins, new NewTicket($ticket));

        return redirect()->route('customer.tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $customer = auth()->user()->customer;
        if ($ticket->customer_id !== $customer->id) {
            abort(403);
        }

        $ticket->load('replies.user', 'department');
        return view('customer.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $customer = auth()->user()->customer;
        if ($ticket->customer_id !== $customer->id) {
            abort(403);
        }

        $validated = $request->validate(['message' => 'required|string']);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        $ticket->update([
            'status' => 'open',
            'last_reply_at' => now(),
        ]);

        return redirect()->route('customer.tickets.show', $ticket)
            ->with('success', 'Reply sent.');
    }
}