<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketDepartment;
use Illuminate\Http\Request;

class TicketDepartmentController extends Controller
{
    public function index()
    {
        $departments = TicketDepartment::withCount('tickets')->latest()->paginate(10);
        return view('admin.tickets.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        TicketDepartment::create($validated);

        return redirect()->route('admin.ticket-departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(Request $request, TicketDepartment $ticketDepartment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $ticketDepartment->update($validated);

        return redirect()->route('admin.ticket-departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(TicketDepartment $ticketDepartment)
    {
        $ticketDepartment->delete();
        return redirect()->route('admin.ticket-departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}