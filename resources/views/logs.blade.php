@extends('layouts.app')
@section('title', 'Activity Logs')

@section('content')
<div class="bg-[#05050a]/50 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
    
    <div class="p-6 border-b border-white/5 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-white tracking-wide">System Logs</h2>
            <p class="text-[10px] font-medium text-gray-500 uppercase tracking-widest mt-1">Track User Activity and Events</p>
        </div>
        <div class="px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20">
            <span class="text-[10px] text-blue-400 font-bold uppercase">{{ $logs->total() }} Records Found</span>
        </div>
    </div>

    {{-- HEADERS --}}
    <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-white/[0.02] border-b border-white/5 text-[10px] font-black text-gray-500 uppercase tracking-widest">
        <div class="col-span-2">Date & Time</div>
        <div class="col-span-3">User</div>
        <div class="col-span-5">Activity</div>
        <div class="col-span-2 text-right">IP Address</div>
    </div>

    {{-- LIST --}}
    <div class="divide-y divide-white/5">
        @forelse($logs as $log)
            <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center hover:bg-white/[0.02] transition-all group">
                
                {{-- COLUMN 1: DATE --}}
                <div class="col-span-2 flex flex-col justify-center min-w-0">
                    <span class="text-[11px] font-bold text-gray-300 truncate">
                        {{ $log->created_at->format('M d, Y') }}
                    </span>
                    <span class="text-[10px] text-gray-500 font-mono truncate">
                        {{ $log->created_at->format('h:i A') }}
                    </span>
                </div>

                {{-- COLUMN 2: USER --}}
                <div class="col-span-3 flex flex-col min-w-0 pr-4">
                    @if($log->user)
                        <p class="text-[12px] font-bold text-white group-hover:text-blue-400 transition-colors truncate" title="{{ $log->user->name }}">
                            {{ $log->user->name }}
                        </p>
                        
                        {{-- FIX: Gibutangan ug 'truncate' ug gisulayan pagkuha ang specific field sa role --}}
                        <p class="text-[9px] font-black text-blue-500/80 uppercase tracking-wider mt-0.5 truncate">
                            {{-- Base sa imong screenshot, JSON ang role. Suwayi access ang role_name --}}
                            {{ $log->user->role->role_name ?? ($log->user->role->name ?? 'USER') }} 
                        </p>
                    @else
                        <p class="text-[12px] font-bold text-red-400 truncate">System / Deleted User</p>
                    @endif
                </div>

                {{-- COLUMN 3: ACTIVITY --}}
                <div class="col-span-5 min-w-0">
                    <div class="flex items-center gap-3">
                        @php
                            $color = 'bg-gray-500'; 
                            if(Str::contains(strtolower($log->action), 'login')) $color = 'bg-green-500';
                            if(Str::contains(strtolower($log->action), 'logout')) $color = 'bg-orange-500';
                            if(Str::contains(strtolower($log->action), 'delete') || Str::contains(strtolower($log->action), 'archive')) $color = 'bg-red-500';
                            if(Str::contains(strtolower($log->action), 'update')) $color = 'bg-blue-500';
                            if(Str::contains(strtolower($log->action), 'create')) $color = 'bg-purple-500';
                        @endphp
                        
                        <div class="shrink-0 w-1.5 h-1.5 rounded-full {{ $color }} shadow-[0_0_8px_rgba(255,255,255,0.3)]"></div>
                        
                        {{-- FIX: Added 'truncate' here so long messages don't break layout --}}
                        {{-- Or use 'break-words' if you want it to wrap to the next line --}}
                        <p class="text-[11px] font-medium text-blue-300/90 uppercase tracking-tight leading-relaxed truncate" title="{{ $log->action }}">
                            {{ $log->action }}
                        </p>
                    </div>
                </div>

                {{-- COLUMN 4: IP --}}
                <div class="col-span-2 text-right min-w-0">
                    <span class="text-[10px] font-mono text-gray-500 group-hover:text-gray-300 transition-colors truncate">
                        {{ $log->ip_address ?? '127.0.0.1' }}
                    </span>
                </div>

            </div>
        @empty
            <div class="p-10 text-center flex flex-col items-center justify-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-xs uppercase tracking-widest">No activity logs found today</p>
            </div>
        @endforelse
    </div>
    
    <div class="p-4 border-t border-white/5 bg-white/[0.01]">
        {{ $logs->links() }}
    </div>
</div>
@endsection