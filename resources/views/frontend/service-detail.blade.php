<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BETA — {{ $service->getTranslated('title') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        :root { --sun: #F2A900; --ocean: #00205B; --sky: #307FE2; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); overflow-x: hidden; line-height: 1.6; }
        html[dir="rtl"] body { font-family: "Cairo", sans-serif; }
        h1, h2, h3, h4, .brand { font-family: "Manrope", sans-serif; }
        html[dir="rtl"] h1, html[dir="rtl"] h2, html[dir="rtl"] h3, html[dir="rtl"] .brand { font-family: "Cairo", sans-serif; }
        a { text-decoration: none; color: inherit; } button { font: inherit; cursor: pointer; border: none; background: none; }
        img { display: block; width: 100%; height: auto; }

        .nav { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: var(--ink); color: var(--white); border-bottom: 1px solid rgba(255,255,255,.1); }
        .nav-inner { width: min(var(--max), calc(100% - 64px)); height: var(--header-height); margin: auto; display: flex; align-items: center; justify-content: space-between; }
        .brand { display: flex; align-items: center; gap: 12px; font-size: 26px; font-weight: 800; letter-spacing: .08em; }
        .brand-mark { width: 30px; height: 30px; position: relative; transform: skew(-14deg) rotate(45deg); border: 3px solid var(--sun); }
        .brand-mark:before { content: ""; position: absolute; left: 6px; top: 2px; width: 3px; height: 20px; background: var(--sun); }
        .nav-links { display: flex; gap: 32px; } .nav-links a { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .72; transition: .25s; }
        .nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; } .lang-switch .active-lang { opacity: 1; color: var(--sun); }
        .quote { background: var(--sun); color: var(--ink); padding: 14px 19px; font-size: 10px; font-weight: 800; text-transform: uppercase; }

        main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
        .container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
        .section-pad { padding: 100px 0; }
        .inner-hero { background: var(--ocean); color: var(--white); padding: 120px 0 100px; text-align: center; }
        .inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
        .label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }

        .service-hero { padding: 80px 0; text-align: center; }
        .service-hero h1 { font-size: clamp(40px, 6vw, 60px); margin-bottom: 20px; }
        .service-hero p { max-width: 600px; margin: 0 auto 30px; color: var(--muted); font-size: 18px; }
        .service-badges { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; margin-top: 40px; }
        .service-badge { background: var(--ink); color: var(--sun); font-size: 11px; font-weight: 800; padding: 8px 16px; text-transform: uppercase; letter-spacing: .1em; }

        .specs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; }
        .spec-card { background: var(--white); border: 1px solid var(--line); padding: 30px; transition: 0.3s; }
        .spec-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .spec-card h4 { font-size: 18px; color: var(--ink); margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--line); }
        .spec-card p { color: var(--muted); font-size: 14px; }

        .cta { text-align: center; margin-top: 50px; }
        .cta .btn-primary { padding: 16px 32px; font-size: 11px; font-weight: 800; }

        footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
        .footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
        .footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }
        @media(max-width:768px){ }
    </style>
</head>
<body>

<x-header />

<main>
    <section class="service-hero">
        <div class="container">
            <h1 class="font-display text-[clamp(40px,6vw,60px)] mb-2">{{ $service->getTranslated('title') }}</h1>
            <p>{{ $service->getTranslated('description') ?? 'No description available.' }}</p>
            <div class="service-badges">
                @foreach ($service->title as $lang => $title)
                    <span class="service-badge">{{ ucfirst($lang) }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-pad">
        <div class="container">
            <div class="specs-grid">
                @foreach ($service->specifications as $key => $value)
                    <div class="spec-card">
                        <h4>{{ ucfirst($key) }}</h4>
                        <p>{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span>{!! __('app.footer.tagline') !!}</span></div>
    </div>
</footer>

<x-success-modal />
</body>
</html>