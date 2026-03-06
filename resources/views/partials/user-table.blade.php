<div class="bg-[#05050a]/50 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-white/5 bg-white/[0.02]">
                <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-500 tracking-widest">Member</th>
                <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-500 tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($users as $user)
            {{-- Search Logic Filter --}}
            <tr 
                x-show="search === '' || '{{ strtolower($user->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(search.toLowerCase())"
                class="hover:bg-white/[0.02] transition-colors"
            >
                <td class="px-6 py-4 text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-[10px] font-black italic">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase">{{ $user->name }}</p>
                            <p class="text-[9px] text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-4">
                        <button @click="$dispatch('open-modal', 'view-user-{{ $user->id }}')" class="text-[9px] font-black text-blue-400 uppercase">View</button>
                        <button @click="$dispatch('open-modal', 'edit-user-{{ $user->id }}')" class="text-[9px] font-black text-gray-400 uppercase">Edit</button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="px-6 py-8 text-center text-gray-500 text-[10px] uppercase font-black tracking-widest italic">
                    No accounts in this category.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>