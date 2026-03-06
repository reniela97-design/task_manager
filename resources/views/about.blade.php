@extends('layouts.app')

@section('title', 'About System')
@section('subtitle', 'Emergence System Information & Technical Overview')

@section('content')
<div class="space-y-8">
    {{-- Main Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl hover:border-red-500/30 transition-all group">
            <p class="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">System Name</p>
            <h3 class="text-3xl font-black mt-2 tracking-tighter uppercase italic">EMERGE<span class="text-red-500">N</span>CE</h3>
        </div>
        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
            <p class="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">Build Version</p>
            <h3 class="text-3xl font-black mt-2 text-blue-500 tracking-tighter italic">v1.0.0-PRO</h3>
        </div>
        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl">
            <p class="text-[9px] font-black uppercase text-gray-500 tracking-[0.2em]">Security Tier</p>
            <h3 class="text-3xl font-black mt-2 text-green-500 tracking-tighter italic text-xs">ENCRYPTED CORE</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Section: Mission & Purpose --}}
        <div class="bg-[#05050a]/60 backdrop-blur-md border border-white/10 rounded-3xl p-8">
            <h3 class="text-xs font-black uppercase text-red-500 mb-6 tracking-widest italic border-b border-white/5 pb-2">Operational Purpose</h3>
            <p class="text-gray-400 text-xs leading-loose tracking-wide italic">
                Ang <span class="text-white font-bold uppercase">Emergence System and Solutions</span> gidesinyo isip usa ka sentralisadong hub alang sa task orchestration ug client monitoring. 
                Gisiguro niini nga ang matag <span class="text-blue-500">Task Registry</span> ug <span class="text-red-500">Urgent Priority</span> ma-monitor sa real-time.
            </p>
        </div>

        {{-- Section: System Access & Roles --}}
        <div class="bg-[#05050a]/60 backdrop-blur-md border border-white/10 rounded-3xl p-8">
            <h3 class="text-xs font-black uppercase text-blue-500 mb-6 tracking-widest italic border-b border-white/5 pb-2">Role Permissions</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-2xl group transition-all hover:bg-blue-500/5">
                    <span class="text-[10px] font-black uppercase text-white italic">Administrator</span>
                    <span class="text-[8px] font-black bg-blue-500/20 text-blue-500 px-2 py-1 rounded uppercase">Full Access</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-2xl group transition-all hover:bg-red-500/5">
                    <span class="text-[10px] font-black uppercase text-white italic">Manager</span>
                    <span class="text-[8px] font-black bg-red-500/20 text-red-500 px-2 py-1 rounded uppercase">Operational Control</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection