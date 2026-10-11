<?php
$blade = <<<'BLADE'
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — {{ app()->getLocale() === 'ar' ? 'كتالوج المنتجات' : 'Product Catalog' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<style>
:root { --sun: #F2A900; --ocean: #003087; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); overflow-x: hidden; }
html[dir="rtl"] body { font-family: "Cairo", sans-serif; }
h1, h2, h3, .brand { font-family: "Manrope", sans-serif; }
html[dir="rtl"] h1, html[dir="rtl"] h2, html[dir="rtl"] h3, html[dir="rtl"] .brand { font-family: "Cairo", sans-serif; }
a { text-decoration: none; color: inherit; } button { font: inherit; cursor: pointer; border: none; background: none; }
img { display: block; width: 100%; height: auto; }
.nav { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: var(--ink); color: var(--white); border-bottom: 1px solid rgba(255,255,255,.1); }
.nav-inner { width: min(var(--max), calc(100% - 64px)); height: var(--header-height); margin: auto; display: flex; align-items: center; justify-content: space-between; }
.brand { display: flex; align-items: center; gap: 12px; font-size: 26px; font-weight: 800; letter-spacing: .08em; }
.brand-mark { width: 30px; height: 30px; position: relative; transform: skew(-14deg) rotate(45deg); border: 3px solid var(--sun); }
.brand-mark:before { content: ""; position: absolute; left: 6px; top: 2px; width: 3px; height: 20px; background: var(--sun); }
.nav-links { display: flex; gap: 32px; } .nav-links a { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .72; }
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
.lang-switch { display:flex; align-items:center; gap:6px; font-size: 11px; font-weight: 700; }
.lang-switch a { color: var(--white); opacity: 0.7; text-decoration:none; }
.lang-switch a.active-lang { opacity: 1; color: var(--sun); }
.quote { background: var(--sun); color: var(--ink); padding: 14px 19px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
.container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
.section-pad { padding: 100px 0; }
.inner-hero { background: var(--ink); color: var(--white); padding: 120px 0 100px; text-align: center; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
.prod-card { background: var(--white); border: 1px solid var(--line); padding: 30px; transition: 0.4s; cursor: pointer; display: flex; flex-direction: column; }
.prod-card:hover { transform: translateY(-8px); border-color: var(--ocean); box-shadow: 0 15px 35px rgba(0,0,0,0.06); }
.prod-img { height: 200px; overflow: hidden; margin-bottom: 20px; background: #1a2635; display:flex; align-items:center; justify-content:center; }
.prod-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
.prod-card:hover .prod-img img { transform: scale(1.05); }
.prod-card h3 { font-size: 22px; color: var(--ink); margin-bottom: 10px; }
.prod-card p { font-size: 14px; color: var(--muted); margin-bottom: 20px; flex-grow:1; }
.btn-spec { font-size: 11px; font-weight: 800; color: var(--ocean); text-transform: uppercase; letter-spacing: 0.1em; }
.drawer-overlay { position: fixed; inset: 0; background: rgba(7, 19, 33, 0.7); backdrop-filter: blur(4px); z-index: 2000; opacity: 0; pointer-events: none; transition: 0.4s; }
.drawer-overlay.active { opacity: 1; pointer-events: all; }
.drawer { position: fixed; top: 0; right: 0; width: min(600px, 100%); height: 100vh; background: var(--white); z-index: 2001; transform: translateX(100%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); padding: 50px; overflow-y: auto; display: flex; flex-direction: column; justify-content: space-between; }
html[dir="rtl"] .drawer { right: auto; left: 0; transform: translateX(-100%); }
.drawer-overlay.active .drawer { transform: translateX(0); }
.drawer-close { font-size: 30px; cursor: pointer; align-self: flex-end; margin-bottom: 20px; }
.alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 14px 20px; margin: 20px 0; border-radius: 4px; }
footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }
</style>
</head>
<body>
<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a>
        <nav class="nav-links">
            <a href="{{ route('home') }}">{{ __('app.nav.home') }}</a>
            <a href="{{ route('services') }}">{{ __('app.nav.services') }}</a>
            <a href="{{ route('products') }}" class="active">{{ __('app.nav.products') }}</a>
            <a href="{{ route('contact') }}">{{ __('app.nav.contact') }}</a>
        </nav>
        <div style="display:flex; align-items:center; gap:20px;">
            <span class="lang-switch">
                <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active-lang' : '' }}">EN</a>
                <span style="color:#fff;opacity:0.4;">|</span>
                <a href="{{ route('locale.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'active-lang' : '' }}">عربي</a>
            </span>
            <a class="quote" href="{{ route('contact') }}">{{ __('app.nav.request_quote') }}</a>
        </div>
    </div>
</header>

<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label">{{ __('app.nav.products') }}</div>
            <h1>{!! app()->getLocale() === 'ar' ? 'خط الإنتاج<br>الهيكلي والفولاذي.' : 'Structural &amp; Fabricated<br>Steel Line.' !!}</h1>
        </div>
    </section>

    @if (session('success'))
        <div class="container"><div class="alert-success">{{ session('success') }}</div></div>
    @endif

    <section class="section-pad">
        <div class="container">
            <div class="products-grid">
                @forelse ($products as $product)
                    <div class="prod-card gsap-fade-up" onclick="openDrawer({{ $product->id }})">
                        <div class="prod-img">
                            @php $cover = $product->attachments->where('scan_status', 'passed')->first(); @endphp
                            @if ($cover)
                                <img src="{{ Storage::url($cover->file_path) }}" alt="{{ $product->getTranslated('title') }}">
                            @else
                                <span style="color:var(--sun);font-size:50px;font-weight:800;opacity:0.5;">β</span>
                            @endif
                        </div>
                        <h3>{{ $product->getTranslated('title') }}</h3>
                        <p>{{ Str::limit($product->getTranslated('description'), 130) }}</p>
                        <span class="btn-spec">
                            {{ app()->getLocale() === 'ar' ? 'عرض المواصفات التقنية ←' : 'View Technical Specs →' }}
                        </span>
                    </div>
                @empty
                    <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:60px 0;font-size:16px;">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد منتجات متاحة حاليًا.' : 'No products available at the moment.' }}
                    </p>
                @endforelse
            </div>
        </div>
    </section>
</main>

<div class="drawer-overlay" id="drawerOverlay" onclick="if(event.target===this)closeDrawer()">
    <div class="drawer">
        <div>
            <div class="drawer-close" onclick="closeDrawer()">&times;</div>
            <div id="drawerBody"></div>
        </div>
        <div style="margin-top:30px;">
            <a href="{{ route('contact') }}" style="display:block;text-align:center;background:var(--ink);color:var(--sun);padding:16px;font-weight:800;text-transform:uppercase;">
                {{ app()->getLocale() === 'ar' ? 'طلب عرض سعر للمواصفة' : 'Request Spec Quote' }}
            </a>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} BETA</span>
            <span>{{ __('app.footer.tagline') }}</span>
        </div>
    </div>
</footer>

<script>
    const locale = @json(app()->getLocale());
    const prodSpecs = {
        @foreach ($products as $product)
        {{ $product->id }}: {
            title: @json($product->getTranslated('title')),
            desc:  @json($product->getTranslated('description')),
            specs: @json($product->specifications ?? (object)[])
        }@if (!$loop->last),@endif
        @endforeach
    };

    function openDrawer(id) {
        const item = prodSpecs[id];
        if (!item) return;
        let rows = '';
        if (item.specs && typeof item.specs === 'object') {
            Object.entries(item.specs).forEach(([k, v]) => {
                const val = typeof v === 'object' ? (v[locale] || v['en'] || '') : String(v);
                rows += `<tr style="border-bottom:1px solid var(--line);"><td style="padding:12px 0;font-weight:bold;">${k}</td><td style="padding:12px 0;color:var(--muted);">${val}</td></tr>`;
            });
        }
        document.getElementById('drawerBody').innerHTML = `
            <div style="font-size:10px;font-weight:800;color:var(--sun);text-transform:uppercase;margin-bottom:10px;">
                ${locale === 'ar' ? 'ورقة المواصفات التقنية' : 'Technical Specification Sheet'}
            </div>
            <h2 style="font-size:28px;margin-bottom:16px;line-height:1.2;">${item.title}</h2>
            <p style="color:var(--muted);margin-bottom:28px;line-height:1.7;">${item.desc}</p>
            ${rows ? `<table style="width:100%;border-collapse:collapse;">${rows}</table>` : ''}
        `;
        document.getElementById('drawerOverlay').classList.add('active');
    }
    function closeDrawer() { document.getElementById('drawerOverlay').classList.remove('active'); }

    gsap.registerPlugin(ScrollTrigger);
    document.addEventListener('DOMContentLoaded', () => {
        gsap.fromTo('.gsap-hero > *', {y:30,opacity:0}, {y:0,opacity:1,duration:0.8,stagger:0.1});
        document.querySelectorAll('.gsap-fade-up').forEach(el => {
            gsap.fromTo(el, {y:40,opacity:0}, {y:0,opacity:1,duration:0.8,scrollTrigger:{trigger:el,start:'top 85%'}});
        });
    });
</script>
</body>
</html>
BLADE;

file_put_contents('c:/xampp/htdocs/Beta/resources/views/frontend/products.blade.php', $blade);
echo "Written: " . strlen($blade) . " bytes\n";
