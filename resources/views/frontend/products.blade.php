<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('products.seo_title') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<style>
:root { --sun: #F2A900; --ocean: #003087; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); overflow-x: hidden; }
html[dir="rtl"] body { font-family: "Cairo", sans-serif; }
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] h5,html[dir="rtl"] h6,
html[dir="rtl"] p,html[dir="rtl"] span,html[dir="rtl"] div{letter-spacing:0 !important}
html[dir="rtl"] h1,html[dir="rtl"] h2{line-height:1.35}
html[dir="rtl"] p{line-height:1.8}
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] .brand{font-family:"Cairo",sans-serif}
h1, h2, h3, .brand { font-family: "Manrope", sans-serif; }
a { text-decoration: none; color: inherit; } button { font: inherit; cursor: pointer; border: none; background: none; }
img { display: block; width: 100%; height: auto; }

/* NAV */
.nav { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: var(--ink); color: var(--white); border-bottom: 1px solid rgba(255,255,255,.1); }
.nav-inner { width: min(var(--max), calc(100% - 64px)); height: var(--header-height); margin: auto; display: flex; align-items: center; justify-content: space-between; }
.brand { display: flex; align-items: center; gap: 12px; font-size: 26px; font-weight: 800; letter-spacing: .08em; }
.brand-mark { width: 30px; height: 30px; position: relative; transform: skew(-14deg) rotate(45deg); border: 3px solid var(--sun); }
.brand-mark:before { content: ""; position: absolute; left: 6px; top: 2px; width: 3px; height: 20px; background: var(--sun); }
.nav-links { display: flex; gap: 32px; } .nav-links a { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .72; }
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
.lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; } .lang-switch .active-lang { opacity: 1; color: var(--sun); }

main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
.container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
.section-pad { padding: 100px 0; }
.inner-hero { background: var(--ink); color: var(--white); padding: 120px 0 100px; text-align: center; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
.prod-card { background: var(--white); border: 1px solid var(--line); border-radius: 12px; overflow: hidden; transition: transform 0.35s var(--ease), box-shadow 0.35s var(--ease); display: flex; flex-direction: column; height: 100%; }
.prod-card:hover { transform: translateY(-8px); border-color: var(--sun); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
.prod-img { height: 220px; overflow: hidden; background: #1a2635; display:flex; align-items:center; justify-content:center; position:relative; }
.prod-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
.prod-card:hover .prod-img img { transform: scale(1.05); }
.prod-card h3 { font-size: 20px; color: var(--ink); margin: 0 22px 10px 22px; line-height: 1.3; }
.prod-card p { font-size: 14px; color: var(--muted); margin: 0 22px 18px 22px; line-height: 1.7; flex-grow: 1; }
.btn-spec { display:inline-block; font-size: 11px; font-weight: 800; color: var(--ocean); text-transform: uppercase; letter-spacing: 0.1em; padding: 8px 14px; border: 1px solid var(--ocean); border-radius: 6px; margin: 0 22px 22px 22px; transition: 0.25s; }
.btn-spec:hover { background: var(--ocean); color: var(--white); }

footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .products-grid { grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
}
@media (max-width: 640px) {
    .products-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>
<x-header />

<main>
    <section class="inner-hero">
        <div class="container">
            <div class="label">{{ __('app.nav.products') }}</div>
            <h1>{!! __('products.hero_heading') !!}</h1>
        </div>
    </section>

    <section class="section-pad">
        <div class="container">
            <div class="products-grid">
                @forelse ($products as $product)
                <a class="prod-card" href="{{ route('products.show', $product->slug) }}">
                    <div class="prod-img">
                        @php $cover = $product->images->first(); @endphp
                        @if ($cover)
                            <img src="{{ asset($cover->image_path) }}" alt="{{ $product->getTranslated('title') }}">
                        @else
                            <span style="color:var(--sun);font-size:50px;font-weight:800;opacity:0.5;">β</span>
                        @endif
                    </div>
                    <h3>{{ $product->getTranslated('title') }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($product->getTranslated('description'), 130) }}</p>
                    <span class="btn-spec">{{ __('products.view_details') }}</span>
                </a>
                @empty
                <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:60px 0;font-size:16px;">
                    {{ __('products.empty') }}
                </p>
                @endforelse
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} BETA</span>
            <span>{{ __('app.footer.tagline') }}</span>
        </div>
    </div>
</footer>
<x-success-modal />
</body>
</html>
