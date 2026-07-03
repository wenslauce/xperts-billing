<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\CannedResponse;
use App\Notifications\TicketReplied;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['customer.user', 'department']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(15);
        $departments = TicketDepartment::where('is_active', true)->get();

        return view('admin.tickets.index', compact('tickets', 'departments'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer.user', 'department', 'replies.user']);
        $cannedResponses = CannedResponse::where(function ($q) use ($ticket) {
            $q->whereNull('department_id')->orWhere('department_id', $ticket->department_id);
        })->get();

        return view('admin.tickets.show', compact('ticket', 'cannedResponses'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => true,
        ]);

        $ticket->update([
            'status' => 'replied',
            'last_reply_at' => now(),
        ]);

        if ($ticket->customer->user) {
            $ticket->customer->user->notify(new TicketReplied($ticket));
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket closed.');
    }

    public function priority(Request $request, Ticket $ticket)
    {
        $validated = $request->validate(['priority' => 'required|in:low,medium,high,urgent']);
        $ticket->update(['priority' => $validated['priority']]);
        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Priority updated.');
    }
}