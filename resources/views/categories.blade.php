@extends('layouts.app')

@section('title', 'Categories Registry')
@section('subtitle', 'Manage Task Classifications')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between gap-4 items-center">
        <div class="relative w-full md:w-96">
            <input type="text" placeholder="FILTER CATEGORIES..." class="bg-white/5 border border-white/10 rounded-xl px-11 py-3 text-[11px] font-bold text-white w-full focus:outline-none focus:border-blue-500 transition-all tracking-widest">
            <svg class="w-4 h-4 absolute left-4 top-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        
        <button @click="$dispatch('open-modal', 'create-category-modal')" 
                class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-[0_0_20px_rgba(37,99,235,0.3)]">
            + New Category
        </button>
    </div>

    <div class="bg-[#05050a]/60 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/[0.02]">
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">ID</th>
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">Name</th>
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">Status</th>
                    <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($categories as $cat)
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-5"><span class="text-[10px] font-bold text-gray-600">{{ $cat->category_id }}</span></td>
                    
                    <td class="px-6 py-5">
                        {{-- Gikuha ang 'uppercase' diri --}}
                        <span class="text-xs font-bold text-white">{{ $cat->category_name }}</span>
                    </td>
                    
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $cat->category_inactive ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                            {{ $cat->category_inactive ? 'Inactive' : 'Active' }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <button @click="$dispatch('open-modal', 'view-category-{{ $cat->category_id }}')" 
                                    class="text-[10px] font-black uppercase text-blue-400 hover:text-blue-300 transition-colors">
                                View
                            </button>

                            <button @click="$dispatch('open-modal', 'edit-category-{{ $cat->category_id }}')" 
                                    class="text-[10px] font-black uppercase text-yellow-500 hover:text-yellow-400 transition-colors">
                                Edit
                            </button>

                            <form action="{{ route('categories.destroy', $cat->category_id) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] font-black uppercase text-red-500 hover:text-red-400 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODALS SECTION --}}
@foreach($categories as $cat)
    {{-- VIEW MODAL --}}
    <x-modal name="view-category-{{ $cat->category_id }}" focusable>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl">
            <h2 class="text-2xl font-black uppercase text-blue-500 mb-6 tracking-tighter italic">Category Details</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Category Name</label>
                    {{-- FIXED: Gikuha ang 'uppercase' --}}
                    <p class="text-white font-bold text-lg leading-tight">{{ $cat->category_name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Created By</label>
                        <p class="text-gray-300 font-mono text-xs">{{ $cat->category_user_id ?? 'System' }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Date Logged</label>
                        <p class="text-gray-300 font-mono text-xs">{{ $cat->category_log_datetime }}</p>
                    </div>
                </div>
                <div class="pt-4 mt-4 border-t border-white/10 flex justify-end">
                    <button @click="$dispatch('close')" class="bg-white/5 hover:bg-white/10 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Close</button>
                </div>
            </div>
        </div>
    </x-modal>

    {{-- EDIT MODAL --}}
    <x-modal name="edit-category-{{ $cat->category_id }}" focusable>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl">
            <h2 class="text-2xl font-black uppercase text-yellow-500 mb-6 tracking-tighter italic">Edit Category</h2>
            
            <form method="POST" action="{{ route('categories.update', $cat->category_id) }}" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div>
                    <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">Category Name</label>
                    {{-- FIXED: Gikuha ang 'uppercase' sa input class --}}
                    <input type="text" name="category_name" value="{{ $cat->category_name }}" required 
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-yellow-500 transition-all tracking-widest">
                </div>

                <div class="flex items-center gap-3 bg-white/5 p-4 rounded-xl border border-white/5">
                    <input type="checkbox" name="category_inactive" value="1" {{ $cat->category_inactive ? 'checked' : '' }} 
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

{{-- CREATE MODAL --}}
<x-modal name="create-category-modal" focusable>
    <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl shadow-2xl">
        <h2 class="text-2xl font-black uppercase tracking-tighter italic text-blue-500 mb-6">Create New Category</h2>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf
            <div>
                <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">Category Designation</label>
                {{-- FIXED: Gikuha ang 'uppercase' sa input class --}}
                <input type="text" name="category_name" placeholder="E.g. API Integration" required 
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white focus:outline-none focus:border-blue-500 transition-all tracking-widest">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl text-[10px] font-black uppercase">Cancel</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-900/20">Confirm Category</button>
            </div>
        </form>
    </div>
</x-modal>
@endsection