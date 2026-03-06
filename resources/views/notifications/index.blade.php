@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">NOTIFICATIONS</h2>
        <a href="{{ route('notifications.readAll') }}" class="text-sm text-blue-400 hover:text-white">Mark all as read</a>
    </div>
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-[#0a0a12] border border-white/10 p-4 rounded-xl {{ $notification->read_at ? 'opacity-60' : 'border-l-4 border-l-blue-500' }}">
                <p class="font-bold text-white">{{ $notification->data['title'] }}</p>
                <p class="text-gray-400">{{ $notification->data['message'] }}</p>
                <p class="text-xs text-gray-600 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-center py-10">No notifications found.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection