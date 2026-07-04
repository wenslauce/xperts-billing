<x-admin-layout>
    <x-slot:header>{{ $ticket->subject }}</x-slot:header>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-4">
            @foreach($ticket->replies as $reply)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 {{ $reply->is_staff ? 'border-l-4 border-blue-500' : 'border-l-4 border-gray-500' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium {{ $reply->is_staff ? 'text-blue-600' : 'text-gray-600' }}">
                            {{ $reply->is_staff ? 'Staff - ' . ($reply->user->name ?? 'Admin') : $ticket->customer->user->name ?? 'Customer' }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reply->message }}</div>
                </div>
            @endforeach

            @if($ticket->status !== 'closed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h3 class="font-medium mb-3 dark:text-white">Reply as Staff</h3>
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="4" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your reply..." required></textarea>
                        <div class="mt-3 flex items-center justify-between">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Send Reply</button>
                            <a href="{{ route('admin.tickets.close', $ticket) }}" class="text-sm text-red-600 hover:text-red-900" onclick="return confirm('Close this ticket?')">Close Ticket</a>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-medium mb-3 dark:text-white">Details</h3>
                <dl class="space-y-2 text-sm">
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium dark:text-white">{{ ucfirst($ticket->status) }}</dd>
                    <dt class="text-gray-500">Priority</dt>
                    <dd class="font-medium dark:text-white">{{ ucfirst($ticket->priority) }}</dd>
                    <dt class="text-gray-500">Department</dt>
                    <dd class="font-medium dark:text-white">{{ $ticket->department->name ?? 'General' }}</dd>
                    <dt class="text-gray-500">Customer</dt>
                    <dd class="font-medium dark:text-white">{{ $ticket->customer->user->name ?? 'N/A' }}</dd>
                    <dt class="text-gray-500">Created</dt>
                    <dd class="font-medium dark:text-white">{{ $ticket->created_at->format('d M Y H:i') }}</dd>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-medium mb-3 dark:text-white">Change Priority</h3>
                <form action="{{ route('admin.tickets.priority', $ticket) }}" method="POST" class="flex gap-2">
                    @csrf
                    <select name="priority" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    <button type="submit" class="px-3 py-2 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700">Set</button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-medium mb-3 dark:text-white">Canned Responses</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @forelse($cannedResponses as $canned)
                        <button type="button" onclick="insertCanned(`{{ addslashes($canned->message) }}`)" class="w-full text-left p-2 text-sm bg-gray-50 dark:bg-gray-700 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                            {{ $canned->title }}
                        </button>
                    @empty
                        <p class="text-sm text-gray-500">No canned responses available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function insertCanned(text) {
            const textarea = document.querySelector('textarea[name="message"]');
            if (textarea) {
                textarea.value = text;
                textarea.focus();
            }
        }
    </script>
</x-admin-layout>