<aside class="w-72 bg-[#05050a]/80 backdrop-blur-xl border-r border-white/5 flex flex-col h-screen sticky top-0 z-50">
    <div class="p-8">
        <span class="font-black text-xl tracking-tighter uppercase">
            EMERGE<span class="text-red-500">N</span>CE
        </span>
        <div class="text-[7px] font-bold text-gray-500 tracking-[0.4em] uppercase">Task Management v1.0.0</div>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
        {{-- SECTION 1: MAIN (Para sa Tanang Users) --}}
        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em] mb-4 px-4">Main</p>
        
        {{-- 1. Dashboard --}}
        <x-sidebar-link route="dashboard" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">Dash Board</x-sidebar-link>
        
        {{-- 2. Tasks Registry --}}
        <x-sidebar-link route="tasks.index" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">Tasks Registry</x-sidebar-link>

        {{-- KANI NGA SECTION (Administration & Operations) TAGO KUNG USER LANG --}}
        @if(auth()->user()->role && in_array(auth()->user()->role->role_name, ['Administrator', 'Manager']))
            {{-- Administration --}}
            <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em] mt-8 mb-4 px-4">Administration</p>
            
            {{-- Icon: User Group --}}
            <x-sidebar-link route="users.index" icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">User Accounts</x-sidebar-link>
            
            {{-- Icon: Shield/Security --}}
            <x-sidebar-link route="roles.index" icon="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6.119c-.035.505-.05 1.011-.05 1.518 0 5.247 3.03 9.789 7.377 12.004a11.952 11.952 0 007.377-12.004c0-.507-.015-1.013-.05-1.518A11.952 11.952 0 0012 2.714z">Roles</x-sidebar-link>
            
            {{-- Icon: Folder/Categories --}}
            <x-sidebar-link route="categories.index" icon="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.158c0 .671.337 1.298.905 1.647l1.246.766c.568.349.905.976.905 1.647V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-1.182c0-.671.337-1.298.905-1.647l1.246-.766c.568-.349.905-.976.905-1.647v-.158">Categories</x-sidebar-link>
            
            {{-- Icon: Computer/System --}}
            <x-sidebar-link route="systems.index" icon="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25">Systems</x-sidebar-link>
            
            {{-- Icon: Tag/Types --}}
            <x-sidebar-link route="types.index" icon="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.317a2.25 2.25 0 000-3.182L11.159 3.659A2.25 2.25 0 009.568 3zM6 6a1 1 0 100 2 1 1 0 000-2z">Types</x-sidebar-link>
       
            {{-- Operations --}}
            <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em] mt-8 mb-4 px-4">Operations</p>
            <x-sidebar-link route="clients.index" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5">Clients</x-sidebar-link>
            <x-sidebar-link route="projects.index" icon="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">Projects</x-sidebar-link>
        @endif

        {{-- SECTION 2: ANALYTICS & ABOUT (Para sa Tanang Users) --}}
        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-[0.2em] mt-8 mb-4 px-4">Insights</p>
        
        {{-- 3. Activity Logs --}}
        <x-sidebar-link route="logs.index" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">Activity Logs</x-sidebar-link>
        
        {{-- 4. Reports --}}
        <x-sidebar-link route="reports.index" icon="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586">Reports</x-sidebar-link>
        
       {{-- 5. About System --}}
<x-sidebar-link route="about.index" icon="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
    About System
</x-sidebar-link>
    <div class="p-4 border-t border-white/5">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full p-4 rounded-xl text-red-400 hover:bg-red-500/10 transition-all font-bold text-xs uppercase tracking-widest">
                Logout
            </button>
        </form>
    </div>
</aside>