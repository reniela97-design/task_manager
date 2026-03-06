<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Emergence Portal | Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { 
            --emergence-blue: #2563EB; 
            --emergence-dark: #1e293b;
            --emergence-red: #DC2626; 
        }
        [x-cloak] { display: none !important; }
        
        /* New Light Background */
        body { 
            background-color: #f8fafc; 
            color: #334155; 
            font-family: 'Outfit', sans-serif; 
            margin: 0; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
        }

        /* Soft Animated Glows (Pastel) */
        .bg-glow { 
            position: fixed; 
            inset: 0; 
            z-index: -1; 
            background: 
                radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.15) 0%, transparent 50%), 
                radial-gradient(circle at 90% 80%, rgba(220, 38, 38, 0.1) 0%, transparent 50%); 
            filter: blur(60px); 
            animation: pulse-glow 10s infinite alternate; 
        }

        .bg-img { 
            position: fixed; 
            inset: 0; 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            opacity: 0.05; /* Subtle texture only */
            z-index: -2; 
            filter: grayscale(100%);
        }

        /* NEW ANIMATION: Smooth Reveal & Float up */
        @keyframes intro-reveal {
            0% { 
                opacity: 0; 
                transform: translateY(40px) scale(0.95) perspective(1000px) rotateX(5deg);
                filter: blur(10px);
            }
            100% { 
                opacity: 1; 
                transform: translateY(0) scale(1) perspective(1000px) rotateX(0deg); 
                filter: blur(0);
            }
        }

        .glass-portal { 
            display: flex; 
            width: 95%; 
            max-width: 1000px; 
            min-height: 600px; 
            /* White Frosted Glass */
            background: rgba(255, 255, 255, 0.75); 
            backdrop-filter: blur(40px); 
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.8); 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255,255,255,0.5);
            border-radius: 32px; 
            overflow: hidden; 
            /* Trigger Animation */
            animation: intro-reveal 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        .visual-side { 
            width: 40%; 
            padding: 48px; 
            /* Light Gradient */
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); 
            border-right: 1px solid rgba(0, 0, 0, 0.05); 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            position: relative;
        }

        /* Decor shape on visual side */
        .visual-side::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(37,99,235,0.05) 0%, transparent 60%);
            z-index: 0;
            pointer-events: none;
        }

        .form-side { flex: 1; padding: 48px 64px; display: flex; flex-direction: column; justify-content: center; z-index: 10; }

        /* Inputs customized for Light Mode */
        .input-pill { 
            background: #f1f5f9; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            color: #0f172a; 
            padding: 14px 18px; 
            width: 100%; 
            font-size: 13px; 
            outline: none; 
            transition: all 0.3s ease; 
            font-weight: 500;
        }
        .input-pill::placeholder { color: #94a3b8; }
        .input-pill:focus { 
            border-color: var(--emergence-blue); 
            background: #fff; 
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.15); 
        }

        .btn-action { 
            background: #0f172a; /* Dark button for contrast */
            color: #fff; 
            padding: 14px 24px; 
            border-radius: 12px; 
            width: 100%; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            font-weight: 800; 
            text-transform: uppercase; 
            transition: all 0.3s ease; 
            cursor: pointer; 
            border: none; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .btn-login:hover { background-color: var(--emergence-blue); transform: translateY(-2px); box-shadow: 0 15px 20px -3px rgba(37, 99, 235, 0.3); }
        .btn-register:hover { background-color: var(--emergence-red); transform: translateY(-2px); box-shadow: 0 15px 20px -3px rgba(220, 38, 38, 0.3); }
        
        .arrow-circle { background: rgba(255,255,255,0.2); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body x-data="{ view: '{{ $errors->has('name') || $errors->has('password_confirmation') ? 'register' : 'login' }}' }">

    <div class="bg-glow"></div>
    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop" class="bg-img" alt="Background">

    <main class="glass-portal">
        <section class="visual-side">
            <div class="z-10">
                <span class="font-black text-2xl tracking-tighter uppercase text-slate-800">EMERGE<span class="text-red-600">N</span>CE</span>
            </div>
            <div class="z-10">
                <h1 class="text-4xl font-black uppercase tracking-tighter leading-none mb-3 text-slate-900">Task <br> <span class="text-blue-600">Analytics</span></h1>
                <p class="text-slate-500 font-medium text-xs">Secure enterprise portal for project orchestration.</p>
            </div>
        </section>

        <section class="form-side">
            {{-- LOGIN VIEW --}}
            <div x-show="view === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex justify-between items-end mb-8">
                    <div><h2 class="text-3xl font-black uppercase italic text-slate-800">Log in</h2></div>
                    <button @click="view = 'register'" class="text-[10px] font-black uppercase text-blue-600 hover:underline tracking-wide">No account? Register</button>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="email" name="email" placeholder="EMAIL ADDRESS" class="input-pill" required autofocus>
                    <input type="password" name="password" placeholder="PASSWORD" class="input-pill" required>
                    <button type="submit" class="btn-action btn-login">
                        <span class="text-xs tracking-wider">Sign in</span>
                        <div class="arrow-circle">→</div>
                    </button>
                </form>
            </div>

            {{-- REGISTER VIEW --}}
            <div x-show="view === 'register'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="flex justify-between items-end mb-8">
                    <div><h2 class="text-3xl font-black uppercase italic text-slate-800">Register</h2></div>
                    <button @click="view = 'login'" class="text-[10px] font-black text-blue-600 hover:underline tracking-wide">Back to Log in</button>
                </div>
                
                <form action="{{ route('register') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="name" placeholder="FULL NAME" class="input-pill" required>
                    <input type="email" name="email" placeholder="WORK EMAIL" class="input-pill" required>
                    <input type="password" name="password" placeholder="PASSWORD" class="input-pill" required>
                    <input type="password" name="password_confirmation" placeholder="CONFIRM" class="input-pill" required>
                    
                    {{-- FIXED ROLE DROPDOWN --}}
                    <div class="mt-2">
                        <select name="role_id" required class="input-pill w-full bg-white cursor-pointer">
                            <option value="" disabled selected>SELECT ROLE</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-action btn-register mt-4">
                        <span class="text-xs tracking-wider">Join Team</span>
                        <div class="arrow-circle">→</div>
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>