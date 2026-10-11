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
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
<style>
:root{
    --sun:#F2A900; --ocean:#003087; --ink:#071321; --paper:#F4F2EB;
    --white:#FFFFFF; --muted:#66717c; --line:rgba(7,19,33,.15);
    --max:1380px; --header-height:92px;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:"DM Sans",sans-serif;background:var(--paper);color:var(--ink);overflow-x:hidden}
html[lang="ar"] body{font-family:"Cairo",sans-serif}
h1,h2,h3,.brand{font-family:"Manrope",sans-serif}
html[lang="ar"] h1,html[lang="ar"] h2,html[lang="ar"] h3,html[lang="ar"] .brand{font-family:"Cairo",sans-serif}
a{text-decoration:none;color:inherit}
button{font:inherit;cursor:pointer;border:none;background:none}
img{display:block;width:100%;height:auto}
.container{width:min(var(--max),calc(100% - 64px));margin:auto}
.section-pad{padding:100px 0}
.label-tag{font-size:10px;font-weight:800;color:var(--sun);letter-spacing:.23em;text-transform:uppercase;margin-bottom:15px;display:inline-block}
.btn-primary{background:var(--sun);color:var(--ink);padding:16px 28px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;transition:.3s;border:none;cursor:pointer;display:inline-block}
.btn-primary:hover{background:var(--ink);color:var(--sun);transform:translateY(-2px)}
.btn-outline-sun{background:transparent;border:2px solid var(--sun);color:var(--sun);padding:14px 26px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;transition:.3s;cursor:pointer;display:inline-block}
.btn-outline-sun:hover{background:var(--sun);color:var(--ink)}

/* NAV */
.nav{position:fixed;top:0;left:0;width:100%;z-index:1000;background:var(--ink);color:var(--white);border-bottom:1px solid rgba(255,255,255,.1);transition:.3s}
.nav-inner{width:min(var(--max),calc(100% - 64px));height:var(--header-height);margin:auto;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:12px;font-size:26px;font-weight:800;letter-spacing:.08em;color:var(--white)}
.brand-mark{width:30px;height:30px;position:relative;transform:skew(-14deg) rotate(45deg);border:3px solid var(--sun);flex-shrink:0}
.brand-mark:before{content:"";position:absolute;left:6px;top:2px;width:3px;height:20px;background:var(--sun)}
.nav-links{display:flex;gap:32px}
.nav-links a{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;opacity:.72;transition:.2s}
.nav-links a:hover,.nav-links a.active{opacity:1;color:var(--sun)}
.nav-right{display:flex;align-items:center;gap:20px}
.lang-btn{font-size:11px;font-weight:700;color:rgba(255,255,255,.7);letter-spacing:.12em;padding:8px 14px;border:1px solid rgba(255,255,255,.25);transition:.3s}
.lang-btn:hover{background:rgba(255,255,255,.1);color:var(--sun)}
.quote-btn{background:var(--sun);color:var(--ink);padding:14px 19px;font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;transition:.3s}
.quote-btn:hover{background:white;transform:translateY(-2px)}
main{min-height:calc(100vh - var(--header-height));padding-top:var(--header-height)}

/* ALERT MESSAGES */
.flash-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:14px 20px;border-radius:4px;margin-bottom:20px;font-size:14px}
.flash-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:14px 20px;border-radius:4px;margin-bottom:20px;font-size:14px}

/* FOOTER */
footer{padding:80px 0 30px;background:var(--ink);color:var(--white)}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:50px;margin-bottom:30px}
.footer-brand p{font-size:14px;color:rgba(255,255,255,.5);margin-top:12px;line-height:1.7;max-width:280px}
.footer-col h4{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.18em;color:var(--sun);margin-bottom:16px}
.footer-col a{display:block;font-size:13px;color:rgba(255,255,255,.55);margin-bottom:8px;transition:.2s}
.footer-col a:hover{color:var(--sun)}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;font-size:11px;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.12em}

@media(max-width:900px){
    .nav-links{display:none}
    .footer-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:600px){
    .footer-grid{grid-template-columns:1fr}
}
</style>
@stack('styles')
</head>
<body>

<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark"></span>BETA
        </a>
        <nav class="nav-links">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>{{ __('nav.home') }}</a>
            <a href="{{ route('services') }}" @class(['active' => request()->routeIs('services')])>{{ __('nav.services') }}</a>
            <a href="{{ route('products') }}" @class(['active' => request()->routeIs('products')])>{{ __('nav.products') }}</a>
            <a href="{{ route('announcements') }}" @class(['active' => request()->routeIs('announcements*')])>{{ __('nav.news') }}</a>
            <a href="{{ route('careers') }}" @class(['active' => request()->routeIs('careers')])>{{ __('nav.careers') }}</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>{{ __('nav.contact') }}</a>
        </nav>
        <div class="nav-right">
            <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="lang-btn">
                {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
            </a>
            <a class="quote-btn" href="{{ route('contact') }}">{{ __('nav.request_quote') }}</a>
        </div>
    </div>
</header>

<main>
    @if(session('success'))
        <div class="container" style="padding-top:20px">
            <div class="flash-success">✓ {{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="container" style="padding-top:20px">
            <div class="flash-error">{{ $errors->first() }}</div>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a>
                <p>{{ __('footer.tagline') }}</p>
            </div>
            <div class="footer-col">
                <h4>{{ __('footer.services') }}</h4>
                <a href="{{ route('services') }}">{{ __('nav.services') }}</a>
                <a href="{{ route('products') }}">{{ __('nav.products') }}</a>
                <a href="{{ route('contact') }}">{{ __('nav.request_quote') }}</a>
            </div>
            <div class="footer-col">
                <h4>{{ __('footer.company') }}</h4>
                <a href="{{ route('announcements') }}">{{ __('nav.news') }}</a>
                <a href="{{ route('careers') }}">{{ __('nav.careers') }}</a>
                <a href="{{ route('contact') }}">{{ __('nav.contact') }}</a>
            </div>
            <div class="footer-col">
                <h4>{{ __('footer.reach_us') }}</h4>
                <a href="mailto:sales@beta-industrial.com">sales@beta-industrial.com</a>
                <a href="tel:+201001234567">+20 100 123 4567</a>
                <a href="tel:+966501234567">+966 50 123 4567</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} BETA Industrial</span>
            <span>{{ __('footer.rights') }}</span>
        </div>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        if(typeof gsap !== 'undefined') {
            gsap.from('.gsap-hero', { y: 50, opacity: 0, duration: 1, ease: 'power3.out' });
            if(typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                gsap.utils.toArray('.gsap-fade-up').forEach(el => {
                    gsap.from(el, { y: 40, opacity: 0, duration: 0.8, ease: 'power2.out',
                        scrollTrigger: { trigger: el, start: 'top 85%' }
                    });
                });
            }
        }
    });
</script>
@stack('scripts')
</body>
</html>
