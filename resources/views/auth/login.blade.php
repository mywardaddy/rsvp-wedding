<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
  
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
  
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        try {
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#00342b",
                            "on-primary": "#ffffff",
                            "primary-container": "#004d40",
                            "secondary": "#7b5455",
                            "background": "#fbf9f9",
                            "on-surface": "#1b1c1c",
                            "on-surface-variant": "#3f4945",
                            "outline": "#707975",
                            "primary-fixed": "#afefdd",
                            "on-primary-fixed": "#00201a",
                        },
                        fontFamily: {
                            "headline": ["Noto Serif", "serif"],
                            "body": ["Plus Jakarta Sans", "sans-serif"],
                            "label": ["Plus Jakarta Sans", "sans-serif"]
                        }
                    }
                }
            }
        } catch (_e) {}
    </script>

    <style type="text/tailwindcss">
        @layer utilities {
            .bg-cinematic {
                background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop'); 
                background-size: cover;
                background-position: center;
            }
            .glass-card {
                @apply bg-white/30 backdrop-blur-xl border border-white/40 shadow-[0_8px_32px_0_rgba(0,0,0,0.1)];
            }
            .glass-input {
                @apply bg-white/40 border border-white/50 focus:bg-white/70 focus:ring-2 focus:ring-primary/40 focus:outline-none transition-all duration-300;
            }
            .primary-glow-btn {
                @apply bg-primary hover:bg-primary-container shadow-[0_0_15px_rgba(0,52,43,0.4)] hover:shadow-[0_0_25px_rgba(0,52,43,0.6)] transition-all duration-300;
            }
            .social-btn {
                @apply bg-white/50 backdrop-blur-md hover:bg-white/80 border border-white/50 shadow-sm hover:shadow-md transition-all duration-300;
            }
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed overflow-x-hidden min-h-screen flex flex-col">

    <header class="fixed top-0 left-0 right-0 z-50 bg-white/10 backdrop-blur-xl border-b border-white/20 shadow-sm">
        <nav class="flex justify-between items-center w-full px-8 py-5 max-w-7xl mx-auto">
            <div class="font-headline text-2xl text-yellow-50 font-bold tracking-tight text-primary">NIKAH YUK!</div>
            <div class="hidden md:flex items-center gap-10">
                <a class="text-on-surface/80 font-medium hover:text-primary text-yellow-50 transition-colors duration-300" href="#">Home</a>
                <a class="text-on-surface/80 font-medium hover:text-primary text-yellow-50 transition-colors duration-300" href="#">Features</a>
                <a class="text-on-surface/80 font-medium hover:text-primary text-yellow-50 transition-colors duration-300" href="#">Pricing</a>
                <a class="text-on-surface/80 font-medium hover:text-primary text-yellow-50 transition-colors duration-300" href="#">Gallery</a>
            </div>
            <div class="flex items-center gap-4 text-primary">
                <button class="material-symbols-outlined p-2 hover:bg-white/40 rounded-full transition-colors">settings</button>
                <button class="material-symbols-outlined p-2 hover:bg-white/40 rounded-full transition-colors">help</button>
            </div>
        </nav>
    </header>

    <main class="flex-grow flex items-center justify-center relative min-h-screen pt-20">
        <div class="absolute inset-0 bg-cinematic z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-black/60 via-black/20 to-black/40 z-[1]"></div>
        <div class="absolute inset-0 bg-primary/10 backdrop-blur-[2px] z-[2]"></div>
        
        <div class="relative z-10 w-full max-w-[540px] px-6 py-12">
            <div class="glass-card rounded-[3rem] p-12 md:p-16 flex flex-col gap-10">
                <div class="text-center space-y-4">
                    <h1 class="font-headline text-5xl text-yellow-50 font-bold text-primary tracking-tight leading-tight">Welcome Back</h1>
                    <p class="text-on-surface-variant font-medium text-lg tracking-wide">Enter your details to access your dashboard</p>
                </div>
                
                <x-auth-session-status class="mb-4 text-primary font-bold text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-7">
                    @csrf
                    
                    <div class="flex flex-col gap-2">
                        <label class="font-label text-xs text-yellow-50 font-bold text-primary px-1 tracking-[0.15em] uppercase" for="email">{{ __('Email Address') }}</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-outline/60">mail</span>
                            <input class="glass-input h-16 pl-14 pr-6 w-full rounded-2xl text-on-surface placeholder:text-outline/60 font-medium text-lg" 
                                   id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="name@example.com" 
                                   required autofocus autocomplete="username">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 font-semibold" />
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <label class="font-label text-xs text-yellow-50 font-bold text-primary px-1 tracking-[0.15em] uppercase" for="password">{{ __('Password') }}</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-outline/60">lock</span>
                            <input class="glass-input h-16 pl-14 pr-14 w-full rounded-2xl text-on-surface placeholder:text-outline/60 font-medium text-lg" 
                                   id="password" 
                                   type="password" 
                                   name="password" 
                                   placeholder="••••••••" 
                                   required autocomplete="current-password">
                            
                            <button id="togglePassword" class="absolute right-5 top-1/2 -translate-y-1/2 text-outline/60 hover:text-primary transition-colors flex items-center" type="button" aria-label="Toggle Password Visibility">
                                <span class="material-symbols-outlined text-2xl" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 font-semibold" />
                    </div>

                    <div class="flex items-center justify-between mt-1 px-1">
                        <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-outline/40 bg-white/20 text-primary shadow-sm focus:ring-primary/50 cursor-pointer" name="remember">
                            <span class="ms-2 text-sm text-yellow-50 font-medium text-on-surface-variant group-hover:text-primary transition-colors">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-secondary text-yellow-50 font-bold text-sm hover:text-primary transition-all underline underline-offset-4 decoration-secondary/30" href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>
                    
                    <button class="primary-glow-btn h-16 rounded-2xl text-on-primary font-bold text-xl flex items-center justify-center gap-3 group mt-4" type="submit">
                        {{ __('Log in') }}
                        <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform text-2xl">arrow_forward</span>
                    </button>
                </form>
                
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
        });
    </script>
</body>
</html>