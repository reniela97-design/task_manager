<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Emergence Portal</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Prevents Alpine.js elements from flickering before loading */
        [x-cloak] { display: none !important; }

        body { 
            background-color: #020408; 
            color: #fff; 
            font-family: 'Outfit', sans-serif; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }

        .main-content { 
            flex: 1; 
            overflow-y: auto; 
            padding: 40px; 
            position: relative; 
        }

        /* Futuristic Background Glow */
        .bg-glow { 
            position: fixed; 
            inset: 0; 
            z-index: -1; 
            background: radial-gradient(circle at 50% 50%, rgba(29, 61, 106, 0.4) 0%, transparent 60%); 
            filter: blur(100px); 
        }

        /* Custom Slim Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { 
            background: rgba(255, 255, 255, 0.1); 
            border-radius: 10px; 
        }
    </style>
</head>
<body x-data>
    <div class="bg-glow"></div>

    {{-- I-load ang Sidebar --}}
    @include('partials.sidebar')

    <main class="main-content custom-scrollbar">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-black uppercase italic tracking-tighter">@yield('title')</h2>
                <p class="text-gray-500 font-bold text-[10px] uppercase tracking-widest">
                    @yield('subtitle', 'Management Portal')
                </p>
            </div>
            
            {{-- USER PROFILE SECTION --}}
            <div class="flex items-center gap-4 bg-white/5 p-2 pr-4 rounded-full border border-white/5">
                {{-- Avatar (First Letter of Name) --}}
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-black">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                
                {{-- User Info --}}
                <div class="text-left">
                    <p class="text-[10px] font-black uppercase tracking-tighter leading-none">
                        {{ Auth::user()->name }}
                    </p>
                    
                    {{-- FIXED: DYNAMIC ROLE LABEL --}}
                    {{-- Dili na 'Verified Admin' pirmi, mag-base na sa database --}}
                    <p class="text-[8px] text-blue-500 font-bold uppercase tracking-widest">
                        {{ Auth::user()->role->role_name ?? 'User' }}
                    </p>
                </div>
            </div>
        </header>

        @yield('content')
    </main>
</body>
</html>