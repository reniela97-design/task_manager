@extends('layouts.app')

@section('title', 'Active Projects')

@section('content')
<div x-data="{ 
    showModal: false, 
    editMode: false, 
    formData: {
        id: null,
        name: '',
        client_id: '',
        branch: '',
        address: '',
        priority: 'Normal',
        start_date: ''
    },
    resetForm() {
        this.formData = { 
            id: null, 
            name: '', 
            client_id: '', 
            branch: '', 
            address: '', 
            priority: 'Normal', 
            start_date: '' 
        };
        this.editMode = false;
    },
    openAddModal() {
        this.resetForm();
        this.showModal = true;
    },
    openEditModal(project) {
        this.formData = JSON.parse(JSON.stringify(project));
        this.editMode = true;
        this.showModal = true;
    }
}" class="space-y-6 relative">

    {{-- HEADER & ADD BUTTON --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-white text-lg font-bold uppercase tracking-widest">Project Directory</h2>
        <button @click="openAddModal()" 
                class="bg-blue-600 hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
            + Add New Project
        </button>
    </div>

    {{-- GRID LAYOUT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-blue-500/50 transition-all group relative overflow-hidden flex flex-col justify-between min-h-[200px]">
            
            <div class="absolute top-4 right-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-40">
                <button @click="openEditModal({{ $project }})" 
                        class="bg-[#0a0a12] border border-white/20 text-blue-400 hover:bg-blue-600 hover:text-white hover:border-blue-500 p-2 rounded-lg transition-all shadow-lg" title="Edit">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </button>
                
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete project?');">
                    @csrf @method('DELETE')
                    <button type="submit" 
                            class="bg-[#0a0a12] border border-white/20 text-red-400 hover:bg-red-600 hover:text-white hover:border-red-500 p-2 rounded-lg transition-all shadow-lg" title="Delete">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>

            <div class="relative z-10">
                <div class="mb-4">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest">
                            {{ $project->client->name ?? 'Unassigned' }}
                        </p>
                        {{-- Displaying Priority Badge --}}
                        <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase border 
                            @if($project->priority == 'Urgent') bg-red-500/10 text-red-400 border-red-500/20 
                            @elseif($project->priority == 'High') bg-orange-500/10 text-orange-400 border-orange-500/20
                            @else bg-blue-500/10 text-blue-400 border-blue-500/20 @endif">
                            {{ $project->priority ?? 'Normal' }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold uppercase italic tracking-tighter text-white truncate pr-8">
                        {{ $project->name }}
                    </h3>
                </div>
                
                <p class="text-gray-500 text-xs mb-6 line-clamp-2 h-[32px]">
                    {{ $project->address ?? 'No location details.' }} 
                    @if($project->branch) <span class="text-gray-600">({{ $project->branch }})</span> @endif
                </p>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-tighter text-gray-400">
                        <span>Status: <b class="text-white">{{ $project->status }}</b></span>
                        <span class="text-blue-500">{{ $project->completion_percent }}%</span>
                    </div>
                    <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden border border-white/5">
                        <div class="h-full bg-gradient-to-r from-blue-600 to-cyan-400 shadow-[0_0_10px_rgba(37,99,235,0.5)] transition-all duration-500" 
                             style="width: {{ $project->completion_percent }}%"></div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/5 flex justify-between items-center relative z-30 mt-auto">
                <div class="flex flex-col">
                    <span class="text-[8px] text-gray-500 uppercase font-bold">Started</span>
                    <span class="text-[10px] text-gray-300 font-bold">{{ $project->start_date ?? 'Not Set' }}</span>
                </div>
                
               <a href="{{ route('projects.show', $project->id) }}" class="text-[9px] font-black uppercase text-gray-500 hover:text-white transition-colors flex items-center gap-1 cursor-pointer">
                    View Details <span>→</span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-500 uppercase text-xs font-bold tracking-widest">
            No projects found.
        </div>
        @endforelse
    </div>

    {{-- MODAL (Add/Edit Project) --}}
    <div x-show="showModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>

        <div class="relative bg-[#0a0a12] border border-white/10 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02]">
                <h3 class="text-sm font-black uppercase tracking-widest text-white" x-text="editMode ? 'Update Project' : 'Add New Project'"></h3>
            </div>

            <form method="POST" :action="editMode ? `{{ url('projects') }}/${formData.id}` : '{{ route('projects.store') }}'" class="p-8 space-y-5">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Project Name</label>
                    <input type="text" name="name" x-model="formData.name" required
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 outline-none transition-all placeholder-gray-600" 
                           placeholder="E.g. API Integration">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Client</label>
                        <select name="client_id" x-model="formData.client_id" required
                                class="w-full bg-[#0a0a12] border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 outline-none appearance-none">
                            <option value="" disabled selected>Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NEW: PRIORITY FIELD --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Priority Level</label>
                        <select name="priority" x-model="formData.priority" required
                                class="w-full bg-[#0a0a12] border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 outline-none appearance-none">
                            <option value="Normal">Normal</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Branch</label>
                        <input type="text" name="branch" x-model="formData.branch"
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 outline-none" 
                               placeholder="Branch Name">
                    </div>
                    
                    {{-- NEW: START DATE FIELD --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Start Date</label>
                        <input type="date" name="start_date" x-model="formData.start_date"
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-[12px] text-white focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Address / Loc.</label>
                    <input type="text" name="address" x-model="formData.address"
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white focus:border-blue-500 outline-none" 
                           placeholder="Location">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-lg border border-white/10 text-[10px] font-bold uppercase text-gray-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-6 py-2.5 rounded-lg text-[10px] font-black uppercase text-white shadow-lg transition-all">
                        <span x-text="editMode ? 'Save Changes' : 'Save Project'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection