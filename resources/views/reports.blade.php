@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-1">
        <h2 class="text-2xl font-black text-blue-500 uppercase tracking-tighter">System Reports</h2>
        <p class="text-sm text-gray-400 font-medium">Review team productivity and identify aging tasks.</p>
    </div>

    {{-- PRODUCTIVITY REPORT --}}
    <div class="bg-white/5 border border-white/10 rounded-3xl p-6 overflow-hidden backdrop-blur-sm">
        <div class="mb-6 pb-4 border-b border-white/5">
            <h3 class="text-lg font-black text-white tracking-tight">Productivity Report</h3>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Completed Tasks & Durations</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[9px] uppercase tracking-widest text-gray-500">
                        <th class="pb-4 font-black">Task Title</th>
                        <th class="pb-4 font-black">Start Time</th>
                        <th class="pb-4 font-black">End Time</th>
                        <th class="pb-4 font-black">Time Tracking</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5">
                    @forelse($completed_tasks ?? [] as $task)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 pr-4 align-top">
                            <div class="font-bold text-white text-sm">{{ $task->title }}</div>
                            @if($task->is_overdue ?? false)
                                <span class="text-[9px] text-red-500 font-black uppercase tracking-widest mt-1 block">Overdue</span>
                            @endif
                        </td>
                        
                        {{-- FIX: Gidugang ang setTimezone('Asia/Manila') --}}
                        <td class="py-5 align-top">
                            <div class="text-gray-300 font-medium">
                                {{ $task->started_at ? \Carbon\Carbon::parse($task->started_at)->setTimezone('Asia/Manila')->format('M d, Y') : '-' }}
                            </div>
                            <div class="text-[10px] text-gray-500 font-mono mt-0.5">
                                {{ $task->started_at ? \Carbon\Carbon::parse($task->started_at)->setTimezone('Asia/Manila')->format('h:i:s A') : '--:--' }}
                            </div>
                        </td>

                        {{-- FIX: Gidugang ang setTimezone('Asia/Manila') --}}
                        <td class="py-5 align-top">
                            <div class="text-gray-300 font-medium">
                                {{ $task->finished_at ? \Carbon\Carbon::parse($task->finished_at)->setTimezone('Asia/Manila')->format('M d, Y') : '-' }}
                            </div>
                            <div class="text-[10px] text-gray-500 font-mono mt-0.5">
                                {{ $task->finished_at ? \Carbon\Carbon::parse($task->finished_at)->setTimezone('Asia/Manila')->format('h:i:s A') : '--:--' }}
                            </div>
                        </td>

                        <td class="py-5 align-top">
                            <div class="flex flex-col items-start gap-2">
                                <span class="bg-gray-700/30 text-gray-300 px-2 py-1.5 rounded-md text-[10px] font-mono border border-white/5">
                                    Work: {{ $task->duration_formatted ?? '00h 00m 00s' }}
                                </span>
                                @if($task->is_overdue ?? false)
                                    <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-1.5 rounded-md text-[9px] font-black uppercase tracking-wide">
                                        Late by {{ $task->late_duration ?? '00h 00m 00s' }}
                                    </span>
                                @else
                                    <span class="bg-green-500/10 text-green-400 border border-green-500/20 px-2 py-1.5 rounded-md text-[9px] font-black uppercase tracking-wide">
                                        On Time
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-500 text-xs font-bold uppercase tracking-widest">
                            No completed tasks found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- AGING REPORT --}}
    <div class="bg-white/5 border border-white/10 rounded-3xl p-6 overflow-hidden backdrop-blur-sm mt-6">
        <div class="mb-6 pb-4 border-b border-white/5">
            <h3 class="text-lg font-black text-white tracking-tight">Aging Report</h3>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Pending Tasks Timeline</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[9px] uppercase tracking-widest text-gray-500">
                        <th class="pb-4 font-black">Task Title</th>
                        <th class="pb-4 font-black">Date Created</th>
                        <th class="pb-4 font-black">Status</th>
                        <th class="pb-4 font-black">Aging</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5">
                    @forelse($pending_tasks ?? [] as $task)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="py-5 pr-4 font-bold text-white">{{ $task->title }}</td>
                        
                        {{-- FIX: Gidugang ang setTimezone('Asia/Manila') --}}
                        <td class="py-5 align-top">
                            <div class="text-gray-300 font-medium">
                                {{ \Carbon\Carbon::parse($task->created_at)->setTimezone('Asia/Manila')->format('M d, Y') }}
                            </div>
                            <div class="text-[9px] text-gray-500 font-bold uppercase tracking-wide mt-1">
                                {{ \Carbon\Carbon::parse($task->created_at)->setTimezone('Asia/Manila')->format('h:i A') }}
                            </div>
                        </td>
                        
                        <td class="py-5">
                            <span class="bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                                {{ $task->status ?? 'In Progress' }}
                            </span>
                        </td>
                        <td class="py-5">
                            <span class="text-red-400 font-mono font-bold text-sm">
                                {{ number_format(\Carbon\Carbon::parse($task->created_at)->diffInDays(now(), true), 1) }} days
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-500 text-xs font-bold uppercase tracking-widest">
                            No pending tasks found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection