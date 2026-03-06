@extends('layouts.app')

@section('title', 'Types Registry')
@section('subtitle', 'Manage Task Classifications (Admin/Manager)')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div class="relative w-64">
            <input type="text" placeholder="FILTER TYPES..." class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-white w-full focus:outline-none focus:border-blue-500 tracking-widest transition-all">
        </div>
        <button @click="$dispatch('open-modal', 'create-type-modal')" 
                class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-[0_0_20px_rgba(37,99,235,0.3)]">
            + New Type
        </button>
    </div>

    <div class="bg-[#05050a]/60 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-white/[0.02]">
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-500">ID</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-500">Type Name</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-500">Status</th>
                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($types as $type)
                <tr class="hover:bg-white/[0.02] transition-colors group">
                    <td class="px-6 py-4"><span class="text-[10px] font-bold text-gray-600">{{ $type->type_id }}</span></td>
                    <td class="px-6 py-4"><span class="text-xs font-bold uppercase text-white">{{ $type->type_name }}</span></td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $type->type_inactive ? 'bg-red-500/10 text-red-500' : 'bg-green-500/10 text-green-500' }}">
                            {{ $type->type_inactive ? 'Inactive' : 'Active' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-3">
                            <button @click="$dispatch('open-modal', 'edit-type-{{ $type->type_id }}')" class="text-[10px] font-black uppercase text-yellow-500 hover:text-yellow-400">Edit</button>
                            <form action="{{ route('types.destroy', $type->type_id) }}" method="POST" onsubmit="return confirm('Delete this type?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] font-black uppercase text-red-500 hover:text-red-400">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-modal name="create-type-modal" focusable>
    <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl shadow-2xl">
        <h2 class="text-2xl font-black uppercase text-blue-500 mb-6">Create New Type</h2>
        <form method="POST" action="{{ route('types.store') }}" class="space-y-6">
            @csrf
            <div>
                <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">Type Name</label>
                <input type="text" name="type_name" placeholder="E.G. JOB ORDER" required 
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white uppercase focus:border-blue-500">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl text-[10px] font-black uppercase">Cancel</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-[10px] font-black uppercase">Save Type</button>
            </div>
        </form>
    </div>
</x-modal>

@foreach($types as $type)
<x-modal name="edit-type-{{ $type->type_id }}" focusable>
    <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl shadow-2xl">
        <h2 class="text-2xl font-black uppercase text-yellow-500 mb-6">Edit Type</h2>
        <form method="POST" action="{{ route('types.update', $type->type_id) }}" class="space-y-6">
            @csrf @method('PATCH')
            <div>
                <label class="text-[9px] font-black uppercase text-gray-500 tracking-widest block mb-2">Type Name</label>
                <input type="text" name="type_name" value="{{ $type->type_name }}" required 
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-xs font-bold text-white uppercase focus:border-yellow-500">
            </div>
            <div class="flex items-center gap-3 bg-white/5 p-4 rounded-xl border border-white/5">
                <input type="checkbox" name="type_inactive" value="1" {{ $type->type_inactive ? 'checked' : '' }} class="rounded border-gray-600 text-yellow-500 bg-gray-700">
                <label class="text-[10px] font-black uppercase text-gray-400">Mark as Inactive</label>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" @click="$dispatch('close')" class="flex-1 bg-white/5 hover:bg-white/10 text-white py-3 rounded-xl text-[10px] font-black uppercase">Cancel</button>
                <button type="submit" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-white py-3 rounded-xl text-[10px] font-black uppercase">Update Type</button>
            </div>
        </form>
    </div>
</x-modal>
@endforeach
@endsection