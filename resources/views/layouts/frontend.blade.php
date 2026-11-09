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
    <link rel="preload" as="font" type="font/woff2" crossorigin="anonymous" href="https://fonts.gstatic.com/s/dmsans/v17/S6iyxHM7mQe2DLoOaUWtN6cAwmOf.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin="anonymous" href="https://fonts.gstatic.com/s/manrope/v25/h8QIQ9c-bxvJyJQ5u0nNE6RobXCl.woff2">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (window.tailwind) window.tailwind.config = {
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
                        muted: '#5b6570',
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
        :root {
            --sun: #F2A900;
            --ocean: #003087;
            --midnight: #00205B;
            --sky: #307FE2;
            --ink: #071321;
            --paper: #F4F2EB;
            --white: #FFFFFF;
            --muted: #5b6570;
            --line: rgba(7, 19, 33, 0.15);
            --max: 1380px;
            --header-height: 92px;
        }
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
        
        /*
         * GSAP Animation Classes.
         * Content stays visible by default; GSAP's `from()` animations provide
         * the entrance effect. This keeps the page usable if a CDN is blocked.
         */
        .gsap-hero > *,
        .gsap-fade-up { opacity: 1; transform: none; }
        .no-gsap .gsap-hero > *,
        .no-gsap .gsap-fade-up { opacity: 1 !important; transform: none !important; }
        
        /* Utility Classes */
        /* `body` prefix keeps this above Tailwind CDN's generic `.container` rule. */
        body .container { width: min(1380px, calc(100% - 64px)); max-width: none; margin-inline: auto; }
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

<x-header />

<main class="flex-1 pt-[92px]">
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
                    <a href="{{ route('announcements.index') }}" class="block text-sm text-white/55 hover:text-sun transition-colors">{{ __('app.nav.news') }}</a>
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
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[11px] text-white/60 uppercase tracking-[.12em]">
            <span>© {{ date('Y') }} BETA Industrial</span>
            <span>{{ __('app.footer.rights') }}</span>
        </div>
    </div>
</footer>

<x-success-modal />
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

        // Mobile menu is provided by the shared <x-header /> component.
    });
</script>
@stack('scripts')
</body>
</html>