@extends('layouts.app')

@section('title', 'Systems Registry')
@section('subtitle', 'Manage Operational Systems (Admin/Manager Only)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between gap-4 items-center">
        <div class="relative w-full md:w-96">
            <input type="text" placeholder="FILTER SYSTEMS..." class="bg-white/5 border border-white/10 rounded-xl px-11 py-3 text-[11px] font-bold text-white w-full focus:outline-none focus:border-blue-500 transition-all tracking-widest">
            <svg class="w-4 h-4 absolute left-4 top-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        
        <button @click="$dispatch('open-modal', 'create-system-modal')" 
                class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-[0_0_20px_rgba(37,99,235,0.3)] hover:shadow-[0_0_30px_rgba(37,99,235,0.5)]">
            + New System
        </button>
    </div>

    <div class="bg-[#05050a]/60 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/[0.02]">
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">ID</th>
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">System Name</th>
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">Status</th>
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($systems as $system)
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-5"><span class="text-[10px] font-bold text-gray-600">{{ $system->system_id }}</span></td>
                    
                    <td class="px-6 py-5">
                        <span class="text-xs font-bold uppercase text-white">{{ $system->system_name }}</span>
                    </td>
                    
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $system->system_inactive ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                            {{ $system->system_inactive ? 'Inactive' : 'Active' }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <button @click="$dispatch('open-modal', 'view-system-{{ $system->system_id }}')" 
                                    class="text-[10px] font-black uppercase text-blue-400 hover:text-blue-300 transition-colors">
                                View
                            </button>

                            <button @click="$dispatch('open-modal', 'edit-system-{{ $system->system_id }}')" 
                                    class="text-[10px] font-black uppercase text-yellow-500 hover:text-yellow-400 transition-colors">
                                Edit
                            </button>

                            <form action="{{ route('systems.destroy', $system->system_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this system?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] font-black uppercase text-red-500 hover:text-red-400 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($systems->isEmpty())
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 text-xs uppercase tracking-widest font-bold">
                        No systems found. Click "+ New System" to add one.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@foreach($systems as $system)
    <x-modal name="view-system-{{ $system->system_id }}" focusable>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl">
            <h2 class="text-2xl font-black uppercase text-blue-500 mb-6">System Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase">System Name</label>
                    <p class="text-white font-bold text-lg uppercase">{{ $system->system_name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-black text-gray-500 uppercase">Created By (User ID)</label>
                        <p class="text-gray-300 font-mono text-xs">{{ $system->system_user_id ?? 'System' }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-500 uppercase">Date Logged</label>
                        <p class="text-gray-300 font-mono text-xs">{{ $system->system_log_datetime }}</p>
                    </div>
                </div>
                <div class="pt-4 mt-4 border-t border-white/10 flex justify-end">
                    <button @click="$dispatch('close')" class="bg-white/5 hover:bg-white/10 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase">Close</button>
                </div>
            </div>
        </div>
    </x-modal>

    <x-modal name="edit-system-{{ $system->system_id }}" focusable>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl">
            <h2 class="text-2xl font-black uppercase text-yellow-500 mb-6">Edit System</h2>
            
            <form method="POST" action="{{ route('systems.update', $system->system_id) }}" class="space-y-6">
                @csrf
                @method('PATCH') <div>
                    <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">System Name</label>
                    <input type="text" name="system_name" value="{{ $system->system_name }}" required 
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-yellow-500 transition-all uppercase tracking-widest">
                </div>

                <div class="flex items-center gap-3 bg-white/5 p-4 rounded-xl border border-white/5">
                    <input type="checkbox" name="system_inactive" value="1" {{ $system->system_inactive ? 'checked' : '' }} 
                           class="w-4 h-4 rounded border-gray-600 text-yellow-500 focus:ring-yellow-500 bg-gray-700">
                    <label class="text-[10px] font-black uppercase text-gray-400">Mark as Inactive / Archive</label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl text-[10px] font-black uppercase">Cancel</button>
                    <button type="submit" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-white py-3 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-yellow-900/20">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
@endforeach

<x-modal name="create-system-modal" focusable>
    <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>

        <div class="mb-8 relative">
            <h2 class="text-2xl font-black uppercase tracking-tighter italic text-blue-500">Create New System</h2>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Define a new operational system (e.g., Inventory)</p>
        </div>

        <form method="POST" action="{{ route('systems.store') }}" class="space-y-6 relative">
            @csrf
            <div>
                <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">System Name</label>
                <input type="text" name="system_name" placeholder="E.G. INVENTORY SYSTEM" required 
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-blue-500 focus:bg-white/10 transition-all uppercase tracking-widest">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" @click="$dispatch('close')" 
                        class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl text-[10px] font-black uppercase transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-900/20 transition-all">
                    Confirm System
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endsection