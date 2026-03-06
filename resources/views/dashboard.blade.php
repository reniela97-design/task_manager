@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'System Analytics & Task Orchestration')

@section('content')
<div class="space-y-8" x-data="{ hoverDate: null }">
    
    {{-- TOP HEADER WITH NOTIFICATION --}}
    <div class="flex justify-between items-center bg-[#0a0a12]/50 p-6 rounded-[2rem] border border-white/5 shadow-2xl backdrop-blur-md relative z-50">
        <div>
            <h1 class="text-2xl font-black text-white uppercase tracking-tighter">Operational Overview</h1>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Real-time System Intelligence</p>
        </div>

        {{-- NOTIFICATION SECTION --}}
        <div class="flex items-center gap-4" x-data="{ open: false }">
            <div class="relative">
                <button @click="open = !open" 
                        class="relative group cursor-pointer p-3 bg-white/5 hover:bg-blue-600/10 border border-white/10 rounded-2xl transition-all duration-300 z-50">
                    <svg class="h-6 w-6 text-gray-400 group-hover:text-blue-400 group-hover:scale-110 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-2.5 right-2.5 block h-3 w-3 rounded-full bg-red-500 border-2 border-[#0a0a12] shadow-[0_0_15px_rgba(239,68,68,0.8)] animate-pulse"></span>
                    @endif
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="absolute right-0 top-full mt-4 w-80 bg-[#0a0a12] border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden z-[9999]">
                    
                    <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/5">
                        <h3 class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Notifications</h3>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <a href="{{ route('notifications.readAll') }}" class="text-[8px] font-bold text-blue-500 hover:text-white uppercase tracking-wider">Mark all read</a>
                        @endif
                    </div>

                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <div class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors cursor-pointer group text-left">
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-1.5 rounded-full bg-blue-500 shadow-[0_0_8px_#3b82f6]"></div>
                                    <div>
                                        <p class="text-[11px] font-bold text-white uppercase">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-[10px] text-gray-500 mt-1 leading-tight">{{ $notification->data['message'] ?? '' }}</p>
                                        <p class="text-[8px] text-gray-600 mt-2 font-mono">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-[10px] text-gray-600 italic uppercase">No new alerts</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-left">
        <div class="bg-[#0a0a12] border border-white/10 p-6 rounded-3xl hover:border-blue-500/30 transition-all group relative overflow-hidden text-left">
            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em] relative z-10">Total Tasks</p>
            <h3 class="text-3xl font-black mt-2 tracking-tighter relative z-10 text-white">{{ $stats['total'] }}</h3>
            <div class="mt-4 h-1 w-full bg-white/5 rounded-full overflow-hidden relative z-10">
                <div class="h-full bg-blue-600 w-3/4 shadow-[0_0_10px_rgba(37,99,235,0.5)]"></div>
            </div>
        </div>

        <div class="bg-[#0a0a12] border border-white/10 p-6 rounded-3xl hover:border-blue-500/30 transition-all text-left">
            <p class="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">Pending Actions</p>
            <h3 class="text-3xl font-black mt-2 text-blue-500 tracking-tighter">{{ $stats['pending'] }}</h3>
        </div>

        {{-- URGENT PRIORITY CARD WITH LIVE LIST --}}
        <div class="bg-[#0a0a12] border border-red-500/20 p-6 rounded-3xl hover:border-red-500/40 transition-all group relative overflow-hidden text-left">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-red-500/5 blur-2xl rounded-full"></div>
            
            <div class="flex justify-between items-center mb-4 relative z-10">
                <p class="text-[9px] font-black uppercase text-red-500 tracking-[0.2em]">Urgent Priority</p>
                
            </div>

            <div class="space-y-2 relative z-10">
                @php
                    $urgentList = collect($calendarTasks)->flatten()->filter(function($task) {
                        $p = strtolower($task->priority);
                        return str_contains($p, 'urg') || $p == 'high';
                    })->take(2);
                @endphp

                @forelse($urgentList as $ut)
                    <div class="flex items-center gap-2 bg-red-500/5 border border-red-500/10 p-2 rounded-xl">
                        <div class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></div>
                        <p class="text-[9px] font-bold text-gray-300 uppercase truncate">{{ Str::limit($ut->title, 20) }}</p>
                    </div>
                @empty
                    <p class="text-[9px] text-gray-600 italic uppercase">Clear: No urgent tasks</p>
                @endforelse
            </div>
        </div>

        <div class="bg-[#0a0a12] border border-white/10 p-6 rounded-3xl hover:border-green-500/30 transition-all text-left">
            <p class="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">System Status</p>
            <h3 class="text-3xl font-black mt-2 text-green-500 tracking-tighter uppercase italic">Active</h3>
        </div>
    </div>

    {{-- LOWER SECTION (TIMELINE & CALENDAR) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
        <div class="lg:col-span-1 space-y-6 text-left">
            <div class="bg-[#05050a]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl text-left">
                <h3 class="text-xs font-black uppercase text-blue-500 mb-6 tracking-widest italic text-left">Timeline Feed</h3>
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($calendarTasks as $day => $tasks)
                        @foreach($tasks as $task)
                            @php
                                $p = strtolower($task->priority);
                                if(str_contains($p, 'urg') || $p == 'high') { $cBase = 'red'; $txt = 'text-red-400'; } 
                                elseif(str_contains($p, 'low') || $p == 'easy') { $cBase = 'green'; $txt = 'text-green-400'; } 
                                else { $cBase = 'blue'; $txt = 'text-blue-400'; }
                            @endphp
                            <div class="flex items-center justify-between p-4 border rounded-2xl group transition-all bg-{{ $cBase }}-500/5 border-{{ $cBase }}-500/10 hover:bg-{{ $cBase }}-500/10 hover:border-{{ $cBase }}-500/30">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full shadow-[0_0_8px_rgba(255,255,255,0.2)] bg-{{ $cBase }}-500"></div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-bold uppercase tracking-tight text-white text-left">{{ Str::limit($task->title, 22) }}</p>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase text-left">{{ $task->task_date ? $task->task_date->format('M d, Y') : 'No Schedule' }}</p>
                                    </div>
                                </div>
                                <span class="text-[8px] font-black bg-white/5 px-2 py-1 rounded-md border border-white/5 uppercase {{ $txt }}">{{ $task->priority }}</span>
                            </div>
                        @endforeach
                    @empty
                        <p class="text-[10px] text-gray-600 text-center py-10 italic">No operational data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-[#05050a]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8 h-full relative shadow-2xl overflow-hidden text-left">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/10 blur-[100px] rounded-full text-left"></div>
                <div class="flex justify-between items-center mb-10 relative z-10 text-left">
                    <div class="text-left">
                        <h3 class="text-xs font-black uppercase text-blue-500 tracking-widest italic text-left">Operational Calendar</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter text-left">{{ $currentDate->format('F Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-3 mb-4 text-center relative z-10 text-white">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">{{ $dayName }}</span>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 gap-3 relative z-10 text-white">
                    @for($i = 0; $i < $emptySlots; $i++) <div class="aspect-square rounded-xl border border-transparent"></div> @endfor
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $hasTask = isset($calendarTasks[$day]);
                            $isToday = $day == now()->day && $currentDate->isCurrentMonth();
                            $dailyTasks = $calendarTasks[$day] ?? [];
                        @endphp
                        <div class="relative" @mouseenter="hoverDate = {{ $day }}" @mouseleave="hoverDate = null">
                            <div class="aspect-square relative flex flex-col items-center justify-center rounded-2xl border transition-all duration-300 
                                {{ $isToday ? 'border-blue-500 bg-blue-500/10' : 'border-white/5 bg-white/[0.03]' }}">
                                <span class="text-xs font-black {{ $isToday ? 'text-blue-400' : 'text-gray-500 group-hover:text-white' }}">{{ $day }}</span>
                                
                                {{-- UPDATED CALENDAR DAY LOOP --}}
                                @if($hasTask && count($dailyTasks) > 0)
                                    <div class="flex gap-1 mt-1.5">
                                        @foreach($dailyTasks as $dt)
                                            {{-- Siguroon nato nga ang wala pa na-delete/human ra ang naay dots --}}
                                            @if($loop->iteration <= 3)
                                                @php 
                                                    $tp = strtolower($dt->priority); 
                                                    $dot = str_contains($tp, 'urg') || $tp == 'high' ? 'bg-red-500' : (str_contains($tp, 'low') ? 'bg-green-500' : 'bg-blue-500'); 
                                                @endphp
                                                <div class="w-1.5 h-1.5 rounded-full {{ $dot }}"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div x-show="hoverDate === {{ $day }}" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-cloak
                                 class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-52 bg-[#0a0a12] border border-white/10 p-4 rounded-2xl z-[100] shadow-2xl pointer-events-none text-left">
                                <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest mb-2 border-b border-white/5 pb-1 italic">Day {{ $day }} Tasks</p>
                                <div class="space-y-1.5 text-left">
                                    @foreach($dailyTasks as $dt)
                                        <div class="flex items-center gap-2">
                                            <div class="w-1 h-1 rounded-full {{ str_contains(strtolower($dt->priority), 'urg') ? 'bg-red-500' : 'bg-blue-500' }}"></div>
                                            <p class="text-[9px] text-gray-300 font-bold uppercase truncate">{{ Str::limit($dt->title, 20) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-10 pt-8 border-t border-white/5 flex items-center justify-center gap-8 relative z-10">
                    <div class="flex items-center gap-2.5 text-left">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_8px_#ef4444]"></div>
                        <span class="text-[10px] font-black uppercase text-gray-500 tracking-widest text-left">Urgent</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-left">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-[0_0_8px_#3b82f6]"></div>
                        <span class="text-[10px] font-black uppercase text-gray-500 tracking-widest text-left">Normal</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-left">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-[0_0_8px_#22c55e]"></div>
                        <span class="text-[10px] font-black uppercase text-gray-500 tracking-widest text-left">Low</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.3); border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>
@endsection       