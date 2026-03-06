@extends('layouts.app')

@section('title', 'User Accounts')

@section('content')

{{-- Display Validation Errors --}}
@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
        <ul class="text-[10px] font-black uppercase tracking-widest">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Alpine.js Search Logic --}}
<div x-data="{ search: '' }" class="space-y-10">
    {{-- TOP BAR --}}
    <div class="flex justify-between items-center">
        <div class="relative">
            <input 
                x-model="search"
                type="text" 
                placeholder="SEARCH SYSTEM ACCOUNTS..." 
                class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold tracking-widest focus:outline-none focus:border-blue-500 w-64 transition-all uppercase text-white placeholder-gray-600"
            >
        </div>
        <button type="button" @click="$dispatch('open-modal', 'add-user-modal')" class="bg-blue-600 hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
            + Add New User
        </button>
    </div>

    {{-- 1. ADMINISTRATORS SECTION --}}
    <section>
        <div class="flex items-center gap-2 mb-4">
            <span class="p-1 rounded bg-blue-500/10 text-blue-500 text-[10px]">🛡️</span>
            <h2 class="text-blue-500 font-black text-[10px] uppercase tracking-[0.2em]">Administrators</h2>
        </div>
        @include('partials.user-table', ['users' => $admins])
    </section>

    {{-- 2. MANAGERS SECTION --}}
    <section>
        <div class="flex items-center gap-2 mb-4">
            <span class="p-1 rounded bg-green-500/10 text-green-500 text-[10px]">💼</span>
            <h2 class="text-green-500 font-black text-[10px] uppercase tracking-[0.2em]">Managers</h2>
        </div>
        @include('partials.user-table', ['users' => $managers])
    </section>

    {{-- 3. USERS SECTION --}}
    <section>
        <div class="flex items-center gap-2 mb-4">
            <span class="p-1 rounded bg-purple-500/10 text-purple-500 text-[10px]">👥</span>
            <h2 class="text-purple-400 font-black text-[10px] uppercase tracking-[0.2em]">Users</h2>
        </div>
        @include('partials.user-table', ['users' => $regularUsers])
    </section>
</div>

{{-- MODAL: REGISTER NEW USER --}}
<x-modal name="add-user-modal" focusable x-cloak>
    <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl">
        <h2 class="text-2xl font-black uppercase italic text-blue-500 mb-6 tracking-tighter">Register Account</h2>
        <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
            @csrf
            {{-- Gikuha ang uppercase sa inputs --}}
            <input type="text" name="name" placeholder="Full Name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white outline-none focus:border-blue-500 transition-all">
            <input type="email" name="email" placeholder="Work Email" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white outline-none focus:border-blue-500 transition-all">
            
            <div class="grid grid-cols-2 gap-4">
                <input type="password" name="password" placeholder="PASSWORD" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white outline-none focus:border-blue-500">
                <input type="password" name="password_confirmation" placeholder="CONFIRM" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white outline-none focus:border-blue-500">
            </div>
            <select name="role_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white uppercase outline-none focus:border-blue-500">
                <option value="" disabled selected>SELECT ROLE</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" class="bg-[#020408]">{{ $role->role_name }}</option>
                @endforeach
            </select>
            <div class="flex gap-3 pt-6">
                <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 text-white py-3 rounded-xl text-[10px] font-black uppercase">Cancel</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl text-[10px] font-black uppercase">Create Account</button>
            </div>
        </form>
    </div>
</x-modal>

{{-- MODALS LOOP: VIEW & EDIT --}}
@foreach($allUsers as $user)
    {{-- VIEW MODAL --}}
    <x-modal name="view-user-{{ $user->id }}" focusable x-cloak>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl text-white">
            <div class="flex items-center gap-6 mb-8 border-b border-white/5 pb-6">
                <div class="w-20 h-20 rounded-2xl bg-blue-600 flex items-center justify-center text-2xl font-black text-white italic">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    {{-- FIXED: Gikuha ang uppercase diri --}}
                    <h2 class="text-2xl font-black italic leading-none mb-2 text-white">{{ $user->name }}</h2>
                    <span class="text-[9px] font-black uppercase text-gray-500 tracking-widest italic">Member Profile</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/[0.02] p-4 rounded-2xl border border-white/5">
                    <p class="text-[8px] font-black text-blue-500 uppercase mb-1 tracking-widest">Status</p>
                    <p class="text-xs font-bold">{{ $user->trashed() ? 'Archived' : 'Active' }}</p>
                </div>
                <div class="bg-white/[0.02] p-4 rounded-2xl border border-white/5">
                    <p class="text-[8px] font-black text-blue-500 uppercase mb-1 tracking-widest">Role</p>
                    <p class="text-xs font-bold">{{ $user->role->role_name ?? 'Unassigned' }}</p>
                </div>
                <div class="col-span-2 bg-white/[0.02] p-4 rounded-2xl border border-white/5">
                    <p class="text-[8px] font-black text-blue-500 uppercase mb-1 tracking-widest">Email</p>
                    <p class="text-xs font-bold">{{ $user->email }}</p>
                </div>
            </div>
            <button @click="$dispatch('close')" class="w-full mt-8 bg-white/5 hover:bg-white/10 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Close</button>
        </div>
    </x-modal>

    {{-- EDIT MODAL --}}
    <x-modal name="edit-user-{{ $user->id }}" focusable x-cloak>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl">
            <h2 class="text-2xl font-black uppercase italic text-blue-500 mb-6 tracking-tighter">Edit Profile</h2>
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-4">
                @csrf @method('PATCH')
                
                {{-- Full Name Input --}}
                <div class="space-y-1 text-left">
                    <label class="text-[8px] font-black text-gray-500 uppercase ml-2">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white outline-none focus:border-blue-500 transition-all">
                </div>

                {{-- Email Input --}}
                <div class="space-y-1 text-left">
                    <label class="text-[8px] font-black text-gray-500 uppercase ml-2">Email Address</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white outline-none focus:border-blue-500 transition-all">
                </div>

                {{-- Role Selection --}}
                <div class="space-y-1 text-left">
                    <label class="text-[8px] font-black text-gray-500 uppercase ml-2">Assign Role</label>
                    <select name="role_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white uppercase outline-none focus:border-blue-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }} class="bg-[#020408]">
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-6">
                    <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 text-white py-3 rounded-xl text-[10px] font-black uppercase">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl text-[10px] font-black uppercase">Save Changes</button>
                </div>
            </form>
        </div>
    </x-modal>
@endforeach

@endsection