<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\Integrations\DirectAdmin\DirectAdminClient;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::latest()->paginate(10);
        return view('admin.servers.index', compact('servers'));
    }

    public function create()
    {
        return view('admin.servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hostname' => 'required|string|max:255',
            'api_username' => 'required|string|max:255',
            'api_key' => 'required|string',
            'server_group' => 'nullable|string|max:255',
            'max_accounts' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $server = Server::create($validated + ['current_accounts' => 0]);

        // Test connection immediately
        $client = new DirectAdminClient($server);
        if (! $client->testConnection()) {
            return redirect()->route('admin.servers.index')
                ->with('warning', 'Server added but connection test failed. Please verify the credentials.');
        }

        return redirect()->route('admin.servers.index')
            ->with('success', 'Server created and connection verified successfully.');
    }

    public function edit(Server $server)
    {
        return view('admin.servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hostname' => 'required|string|max:255',
            'api_username' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'server_group' => 'nullable|string|max:255',
            'max_accounts' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['api_key'])) {
            unset($validated['api_key']);
        }

        $server->update($validated);

        return redirect()->route('admin.servers.index')
            ->with('success', 'Server updated successfully.');
    }

    public function destroy(Server $server)
    {
        if ($server->services()->where('status', 'active')->count() > 0) {
            return redirect()->route('admin.servers.index')
                ->with('error', 'Cannot delete server with active services. Please migrate them first.');
        }

        $server->delete();
        return redirect()->route('admin.servers.index')
            ->with('success', 'Server deleted successfully.');
    }

    public function testConnection(Server $server)
    {
        $client = new DirectAdminClient($server);
        $result = $client->testConnection();

        if ($result) {
            return redirect()->route('admin.servers.index')
                ->with('success', 'Connection to ' . $server->name . ' verified successfully.');
        }

        return redirect()->route('admin.servers.index')
            ->with('error', 'Connection failed. Please check hostname and credentials.');
    }
}