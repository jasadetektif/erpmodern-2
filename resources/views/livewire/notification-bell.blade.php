<div x-data="{ open: false }" wire:poll.10s="loadNotifications" class="relative">
    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
        {{-- Animasi Lonceng --}}
        <svg class="h-6 w-6 @if($unreadCount > 0) animate-swing @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        {{-- Titik Merah Notifikasi --}}
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500 ring-2 ring-white"></span>
        @endif
    </button>

    {{-- Dropdown Notifikasi --}}
    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-20">
        <div class="p-4 font-bold border-b">{{__('Notifications')}}</div>
        <div class="py-1">
            @forelse($notifications as $notification)
    <div class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex justify-between items-start">
        
        {{-- Logika Tautan Dinamis --}}
        @php $link = '#'; @endphp
        @if(isset($notification->data['project_id']))
            @php $link = route('projects.show', $notification->data['project_id']); @endphp
        @endif
        @if(isset($notification->data['pr_id']))
            @php $link = route('procurement.pr.show', $notification->data['pr_id']); @endphp
        @endif

        <a href="{{ $link }}" class="flex-1">
            <p>{{ $notification->data['message'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
        </a>

        <button wire:click="markAsRead('{{ $notification->id }}')" title="Tandai sudah dibaca" class="text-gray-400 hover:text-blue-500 ml-2">&times;</button>
    </div>
@empty
    <p class="px-4 py-3 text-sm text-gray-500">{{__('No new notifications')}}</p>
@endforelse

        </div>
    </div>
</div>
