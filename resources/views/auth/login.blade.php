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
                
                <div class="relative flex items-center mt-2">
                    <div class="flex-grow border-t border-white/30"></div>
                    <span class="flex-shrink mx-6 text-primary font-bold text-[10px] uppercase tracking-[0.25em] opacity-80">secure login with</span>
                    <div class="flex-grow border-t border-white/30"></div>
                </div>
                
                <div class="grid grid-cols-2 gap-5">
                    <button type="button" class="social-btn h-16 rounded-2xl flex items-center justify-center gap-3 text-on-surface font-semibold text-sm">
                        <svg class="w-6 h-6" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path></svg>
                        Google
                    </button>
                    <button type="button" class="social-btn h-16 rounded-2xl flex items-center justify-center gap-3 text-on-surface font-semibold text-sm">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.15 2.95.93 3.78 2.04-3.22 1.96-2.7 6.46.35 7.72-.72 1.34-1.39 2.45-2.78 3.25zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"></path></svg>
                        Apple
                    </button>
                </div>
            </div>
        </div>
    </main>

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