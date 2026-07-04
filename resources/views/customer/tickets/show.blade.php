<x-customer-layout>
    <x-slot:header>{{ $ticket->subject }}</x-slot:header>

    <div class="space-y-4">
        @foreach($ticket->replies as $reply)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 {{ $reply->is_staff ? 'border-l-4 border-blue-500' : 'border-l-4 border-gray-500' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium {{ $reply->is_staff ? 'text-blue-600' : 'text-gray-600' }}">
                        {{ $reply->is_staff ? 'Support Team' : 'You' }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reply->message }}</div>
            </div>
        @endforeach

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="font-medium mb-3 dark:text-white">Add Reply</h3>
            <form action="{{ route('customer.tickets.reply', $ticket) }}" method="POST">
                @csrf
                <textarea name="message" rows="4" required class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Type your message..."></textarea>
                <div class="mt-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</x-customer-layout>