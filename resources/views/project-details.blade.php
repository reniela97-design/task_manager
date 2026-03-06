@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="space-y-6">
    
    {{-- BACK BUTTON --}}
    <a href="{{ route('projects.index') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors text-xs font-bold uppercase tracking-widest gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Projects
    </a>

    {{-- MAIN CARD --}}
    <div class="bg-[#0a0a12] border border-white/10 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-32 bg-blue-600/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                <div>
                    <span class="text-blue-500 font-black text-xs uppercase tracking-[0.2em] mb-2 block">
                        {{ $project->client->name ?? 'NO CLIENT' }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-white uppercase italic tracking-tighter mb-2">
                        {{ $project->name }}
                    </h1>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded text-[10px] font-bold uppercase border bg-white/5 border-white/10 text-gray-300">
                            {{ $project->status }}
                        </span>
                        <span class="text-gray-500 text-xs font-mono">ID: #{{ $project->id }}</span>
                    </div>
                </div>

                {{-- Completion Circle --}}
                <div class="flex items-center gap-4 bg-white/5 px-6 py-4 rounded-2xl border border-white/5">
                    <div class="text-right">
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Progress</div>
                        <div class="text-2xl font-black text-blue-400">{{ $project->completion_percent }}%</div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-white/10 flex items-center justify-center relative">
                        <svg class="w-full h-full -rotate-90 absolute" viewBox="0 0 36 36">
                            <path class="text-blue-600" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="{{ $project->completion_percent }}, 100" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8 border-t border-white/10">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Branch Location</label>
                    <p class="text-white font-medium">{{ $project->branch ?? 'N/A' }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Full Address</label>
                    <p class="text-white font-medium">{{ $project->address ?? 'N/A' }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date Created</label>
                    <p class="text-white font-medium">{{ $project->created_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- EXTRA SECTIONS (Placeholder for future Tasks/Logs) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 min-h-[200px]">
            <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4">Project Tasks</h3>
            <p class="text-gray-500 text-xs italic">No tasks linked to this project yet.</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 min-h-[200px]">
            <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4">Recent Activity</h3>
            <p class="text-gray-500 text-xs italic">No recent activity logs.</p>
        </div>
    </div>

</div>
@endsection