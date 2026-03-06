@extends('layouts.app')

@section('title', 'Task Registry')
@section('subtitle', 'Core Operational Queue & Status Tracking')

@section('content')
<div class="space-y-8">
    {{-- TOP BAR: Search & Action --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="SEARCH REGISTRY..." class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-[10px] font-bold tracking-widest focus:outline-none focus:border-blue-500 w-64 transition-all uppercase text-white">
            </div>
            
            {{-- GI-TANGTANG ANG @IF ARON MAKITA SA TANAN ANG BUTTON --}}
            <button type="button" 
                    @click="$dispatch('open-modal', 'create-task-modal')" 
                    class="bg-blue-600 hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border border-blue-400/20">
                + Register New Task
            </button>
        </div>
    </div>

    {{-- MAIN REGISTRY TABLE --}}
    <div class="bg-[#05050a]/60 backdrop-blur-xl border border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/[0.02]">
                        <th class="px-8 py-6 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Task Identification</th>
                        <th class="px-6 py-6 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Timeline</th>
                        <th class="px-6 py-6 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Priority</th>
                        <th class="px-6 py-6 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tasks as $task)
                    <tr class="group hover:bg-white/[0.02] transition-all">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-white uppercase tracking-tight group-hover:text-blue-400 transition-colors">{{ $task->title }}</span>
                                <span class="text-[9px] text-gray-500 font-bold mt-1 uppercase italic">{{ Str::limit($task->description, 40) }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[8px] text-blue-500/60 font-black uppercase tracking-widest">
                                        {{ $task->project->name ?? 'NO PROJECT' }} / {{ $task->client->name ?? 'INTERNAL' }}
                                    </span>
                                    <span class="text-[8px] text-orange-500/80 font-black uppercase tracking-widest border-l border-white/10 pl-2">
                                        ASSIGNED: {{ $task->user->name ?? 'UNASSIGNED' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-6 italic font-black text-gray-400 uppercase tracking-tighter text-[10px]">
                            {{ $task->task_date ? \Carbon\Carbon::parse($task->task_date)->format('M d, Y') : 'NO DATE SET' }}
                        </td>

                        <td class="px-6 py-6">
                            @php
                                $priorityClass = match(strtolower($task->priority ?? '')) {
                                    'high' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    'medium' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'low' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                                    default => 'bg-white/5 text-gray-600 border-white/5'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border {{ $priorityClass }}">
                                {{ $task->priority ?? 'UNDEFINED' }}
                            </span>
                        </td>

                        <td class="px-6 py-6 font-black text-white italic tracking-widest uppercase text-[9px]">
                            {{ $task->status ?? 'OPEN' }}
                        </td>
                        
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3 items-center">
                                <button @click="$dispatch('open-modal', 'view-task-{{ $task->id }}')" class="p-2 bg-white/5 hover:bg-blue-600/20 border border-white/10 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                
                                @if(auth()->user()->role && in_array(auth()->user()->role->role_name, ['Administrator', 'Manager']))
                                <button @click="$dispatch('open-modal', 'edit-task-{{ $task->id }}')" class="p-2 bg-white/5 hover:bg-yellow-600/20 border border-white/10 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                @endif

                                @if(!$task->started_at)
                                    <form action="{{ route('tasks.start', $task->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-green-600/20 hover:bg-green-600 border border-green-500/40 text-green-500 hover:text-white rounded-lg text-[9px] font-black uppercase transition-all">Start</button>
                                    </form>
                                @elseif(!$task->finished_at)
                                    <form action="{{ route('tasks.finish', $task->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-orange-600/20 hover:bg-orange-600 border border-orange-500/40 text-orange-500 hover:text-white rounded-lg text-[9px] font-black uppercase transition-all">Finish</button>
                                    </form>
                                @endif

                                @if(auth()->user()->role && in_array(auth()->user()->role->role_name, ['Administrator', 'Manager']))
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('DELETE TASK?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-white/5 hover:bg-red-600/20 border border-white/10 rounded-xl transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-12 text-center text-gray-500 italic font-bold uppercase text-[10px]">No active dossier</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: REGISTER NEW TASK (NOW ACCESSIBLE BY ALL) --}}
    <x-modal name="create-task-modal" focusable x-cloak>
        <div class="p-8 bg-[#0a0a0a] border border-white/10 rounded-3xl text-left shadow-2xl">
            <h2 class="text-xl font-black text-white uppercase italic mb-6 border-b border-white/5 pb-4">Register New Task</h2>
            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Task Identification</label>
                    <input type="text" name="title" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500" placeholder="ENTER TASK TITLE...">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Project Scope</label>
                        <select name="project_id" required class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            <option value="" disabled selected>Select Project</option>
                            @foreach($projects as $project) <option value="{{ $project->id }}">{{ $project->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Target Client</label>
                        <select name="client_id" required class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            <option value="" disabled selected>Select Client</option>
                            @foreach($clients as $client) <option value="{{ $client->id }}">{{ $client->name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- CONDITIONAL ASSIGNMENT LOGIC --}}
                    @if(auth()->user()->role && in_array(auth()->user()->role->role_name, ['Administrator', 'Manager']))
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Assign Personnel</label>
                            <select name="user_id" required class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500">
                                <option value="" disabled selected>Choose User</option>
                                @foreach($users as $user) 
                                    <option value="{{ $user->id }}">{{ $user->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                    @else
                        {{-- AUTO-ASSIGN IF ORDINARY USER --}}
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Assigned To</label>
                            <div class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-400 italic">
                                {{ auth()->user()->name }} (Self)
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Operational Date</label>
                        <input type="date" name="task_date" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Urgency Level</label>
                        <select name="priority" class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Category</label>
                        <select name="category" class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            @foreach($categories as $cat) <option value="{{ $cat->category_name }}">{{ $cat->category_name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Task Intelligence (Description)</label>
                    <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500" placeholder="PROVIDE OPERATIONAL DETAILS..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-white/5 mt-4">
                    <button type="button" @click="$dispatch('close')" class="px-6 py-2 text-[10px] font-black text-gray-500 uppercase hover:text-white transition-colors">Abort</button>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg hover:bg-blue-500 transition-all">Commit to Registry</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- MODAL: VIEW TASK DETAILS --}}
    @foreach($tasks as $task)
    <x-modal name="view-task-{{ $task->id }}" focusable x-cloak>
        <div class="p-8 bg-[#020408] border border-white/10 rounded-3xl text-left shadow-2xl">
            <div class="flex justify-between items-start border-b border-white/5 pb-4 mb-6">
                <h2 class="text-2xl font-black uppercase italic text-white tracking-tighter">{{ $task->title }}</h2>
                <span class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 text-blue-500 text-[8px] font-black rounded-full uppercase tracking-widest">
                    {{ $task->status }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 text-xs font-bold text-white uppercase">
                    <p class="text-[8px] font-black text-blue-500 uppercase mb-1 tracking-[0.2em]">Assigned To</p>
                    {{ $task->user->name ?? 'NO PERSONNEL ASSIGNED' }}
                </div>
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 text-xs font-bold text-white uppercase">
                    <p class="text-[8px] font-black text-blue-500 uppercase mb-1 tracking-[0.2em]">Timeline</p>
                    {{ $task->task_date ? \Carbon\Carbon::parse($task->task_date)->format('d M Y') : 'PENDING' }}
                </div>
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 text-center text-xs font-bold text-white uppercase">
                    <p class="text-[8px] font-black text-gray-500 uppercase mb-1 tracking-[0.2em]">Priority</p>
                    {{ $task->priority ?? 'MEDIUM' }}
                </div>
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 text-center text-xs font-bold text-white uppercase">
                    <p class="text-[8px] font-black text-gray-500 uppercase mb-1 tracking-[0.2em]">Category</p>
                    {{ $task->category ?? 'GENERAL' }}
                </div>
                <div class="col-span-2 bg-white/5 p-4 rounded-xl border border-white/5">
                    <p class="text-[8px] font-black text-blue-500 uppercase mb-1 tracking-[0.2em]">Detailed Description</p>
                    <p class="text-xs text-gray-400 leading-relaxed uppercase">{{ $task->description ?? 'No extra intelligence available.' }}</p>
                </div>
            </div>
            <button @click="$dispatch('close')" class="w-full mt-6 bg-white/10 text-white py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white/20 transition-all border border-white/5">Close Dossier</button>
        </div>
    </x-modal>
    @endforeach

    {{-- MODAL: EDIT TASK --}}
    @foreach($tasks as $task)
    @if(auth()->user()->role && in_array(auth()->user()->role->role_name, ['Administrator', 'Manager']))
    <x-modal name="edit-task-{{ $task->id }}" focusable x-cloak>
        <div class="p-8 bg-[#0a0a0a] border border-white/10 rounded-3xl text-left shadow-2xl">
            <h2 class="text-xl font-black text-white uppercase italic mb-6 border-b border-white/5 pb-4">Update Task Protocol</h2>
            <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Task Identification</label>
                    <input type="text" name="title" value="{{ $task->title }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500" placeholder="ENTER TASK TITLE...">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Project Scope</label>
                        <select name="project_id" required class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            @foreach($projects as $project) 
                                <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Target Client</label>
                        <select name="client_id" required class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            @foreach($clients as $client) 
                                <option value="{{ $client->id }}" {{ $task->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option> 
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Assign Personnel</label>
                        <select name="user_id" required class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500">
                            @foreach($users as $user) 
                                <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Operational Date</label>
                        <input type="date" name="task_date" value="{{ $task->task_date }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Urgency Level</label>
                        <select name="priority" class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Status</label>
                        <select name="status" class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                            <option value="Open" {{ $task->status == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
                 
                <div>
                     <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Category</label>
                     <select name="category" class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none">
                         @foreach($categories as $cat) 
                             <option value="{{ $cat->category_name }}" {{ $task->category == $cat->category_name ? 'selected' : '' }}>{{ $cat->category_name }}</option> 
                         @endforeach
                     </select>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 block">Task Intelligence (Description)</label>
                    <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500">{{ $task->description }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-white/5 mt-4">
                    <button type="button" @click="$dispatch('close')" class="px-6 py-2 text-[10px] font-black text-gray-500 uppercase hover:text-white transition-colors">Abort</button>
                    <button type="submit" class="px-8 py-3 bg-yellow-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg hover:bg-yellow-500 transition-all">Update Registry</button>
                </div>
            </form>
        </div>
    </x-modal>
    @endif
    @endforeach
</div>
@endsection