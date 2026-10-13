<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BETA Industrial — Engineering The Future')</title>
    <meta name="description" content="@yield('meta_description', 'BETA Industrial — Engineering, manufacturing and installation of heavy steel structures in Egypt and Saudi Arabia.')">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sun: '#F2A900',
                        ocean: '#003087',
                        midnight: '#00205B',
                        sky: '#307FE2',
                        ink: '#071321',
                        paper: '#F4F2EB',
                        white: '#FFFFFF',
                        muted: '#66717c',
                        line: 'rgba(7, 19, 33, 0.15)',
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Manrope', 'sans-serif'],
                        arabic: ['Cairo', 'sans-serif'],
                    },
                    maxWidth: {
                        'container': '1380px',
                    },
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: "DM Sans", sans-serif;
            background: var(--paper);
            color: var(--ink);
            overflow-x: hidden;
        }
        html[dir="rtl"] body { font-family: "Cairo", sans-serif; }
        h1, h2, h3, h4, .brand { font-family: "Manrope", sans-serif; }
        html[dir="rtl"] h1, html[dir="rtl"] h2, html[dir="rtl"] h3, html[dir="rtl"] h4, html[dir="rtl"] .brand { font-family: "Cairo", sans-serif; }
        a { text-decoration: none; color: inherit; }
        button { font: inherit; cursor: pointer; border: none; background: none; }
        img { display: block; width: 100%; height: auto; }
        
        /* GSAP Animation Classes */
        .gsap-hero > * { opacity: 0; transform: translateY(30px); }
        .gsap-fade-up { opacity: 0; transform: translateY(40px); }
        /* If GSAP fails to load, never leave content hidden */
        .no-gsap .gsap-hero > *,
        .no-gsap .gsap-fade-up { opacity: 1 !important; transform: none !important; }
        
        /* Utility Classes */
        .container { width: min(1380px, calc(100% - 64px)); margin: auto; }
        .section-pad { padding: 100px 0; }
        .label-tag { 
            font-size: 10px; font-weight: 800; color: var(--sun); 
            letter-spacing: .23em; text-transform: uppercase; 
            margin-bottom: 15px; display: inline-block; 
        }
        .label-tag-ocean { color: var(--ocean); }
        .label-tag-ink { color: var(--ink); }
        
        .btn-primary { 
            background: var(--sun); color: var(--ink); 
            padding: 16px 28px; font-size: 11px; font-weight: 800; 
            text-transform: uppercase; letter-spacing: .1em; 
            transition: .3s; display: inline-block; 
        }
        .btn-primary:hover { background: var(--ink); color: var(--sun); transform: translateY(-2px); }
        
        .btn-outline { 
            background: transparent; border: 2px solid var(--sun); color: var(--sun); 
            padding: 14px 26px; font-size: 11px; font-weight: 800; 
            text-transform: uppercase; letter-spacing: .1em; 
            transition: .3s; display: inline-block; 
        }
        .btn-outline:hover { background: var(--sun); color: var(--ink); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">

<header class="nav fixed top-0 left-0 w-full z-[1000] bg-ink text-white border-b border-white/10 transition-all duration-300">
    <div class="nav-inner max-w-[1380px] h-[92px] mx-auto px-[32px] flex items-center justify-between">
        <a class="brand flex items-center gap-3 text-[26px] font-extrabold tracking-[.08em]" href="{{ route('home') }}">
            <span class="brand-mark relative w-[30px] h-[30px] -skew-x-6 rotate-45 border-3 border-sun flex-shrink-0" aria-hidden="true">
                <span class="absolute left-[6px] top-[2px] w-[3px] h-[20px] bg-sun"></span>
            </span>
            BETA
        </a>
        
        <nav class="nav-links hidden md:flex gap-8" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('home') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                {{ __('app.nav.home') }}
            </a>
            <a href="{{ route('services') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('services') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                {{ __('app.nav.services') }}
            </a>
            <a href="{{ route('products') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('products') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                {{ __('app.nav.products') }}
            </a>
            <a href="{{ route('announcements') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('announcements*', 'insights*') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                {{ __('app.nav.news') }}
            </a>
            <a href="{{ route('careers') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('careers') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                {{ __('app.nav.careers') }}
            </a>
            <a href="{{ route('about') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('about') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                About
            </a>
            <a href="{{ route('projects') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('projects') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                Projects
            </a>
            <a href="{{ route('contact') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-all duration-200 {{ request()->routeIs('contact') ? 'opacity-100 text-sun' : 'opacity-72 hover:opacity-100 hover:text-sun' }}">
                {{ __('app.nav.contact') }}
            </a>
        </nav>

        <div class="nav-right flex items-center gap-4">
            <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" 
               class="lang-btn text-[11px] font-bold text-white/70 tracking-[.12em] px-3 py-2 border border-white/25 hover:bg-white/10 hover:text-sun transition-all duration-300">
                {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
            </a>
            <a class="quote-btn bg-sun text-ink px-4 py-3 text-[10px] font-extrabold tracking-[.12em] uppercase hover:bg-white hover:-translate-y-0.5 transition-all duration-300" href="{{ route('contact') }}">
                {{ __('app.nav.request_quote') }}
            </a>
            <button type="button" class="md:hidden text-white text-2xl leading-none" id="mobile-menu-btn"
                    aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-nav">
                <span id="menu-icon">☰</span>
            </button>
        </div>
    </div>

    <!-- Mobile navigation (below 768px) -->
    <nav id="mobile-nav" class="hidden md:hidden bg-ink border-t border-white/10" aria-label="Mobile navigation">
        <div class="max-w-[1380px] mx-auto px-8 py-5 flex flex-col gap-4">
            <a href="{{ route('home') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('home') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">{{ __('app.nav.home') }}</a>
            <a href="{{ route('services') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('services') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">{{ __('app.nav.services') }}</a>
            <a href="{{ route('products') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('products') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">{{ __('app.nav.products') }}</a>
            <a href="{{ route('announcements') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('announcements*', 'insights*') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">{{ __('app.nav.news') }}</a>
            <a href="{{ route('careers') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('careers') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">{{ __('app.nav.careers') }}</a>
            <a href="{{ route('about') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('about') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">About</a>
            <a href="{{ route('projects') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('projects') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">Projects</a>
            <a href="{{ route('contact') }}" class="text-[11px] font-bold tracking-[.14em] uppercase transition-colors {{ request()->routeIs('contact') ? 'text-sun' : 'text-white/75 hover:text-sun' }}">{{ __('app.nav.contact') }}</a>
        </div>
    </nav>
</header>

<main class="flex-1 pt-[92px]">
    @if(session('success'))
        <div class="container pt-5">
            <div class="bg-green-50 text-green-700 border border-green-200 px-5 py-4 rounded-lg text-sm">
                ✓ {{ session('success') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="container pt-5">
            <div class="bg-red-50 text-red-700 border border-red-200 px-5 py-4 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-ink text-white">
    <div class="container py-20 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 border-b border-white/10 pb-12 mb-8">
            <div class="lg:col-span-1">
                <a class="brand flex items-center gap-3 text-[26px] font-extrabold tracking-[.08em] mb-4" href="{{ route('home') }}">
                    <span class="brand-mark relative w-[30px] h-[30px] -skew-x-6 rotate-45 border-3 border-sun flex-shrink-0" aria-hidden="true">
                        <span class="absolute left-[6px] top-[2px] w-[3px] h-[20px] bg-sun"></span>
                    </span>
                    BETA
                </a>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs">{{ __('app.footer.tagline') }}</p>
            </div>
            
            <div>
                <h4 class="text-[10px] font-extrabold uppercase tracking-[.18em] text-sun mb-4">{{ __('app.footer.services') }}</h4>
                <nav class="space-y-2">
                    <a href="{{ route('services') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.services') }}</a>
                    <a href="{{ route('products') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.products') }}</a>
                    <a href="{{ route('contact') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.request_quote') }}</a>
                </nav>
            </div>
            
            <div>
                <h4 class="text-[10px] font-extrabold uppercase tracking-[.18em] text-sun mb-4">{{ __('app.footer.company') }}</h4>
                <nav class="space-y-2">
                    <a href="{{ route('announcements') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.news') }}</a>
                    <a href="{{ route('careers') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.careers') }}</a>
                    <a href="{{ route('about') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">About</a>
                    <a href="{{ route('contact') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.contact') }}</a>
                </nav>
            </div>
            
            <div>
                <h4 class="text-[10px] font-extrabold uppercase tracking-[.18em] text-sun mb-4">{{ __('app.footer.reach_us') }}</h4>
                <address class="not-italic space-y-2 text-sm text-white/55">
                    <a href="mailto:sales@beta-industrial.com" class="block hover:text-sun transition-colors">sales@beta-industrial.com</a>
                    <a href="tel:+201001234567" class="block hover:text-sun transition-colors">+20 100 123 4567</a>
                    <a href="tel:+966501234567" class="block hover:text-sun transition-colors">+966 50 123 4567</a>
                </address>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[11px] text-white/30 uppercase tracking-[.12em]">
            <span>© {{ date('Y') }} BETA Industrial</span>
            <span>{{ __('app.footer.rights') }}</span>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof gsap !== 'undefined') {
            gsap.from('.gsap-hero > *', { y: 30, opacity: 0, duration: 0.8, stagger: 0.1, ease: 'power3.out' });
            if (typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                gsap.utils.toArray('.gsap-fade-up').forEach(el => {
                    gsap.from(el, { y: 40, opacity: 0, duration: 0.8, ease: 'power2.out',
                        scrollTrigger: { trigger: el, start: 'top 85%' }
                    });
                });
            }
        } else {
            // CDN failed: reveal everything that the animation CSS had hidden.
            document.documentElement.classList.add('no-gsap');
        }

        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileNav = document.getElementById('mobile-nav');
        const menuIcon = document.getElementById('menu-icon');

        if (menuBtn && mobileNav) {
            menuBtn.addEventListener('click', () => {
                const isOpen = !mobileNav.classList.toggle('hidden');
                menuBtn.setAttribute('aria-expanded', String(isOpen));
                if (menuIcon) menuIcon.textContent = isOpen ? '×' : '☰';
            });

            mobileNav.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileNav.classList.add('hidden');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    if (menuIcon) menuIcon.textContent = '☰';
                });
            });
        }
    });
</script>
@stack('scripts')
</body>
</html>