@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Define and restrict staff permissions.</p>
        
        <button @click="$dispatch('open-modal', 'create-role-modal')" 
                class="bg-blue-600 hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] active:scale-95 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 ease-in-out">
            + New Role
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($roles as $role)
        <div class="bg-white/5 border border-white/10 p-6 rounded-3xl hover:border-blue-500/50 hover:bg-white/[0.07] transition-all duration-300 group">
            <h3 class="text-lg font-black text-blue-500 uppercase tracking-tighter">{{ $role->role_name }}</h3>
            
            <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-wide">
                {{ $role->role_inactive ? 'Inactive' : 'Active Access Level' }}
            </p>
            
            <div class="mt-4 pt-4 border-t border-white/5 flex gap-3">
                
                {{-- LOGIC: Check if the role is one of the Protected System Roles --}}
                @if(in_array($role->role_name, ['Administrator', 'Manager', 'User']))
                    <div class="flex items-center gap-2 opacity-50 cursor-not-allowed select-none" title="This is a core system role and cannot be deleted.">
                        <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span class="text-[9px] text-gray-500 font-black uppercase tracking-widest">System Core</span>
                    </div>
                @else
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[9px] text-red-500 font-black hover:text-red-400 transition-colors uppercase tracking-widest">
                            Delete Role
                        </button>
                    </form>
                @endif

            </div>
        </div>
        @endforeach
    </div>
</div>

<x-modal name="create-role-modal" focusable>
    <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl shadow-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-black uppercase tracking-tighter italic text-blue-500">Create New Role</h2>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Add a new access level to the system</p>
        </div>

        <form method="POST" action="{{ route('roles.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">Role Designation</label>
                <input type="text" name="role_name" required placeholder="E.G. PROJECT SUPERVISOR" 
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-blue-500 transition-all uppercase tracking-widest">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl text-[10px] font-black uppercase transition-all">Cancel</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-900/20 transition-all">
                    Confirm Role
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endsection