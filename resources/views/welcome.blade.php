<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ config('app.name', 'NIKAH YUK!') }} | Cinematic Digital Invitations</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&amp;family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-lowest": "#ffffff",
                        "outline": "#707975",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#3a0915",
                        "tertiary-fixed": "#ffd9dd",
                        "primary-fixed-dim": "#94d3c1",
                        "on-surface": "#1b1c1c",
                        "secondary-container": "#fecbcb",
                        "background": "#fbf9f9",
                        "inverse-surface": "#303031",
                        "outline-variant": "#bfc9c4",
                        "on-secondary-fixed-variant": "#613d3e",
                        "surface-container-high": "#e9e8e8",
                        "surface-variant": "#e4e2e2",
                        "surface-tint": "#29695b",
                        "on-tertiary-fixed-variant": "#70343e",
                        "tertiary": "#511b26",
                        "secondary-fixed": "#ffdad9",
                        "inverse-on-surface": "#f2f0f0",
                        "error": "#ba1a1a",
                        "on-background": "#1b1c1c",
                        "on-primary-fixed-variant": "#065043",
                        "on-surface-variant": "#3f4945",
                        "on-secondary-container": "#7a5354",
                        "inverse-primary": "#94d3c1",
                        "on-secondary-fixed": "#2f1314",
                        "secondary": "#7b5455",
                        "on-primary-container": "#7ebdac",
                        "surface-container": "#efeded",
                        "surface-container-low": "#f5f3f3",
                        "surface-dim": "#dbdada",
                        "on-primary": "#ffffff",
                        "primary": "#00342b",
                        "surface": "#fbf9f9",
                        "surface-bright": "#fbf9f9",
                        "on-secondary": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "on-primary-fixed": "#00201a",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#e4e2e2",
                        "primary-fixed": "#afefdd",
                        "on-error": "#ffffff",
                        "primary-container": "#004d40",
                        "on-tertiary-container": "#eb9ba6",
                        "tertiary-container": "#6c313b",
                        "secondary-fixed-dim": "#ecbaba",
                        "tertiary-fixed-dim": "#ffb2bc"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Noto Serif"],
                        "body": ["Plus Jakarta Sans"],
                        "label": ["Plus Jakarta Sans"],
                        "noto-serif": ["Noto Serif"],
                        "plus-jakarta-sans": ["Plus Jakarta Sans"]
                    }
                },
            },
        }
    </script>
    <style>
        .glass-morphism {
            background: rgba(251, 249, 249, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .jeweled-action {
            background: linear-gradient(45deg, #00342b, #004d40);
        }
        .rose-glow {
            box-shadow: 0 20px 40px rgba(123, 84, 85, 0.15);
        }
        .emerald-glow {
            box-shadow: 0 20px 40px rgba(0, 52, 43, 0.15);
        }
        .rose-gold-glow {
            box-shadow: 0 20px 50px rgba(235, 155, 166, 0.3);
            border: 1px solid rgba(235, 155, 166, 0.4);
        }
        .floating-element {
            pointer-events: none;
            position: absolute;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface antialiased overflow-x-hidden">

    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 bg-white/65 dark:bg-emerald-950/65 backdrop-blur-xl shadow-sm dark:shadow-emerald-900/20">
        <div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
            <div class="text-2xl font-noto-serif font-bold text-emerald-900 dark:text-emerald-100 tracking-tighter">NIKAH YUK!</div>
            <div class="hidden md:flex gap-8 items-center">
                <a class="font-noto-serif italic tracking-wide text-sm text-emerald-800/70 dark:text-emerald-200/70 hover:text-emerald-900 dark:hover:text-emerald-50 transition-colors" href="#">Features</a>
                <a class="font-noto-serif italic tracking-wide text-sm text-emerald-800/70 dark:text-emerald-200/70 hover:text-emerald-900 dark:hover:text-emerald-50 transition-colors" href="#pricing">Pricing</a>
                <a class="font-noto-serif italic tracking-wide text-sm text-emerald-800/70 dark:text-emerald-200/70 hover:text-emerald-900 dark:hover:text-emerald-50 transition-colors" href="#gallery">Gallery</a>
            </div>
            <div class="flex gap-4 items-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-noto-serif italic tracking-wide text-sm text-emerald-800/70 dark:text-emerald-200/70 hover:opacity-80 transition-all duration-300 scale-95 active:scale-90">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-noto-serif italic tracking-wide text-sm text-emerald-800/70 dark:text-emerald-200/70 hover:opacity-80 transition-all duration-300 scale-95 active:scale-90">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-primary text-on-primary px-6 py-2 rounded-full font-noto-serif italic tracking-wide text-sm hover:opacity-80 transition-all duration-300 scale-95 active:scale-90 inline-block">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover opacity-60" data-alt="luxury wedding venue at golden hour" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDxQToA7yFdz7u8bC8cylsQ5SzO_jkTmCbW9XoVY6Trmoi0dOUJ3W8a5oih2aoxQdvo_rrA3y2bilnB5RNU2nYvz9WTE0BlALEDvCZE73VTxVD6tnv6dDODPkzuSj3Qw4RY0hm6aYHOiJ-OlnpVxOZPT0lBr9Y4td-UYlg-iS3aUzVl-3M55y4Rcxdoqf_OFK4_r08XOq0X4bmx8mwu0M_yxTbZ5QloYGavEPjPX0nGocExHbhAmJPFpdq4u-dTO-sZ51CxEJf6VGg"/>
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/10 via-background/80 to-secondary/10"></div>
        </div>
        <div class="floating-element top-1/4 left-10 w-24 h-24 opacity-40">
            <img class="w-full h-full object-contain" data-alt="floating petal" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzT1teXUycR1G2v18dsaNqaQ6R-GwVt4zpbvZ1h-wj6rdTVDnqlIzH1oQtnIWJwc9PuIn5pywzxIgBaCbEnY9mtLKMwwPQDbzQtI4oSgJ7Qq8_HZLjF_E9HGucbZ5co0NySa68qxxTqOcnwgO6eqjRiJC-0TKnlqAepzTN5D6-RhZQVNRxQQtZa-4mq32z-tQUbFqUql1L_-xDVBihk3Z6YBOWPAm_gsgCHkZPgADDzVeIu6syQJGpTynM-JnyOEwc8qKkXBPdYVE"/>
        </div>
        <div class="relative z-20 text-center max-w-4xl px-6">
            <h1 class="font-headline text-6xl md:text-8xl text-primary font-bold tracking-tight mb-8">
                Your Love, <br/><span class="italic font-normal serif">Cinematically</span> Told.
            </h1>
            <p class="font-body text-xl text-on-surface-variant mb-12 max-w-2xl mx-auto leading-relaxed">
                Elevate your special day with premium digital invitations that blend timeless elegance with futuristic glassmorphism.
            </p>
            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <a href="{{ Route::has('register') ? route('register') : '#' }}" class="jeweled-action text-on-primary px-12 py-5 rounded-full text-lg font-semibold shadow-2xl hover:scale-105 transition-transform duration-300 flex items-center gap-3">
                    Join Now
                    <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
                </a>
                <a href="#gallery" class="glass-morphism border border-outline-variant/30 text-primary px-12 py-5 rounded-full text-lg font-semibold hover:bg-white transition-colors inline-block">
                    Explore Gallery
                </a>
            </div>
        </div>
    </section>

    <!-- Bento Grid Features -->
    <section class="py-32 px-6 max-w-7xl mx-auto relative">
        <div class="text-center mb-20">
            <h2 class="font-headline text-4xl md:text-5xl text-primary mb-4">Crafting Memories</h2>
            <p class="font-body text-on-surface-variant">The future of wedding celebrations begins here.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[280px]">
            <div class="md:col-span-8 md:row-span-2 glass-morphism rounded-3xl p-10 flex flex-col justify-end relative overflow-hidden group">
                <img class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-105 transition-transform duration-700" data-alt="cinematic close up of a luxury wedding table setting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_vefnq0rLzc4_95-NVYeqvXxgODTX0zZP3UqtR9GTnkkyujSPfIz9m9dEjASr3UefZEjOBXqFyd2uixuXvJHTHbI3DMwnQWKBdAv9vl03FzgVlzd0igXNgCEC5eWIEtio8wJOm7IdvRssa8n-wg8c3WB5QCIVWWfyFJ20nBv7TtqRhNiCLwgTOKQb8ddlWCE2J1aoZdDPqbzwXozq0pBDwxPx6OfiNL9IaRTjnFM668SHphnf2AOAFKuFBSJnZdlyP6bNrNWQWMo"/>
                <div class="relative z-10">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-6" data-icon="auto_awesome">auto_awesome</span>
                    <h3 class="font-headline text-3xl text-primary mb-4">Cinematic Narrative</h3>
                    <p class="text-on-surface-variant max-w-md">Our invitations aren't just dates; they are immersive experiences that tell your unique journey through motion and depth.</p>
                </div>
            </div>
            <div class="md:col-span-4 glass-morphism rounded-3xl p-8 emerald-glow border border-primary/5">
                <span class="material-symbols-outlined text-secondary text-3xl mb-4" data-icon="history_edu">history_edu</span>
                <h3 class="font-headline text-xl text-primary mb-2">Modern Guestbook</h3>
                <p class="text-sm text-on-surface-variant">Collect heartfelt digital wishes in a beautifully rendered 3D environment that lasts forever.</p>
            </div>
            <div class="md:col-span-4 glass-morphism rounded-3xl p-8 rose-glow border border-secondary/5">
                <span class="material-symbols-outlined text-secondary text-3xl mb-4" data-icon="event_available">event_available</span>
                <h3 class="font-headline text-xl text-primary mb-2">Instant RSVP</h3>
                <p class="text-sm text-on-surface-variant">Seamlessly manage guest lists and dietary requirements with our real-time dashboard analytics.</p>
            </div>
            <div class="md:col-span-4 md:row-span-1 glass-morphism rounded-3xl p-8 relative overflow-hidden group">
                <img class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:scale-110 transition-transform duration-700" data-alt="romantic couple walking" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAtiAuzq1-pbTy7a49_Eds5KDy9sCK5aPG_gXu-IXagcl3AWSU815x9ddaQEfagqUH0B4NZHZcTPxUOuJovs8_FVEuRROt229Wi19CyfUUPiGkXddMpRZxOcVv6dlR-HMqu1AJ9ZsirNYWYITZJFCzAtEViQcButiaEgRrsFE4XTPtI4zymP_1lKUAs1p7TmiaYhjHNbkEEAAwc7QWJe5tzWFxGNlDyh9nWyVxSv3UI8gNPEi7fF2Rwr811D8NhBx4UqN4RiY9vPJ8"/>
                <div class="relative z-10">
                    <span class="material-symbols-outlined text-secondary text-3xl mb-4" data-icon="photo_library">photo_library</span>
                    <h3 class="font-headline text-xl text-primary mb-2">Interactive Gallery</h3>
                    <p class="text-sm text-on-surface-variant">Showcase your pre-wedding story through ultra-high resolution cinematic sliders.</p>
                </div>
            </div>
            <div class="md:col-span-8 flex flex-col md:flex-row gap-6">
                <div class="flex-1 bg-secondary-container/30 backdrop-blur-md rounded-3xl p-8 rose-glow border border-secondary/20 flex flex-col justify-center items-center text-center">
                    <h4 class="font-headline text-2xl text-secondary mb-4">Start Your Journey</h4>
                    <a href="{{ Route::has('register') ? route('register') : '#' }}" class="bg-secondary text-white px-8 py-3 rounded-full font-bold shadow-lg hover:opacity-90 transition-opacity inline-block">Register</a>
                </div>
                <div class="flex-1 bg-primary-container/90 backdrop-blur-md rounded-3xl p-8 emerald-glow border border-primary/20 flex flex-col justify-center items-center text-center">
                    <h4 class="font-headline text-2xl text-on-primary-container mb-4">Welcome Back</h4>
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="bg-primary text-white px-8 py-3 rounded-full font-bold shadow-lg hover:opacity-90 transition-opacity inline-block">Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-32 px-6 relative bg-surface-container-low overflow-hidden" id="gallery">
        <!-- Floating Element -->
        <div class="floating-element top-20 right-[15%] w-32 h-32 opacity-20 transform -rotate-12">
            <img class="w-full h-full object-contain" data-alt="floating petal" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDGc5-mR8LP6qj6FB5TDi6VrkpKxWLv8OkxMfRehS3kZH123285-NuMHLHKn2VH2PweYRc5wem2t26-pLUJpb9sSw7lZbDosKZcZf8MCUIXtAvKmO9Ioam9IFkSzJFbM8UOzx3B5Sj3nGMIcDfO3GyHwmQ7XNes_Izu3bl4CycT3dEe3IdPIQ9W4kqkCXXRtSU4ILc1ilbNnOfudwod2xY1gGomq9um75pu6YAgeJIfhEii3cZEbiz8JBjBVA6UgAW7cFXQA6DDUGg"/>
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="font-headline text-4xl md:text-5xl text-primary mb-4">The Cinematic Gallery</h2>
                <p class="font-body text-on-surface-variant">Moments frozen in digital elegance.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-6 h-auto md:h-[800px]">
                <div class="md:col-span-2 md:row-span-2 relative group overflow-hidden rounded-[2rem] cursor-pointer shadow-xl">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" data-alt="luxury wedding venue with pool" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJNRTB4O6wEfz6hsfXfcPWT6RLT5xJ2_LlBgOwRByKmuwwI4WFmbq7ZYXrnnIHTBji79NxZNfA9vSmJQ241mOQTBBlOFAlzOts-a89aF2HMYuZz5FGmENni-3qyZhwlpLtzKqfzCBiFsjVjbuAecr6BG68rEtPn8iCY2YyB1mTRLJODzk6WnPWUAEZciQk8ms8m5-SxMe7acBjdoY4zNB3xDp7mBnHbHEu7qPLo9GCw_wmaoqziccPN629QFN9AxN3IBpQrqbVaGc"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-10">
                        <span class="text-white font-headline text-3xl mb-2">The Emerald Estate</span>
                        <p class="text-white/80 font-body text-sm leading-relaxed max-w-sm">A breathtaking landscape where architecture meets the ethereal beauty of nature.</p>
                    </div>
                </div>
                <div class="md:col-span-2 md:row-span-1 relative group overflow-hidden rounded-[2rem] cursor-pointer shadow-xl">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" data-alt="romantic couple dancing" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2z6A4pAGH2Ty3c_qITSivSheb49P6biYjDAWBkDRhdoUQq_-AHK319vTfkWiFgoLyiEftq1Cmok23o8mxgXA1UALkTlP4cVoY4M-rom9xNNwxQ8et8thA7rkL7Gr4l7SqkX6QdIxrLkWey6WKHX_kZXlE_O71zSMOoMwCucBZ_7MmWwBUqp2Llg7GO7emao1IbYLkR9pT5rzkPPTl3psyz2uuycjaEtZh8tNUdVpX5L2c4Br5skPtw-qDU0jwOCuv3uS6Oz0meRU"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-secondary/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-white font-headline text-2xl mb-1">A Soft Embrace</span>
                        <p class="text-white/80 font-body text-xs italic">Capturing the quiet whispers of a lifelong promise.</p>
                    </div>
                </div>
                <div class="md:col-span-1 md:row-span-1 relative group overflow-hidden rounded-[2rem] cursor-pointer shadow-xl">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" data-alt="elegant table floral arrangement" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFTRXvDxBiZgDI3gnM_rU7iv1LOUdzr1mBz12hLrZKrn2ihkEAf89viSY5oj2Vam-uDLZK9VjFQReLOqWtGKbZK6-oMOO_4f-QZE9NxZcJs1re3JHIkMmWwA-LeKC3kMM9qYoSrIFDpBMg3ZCHpvIxV0SMEKcQ-qmRbtXG_9B-5i12FR5wtBceqDu5t6EEpzz_v5RvlUCd5Cnjsi5CQZNW5St4wsWuPlPglzAvYPLNQS1jBMfZja7l0zuOG_G5ZSW5QR8rxrZvh7k"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <span class="text-white font-headline text-lg">Blossoming Grace</span>
                    </div>
                </div>
                <div class="md:col-span-1 md:row-span-1 relative group overflow-hidden rounded-[2rem] cursor-pointer shadow-xl">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" data-alt="bride looking through window" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBqeQVzgPg3urTyIUikvyB-Bg0Apc4LculJROyOq6h9AjBBorX_gZ0pvexmgVl4WH9XHf0yjEzxRd4TTF9Dz-jmYmNekcSYbdsTs0_YZJcgvRmMvt5EFEVZ7kdiG-59v1sjvjPVti81M32nRjFfRlaJeGVdcRsGzYpYxDtZcZZW665QuhElcTu92SbXsMecIprdbA6VD5uj_kp-jFtGdQdoUTHWKnjlmSMaoj5SuTSPlvwwKSr5Hhlqkj8RUhu--WdqwwDPyipQ8HA"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <span class="text-white font-headline text-lg">Golden Solace</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-32 px-6 relative overflow-hidden bg-background" id="pricing">
        <!-- Floating Element -->
        <div class="floating-element bottom-20 left-[10%] w-40 h-40 opacity-10 blur-sm">
            <img class="w-full h-full object-contain" data-alt="soft blossom" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzT1teXUycR1G2v18dsaNqaQ6R-GwVt4zpbvZ1h-wj6rdTVDnqlIzH1oQtnIWJwc9PuIn5pywzxIgBaCbEnY9mtLKMwwPQDbzQtI4oSgJ7Qq8_HZLjF_E9HGucbZ5co0NySa68qxxTqOcnwgO6eqjRiJC-0TKnlqAepzTN5D6-RhZQVNRxQQtZa-4mq32z-tQUbFqUql1L_-xDVBihk3Z6YBOWPAm_gsgCHkZPgADDzVeIu6syQJGpTynM-JnyOEwc8qKkXBPdYVE"/>
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="font-headline text-4xl md:text-5xl text-primary mb-4">Investment in Memories</h2>
                <p class="font-body text-on-surface-variant">Choose the perfect tier for your cinematic story.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                <!-- Silver Tier -->
                <div class="glass-morphism rounded-[2.5rem] p-10 border border-outline-variant/20 flex flex-col hover:scale-[1.02] transition-transform duration-300">
                    <div class="mb-8">
                        <h3 class="font-headline text-2xl text-primary mb-2">Silver</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-primary">$199</span>
                            <span class="text-on-surface-variant">/event</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Digital Invitation Link
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            RSVP Management
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Basic Photo Gallery (10 photos)
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant/50">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                            Custom Background Music
                        </li>
                    </ul>
                    <button class="w-full py-4 rounded-full border border-primary text-primary font-bold hover:bg-primary hover:text-white transition-colors">Select Silver</button>
                </div>

                <!-- Platinum Tier (Featured) -->
                <div class="glass-morphism rounded-[2.5rem] p-10 relative rose-gold-glow flex flex-col hover:scale-[1.05] transition-transform duration-300 z-10 bg-white/80">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-secondary text-white px-6 py-1 rounded-full text-xs font-bold tracking-widest uppercase">Most Beloved</div>
                    <div class="mb-8">
                        <h3 class="font-headline text-3xl text-primary mb-2">Platinum</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-bold text-primary">$499</span>
                            <span class="text-on-surface-variant">/event</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <li class="flex items-center gap-3 text-primary font-medium">
                            <span class="material-symbols-outlined text-secondary text-sm">auto_awesome</span>
                            Cinematic 3D Animation
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Premium RSVP Analytics
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Infinite Cinematic Gallery
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Bespoke Music Scoring
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            3D Interactive Guestbook
                        </li>
                    </ul>
                    <button class="w-full py-5 rounded-full jeweled-action text-white font-bold shadow-xl hover:opacity-90 transition-opacity">Elevate to Platinum</button>
                </div>

                <!-- Gold Tier -->
                <div class="glass-morphism rounded-[2.5rem] p-10 border border-outline-variant/20 flex flex-col hover:scale-[1.02] transition-transform duration-300">
                    <div class="mb-8">
                        <h3 class="font-headline text-2xl text-primary mb-2">Gold</h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-primary">$299</span>
                            <span class="text-on-surface-variant">/event</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Premium Visual Theme
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Extended Gallery (30 photos)
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Standard Guestbook
                        </li>
                        <li class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">check_circle</span>
                            Custom Background Music
                        </li>
                    </ul>
                    <button class="w-full py-4 rounded-full border border-primary text-primary font-bold hover:bg-primary hover:text-white transition-colors">Select Gold</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Section -->
    <section class="py-32 bg-surface-container-low relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl relative z-20">
                    <img class="w-full h-full object-cover" data-alt="luxury wedding invitation close up" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCKd3UklOHRD3bZ8kX_Ztwhra-awiCroefrADXE13_2omGY11c3NDaAWcjU0DcCYfEZuzamuO4NaGCQvaiE57F6jp7LcAMOTvR97WPl5HGysdMk4WWAaqhz743d6RyUl7D5YYlRbfr08K8DkiAMsgnPKOO2wTQA-HNcuwWLxzrcLz_qIqNR9RxOEpAXBUJ1VKHvWJnNzatLeTr9Eb3KBfSlUYpyfzUHV5Kt7irO56ThyXyj-INvDARJXPlPSIxdnT_Q3sTOgI968JM"/>
                </div>
                <div class="absolute -bottom-10 -right-10 w-48 h-48 z-30 transform rotate-12 opacity-90">
                    <img class="w-full h-full object-contain" data-alt="3d clay flower" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB2Q_AqI_k9M5goq09YgV3jBZ_v6fLZ61ROZHB0-f3DsdZml-aNKGFkAjFJYY5FYt2zwgrB6syKnsJRpDg-utjCOaymecvLX-7D8xgT-te1tZyTc1tnEme9W_ASyuFPeCiQDO_yaQxwL3muSZdh0ufhldKu7S0btA_zeIPFZlFRdOHzmyfIMfZfzNGn79CK1Ps1RdBzq8bEQHrXbiGET9PtmR6E2_nRIPCgRQ59V0xs-3oI2NS1Z3gCidaMUjhQB7DRJDYtVMt9_6I"/>
                </div>
            </div>
            <div class="space-y-8">
                <h2 class="font-headline text-5xl text-primary leading-tight">Attention to Every <span class="italic">Digital Detail</span></h2>
                <p class="text-on-surface-variant text-lg leading-relaxed">
                    Our platform goes beyond simple links. We provide a bespoke digital atmosphere that mirrors the physical grandeur of your wedding venue. Every interaction is designed to evoke emotion.
                </p>
                <ul class="space-y-6">
                    <li class="flex gap-4 items-start">
                        <span class="material-symbols-outlined text-secondary" data-icon="done_all">done_all</span>
                        <div>
                            <h5 class="font-bold text-primary">Custom Music Score</h5>
                            <p class="text-sm text-on-surface-variant">Background scores that fade in perfectly as guests scroll.</p>
                        </div>
                    </li>
                    <li class="flex gap-4 items-start">
                        <span class="material-symbols-outlined text-secondary" data-icon="done_all">done_all</span>
                        <div>
                            <h5 class="font-bold text-primary">Map Integration</h5>
                            <p class="text-sm text-on-surface-variant">One-tap navigation for guests to find your luxury venue.</p>
                        </div>
                    </li>
                    <li class="flex gap-4 items-start">
                        <span class="material-symbols-outlined text-secondary" data-icon="done_all">done_all</span>
                        <div>
                            <h5 class="font-bold text-primary">Multi-language Support</h5>
                            <p class="text-sm text-on-surface-variant">Speak to your family across borders with localized invites.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-emerald-950 dark:bg-emerald-950 border-t border-rose-900/20">
        <div class="flex flex-col md:flex-row justify-between items-center px-12 py-16 gap-8 w-full max-w-7xl mx-auto">
            <div class="text-lg font-noto-serif text-rose-200">NIKAH YUK!</div>
            <div class="flex gap-8">
                <a class="font-plus-jakarta-sans text-xs tracking-widest uppercase text-rose-300/60 hover:text-rose-100 transition-colors duration-200 opacity-80 hover:opacity-100" href="#">Privacy Policy</a>
                <a class="font-plus-jakarta-sans text-xs tracking-widest uppercase text-rose-300/60 hover:text-rose-100 transition-colors duration-200 opacity-80 hover:opacity-100" href="#">Terms of Service</a>
                <a class="font-plus-jakarta-sans text-xs tracking-widest uppercase text-rose-300/60 hover:text-rose-100 transition-colors duration-200 opacity-80 hover:opacity-100" href="#">Contact Us</a>
            </div>
            <div class="text-rose-200 font-plus-jakarta-sans text-xs tracking-widest uppercase">
                © {{ date('Y') }} NIKAH YUK! Cinematic Invitations. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>