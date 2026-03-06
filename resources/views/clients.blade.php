@extends('layouts.app')

@section('title', 'Clients Registry')
@section('subtitle', 'Manage corporate partnerships and directories')

@section('content')
<div x-data="{ 
    showModal: false, 
    editMode: false, 
    search: '',
    formData: { id: null, name: '', contact_number: '', branch: '', address: '' },
    
    openAddModal() {
        this.editMode = false;
        this.formData = { id: null, name: '', contact_number: '', branch: '', address: '' };
        this.showModal = true;
    },
    
    openEditModal(client) {
        this.editMode = true;
        this.formData = { ...client }; // Clone client data
        this.showModal = true;
    }
}" class="space-y-6">

    {{-- HEADER & ACTIONS --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-500 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input x-model="search" type="text" placeholder="SEARCH CLIENTS..." 
                   class="bg-[#0a0a12] border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold tracking-widest text-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 w-full md:w-80 transition-all placeholder-gray-600">
        </div>

        <button @click="openAddModal()" 
                class="bg-blue-600 hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
            <span>+ Add New Client</span>
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-[#05050a]/60 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/[0.02]">
                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">Client Info</th>
                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">Contact</th>
                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">Location</th>
                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-gray-500 text-right">Directory Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($clients as $client)
                    <tr x-show="search === '' || '{{ strtolower($client->name) }}'.includes(search.toLowerCase())" 
                        class="hover:bg-white/[0.02] transition-colors group">
                        
                        {{-- CLIENT NAME --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-900 to-slate-900 flex items-center justify-center border border-white/10 text-blue-400 font-bold text-xs">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold uppercase tracking-widest text-white">{{ $client->name }}</span>
                            </div>
                        </td>

                        {{-- CONTACT --}}
                        <td class="px-6 py-5">
                            <span class="text-[11px] font-mono text-gray-400">{{ $client->contact_number }}</span>
                        </td>

                        {{-- LOCATION (Branch & Address) --}}
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase text-blue-500">{{ $client->branch }}</span>
                                <span class="text-[10px] text-gray-500 truncate max-w-[200px]" title="{{ $client->address }}">{{ $client->address }}</span>
                            </div>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-5">
                            <div class="flex justify-end items-center gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                {{-- EDIT BUTTON --}}
                                <button @click="openEditModal({{ $client }})" 
                                        class="p-1.5 rounded-lg hover:bg-white/10 text-blue-400 transition-colors" title="Edit Client">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>

                                {{-- DELETE FORM --}}
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to remove {{ $client->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-500/20 text-red-500 hover:text-red-400 transition-colors" title="Delete Client">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center opacity-30">
                                <svg class="w-12 h-12 mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-500">No Clients Found</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="showModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>

        {{-- MODAL CONTENT --}}
        <div class="relative bg-[#0a0a12] border border-white/10 rounded-2xl w-full max-w-lg overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.5)]"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02]">
                <h3 class="text-sm font-black uppercase tracking-widest text-white" x-text="editMode ? 'Update Client Details' : 'Register New Client'"></h3>
            </div>

            <form method="POST" :action="editMode ? '/clients/' + formData.id : '{{ route('clients.store') }}'" class="p-8 space-y-5">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                {{-- Client Name --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Client Name</label>
                    <input type="text" name="name" x-model="formData.name" required
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-600" 
                           placeholder="E.g. Nexus Corp">
                </div>

                {{-- Contact Number --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Contact Number</label>
                    <input type="text" name="contact_number" x-model="formData.contact_number" required
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-600" 
                           placeholder="E.g. +1 (555) 123-4567">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Branch --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Branch Location</label>
                        <input type="text" name="branch" x-model="formData.branch" required
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-600" 
                               placeholder="E.g. North District">
                    </div>
                    
                    {{-- Address --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Physical Address</label>
                        <input type="text" name="address" x-model="formData.address" required
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-600" 
                               placeholder="E.g. 123 Innovation Blvd">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="showModal = false" 
                            class="px-5 py-2.5 rounded-lg border border-white/10 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-500 px-6 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest text-white shadow-[0_0_20px_rgba(37,99,235,0.3)] hover:shadow-[0_0_25px_rgba(37,99,235,0.5)] transition-all">
                        <span x-text="editMode ? 'Save Changes' : 'Register Client'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection