<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('services.seo_title') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<style>
:root { --sun: #F2A900; --ocean: #003087; --midnight: #00205B; --sky: #307FE2; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.12); --max: 1380px; --header-height: 92px; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); overflow-x: hidden; }
html[dir="rtl"] body { font-family: "Cairo", sans-serif; }
h1, h2, h3, .brand { font-family: "Manrope", sans-serif; }
html[dir="rtl"] h1, html[dir="rtl"] h2, html[dir="rtl"] h3, html[dir="rtl"] .brand { font-family: "Cairo", sans-serif; }
a { text-decoration: none; color: inherit; } button { font: inherit; cursor: pointer; border: none; background: none; }

.nav { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: var(--ink); color: var(--white); border-bottom: 1px solid rgba(255,255,255,.1); }
.nav-inner { width: min(var(--max), calc(100% - 64px)); height: var(--header-height); margin: auto; display: flex; align-items: center; justify-content: space-between; }
.brand { display: flex; align-items: center; gap: 12px; font-size: 26px; font-weight: 800; letter-spacing: .08em; }
.brand-mark { width: 30px; height: 30px; position: relative; transform: skew(-14deg) rotate(45deg); border: 3px solid var(--sun); }
.brand-mark:before { content: ""; position: absolute; left: 6px; top: 2px; width: 3px; height: 20px; background: var(--sun); }
.nav-links { display: flex; gap: 32px; } .nav-links a { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .72; }
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
.lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; } .lang-switch .active-lang { opacity: 1; color: var(--sun); }
.quote { background: var(--sun); color: var(--ink); padding: 14px 19px; font-size: 10px; font-weight: 800; text-transform: uppercase; }

main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
.container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
.section-pad { padding: 100px 0; }
.inner-hero { background: var(--midnight); color: var(--white); padding: 120px 0 100px; text-align: center; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }

.journey-step { display: flex; gap: 40px; margin-bottom: 40px; }
.step-num { width: 60px; height: 60px; background: var(--ocean); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; flex-shrink: 0; }
.step-body { background: var(--white); padding: 30px; border: 1px solid var(--line); flex-grow: 1; }

/* Dynamic Services Modern Card Grid System */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 32px;
}
.service-card {
    position: relative;
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 40px 32px;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(7, 19, 33, 0.03);
}
.service-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--sun);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.35s ease;
}
html[dir="rtl"] .service-card::before {
    transform-origin: right;
}
.service-card:hover {
    transform: translateY(-6px);
    border-color: rgba(242, 169, 0, 0.5);
    box-shadow: 0 20px 40px rgba(7, 19, 33, 0.08);
}
.service-card:hover::before {
    transform: scaleX(1);
}
.service-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.service-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: rgba(242, 169, 0, 0.12);
    color: var(--midnight);
    display: flex;
    align-items: center;
    justify-content: center;
}
.service-icon svg {
    width: 28px;
    height: 28px;
}
.service-card-num {
    font-family: "Manrope", sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: var(--sun);
    letter-spacing: 0.1em;
    opacity: 0.85;
}
.service-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 14px;
    line-height: 1.3;
}
.service-desc {
    font-size: 15px;
    line-height: 1.65;
    color: var(--muted);
    flex-grow: 1;
}

footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }
</style>
</head>
<body>
<x-header />

<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label">{!! __('services.s.label') !!}</div>
            <h1>{!! __('services.s.title') !!}</h1>
        </div>
    </section>

    <!-- SECTION 1: THE JOURNEY -->
    <section class="section-pad">
        <div class="container">
            <div class="label" style="color:var(--ocean)">{!! __('services.s.jlabel') !!}</div>
            <h2 style="font-size:36px; margin-bottom:40px;">{!! __('services.s.jtitle') !!}</h2>
            
            <div class="journey-step gsap-fade-up">
                <div class="step-num">01</div>
                <div class="step-body">
                    <h3 style="font-size:22px; margin-bottom:10px;">{!! __('services.s.st1.t') !!}</h3>
                    <p style="color:var(--muted);">{!! __('services.s.st1.d') !!}</p>
                </div>
            </div>
            <div class="journey-step gsap-fade-up">
                <div class="step-num">02</div>
                <div class="step-body">
                    <h3 style="font-size:22px; margin-bottom:10px;">{!! __('services.s.st2.t') !!}</h3>
                    <p style="color:var(--muted);">{!! __('services.s.st2.d') !!}</p>
                </div>
            </div>
            <div class="journey-step gsap-fade-up">
                <div class="step-num">03</div>
                <div class="step-body">
                    <h3 style="font-size:22px; margin-bottom:10px;">{!! __('services.s.st3.t') !!}</h3>
                    <p style="color:var(--muted);">{!! __('services.s.st3.d') !!}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES GRID (database-driven, static display cards) -->
    <section class="section-pad" style="background:var(--paper);">
        <div class="container">
            <div style="text-align:center; margin-bottom:56px;">
                <div class="label" style="color:var(--ocean)">{!! __('services.s.clabel') !!}</div>
                <h2 style="font-size:36px;">{!! __('services.s.ctitle') !!}</h2>
            </div>
            
            <div class="services-grid">
                @forelse($services as $index => $service)
                    <div class="service-card gsap-fade-up">
                        <div class="service-card-header">
                            <div class="service-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0H9m4 0h2M9 7h6"></path>
                                </svg>
                            </div>
                            <span class="service-card-num">{{ sprintf('%02d', $index + 1) }}</span>
                        </div>
                        <h3 class="service-title">{{ $service->getTitle() }}</h3>
                        <p class="service-desc">{{ $service->getTranslated('description') }}</p>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 64px 0;">
                        <p style="color: var(--muted); font-size: 16px;">No services available.</p>
                    </div>
                @endforelse
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

<script>
    document.addEventListener("DOMContentLoaded", () => { gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }); gsap.fromTo('.gsap-fade-up', { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.gsap-fade-up', start: 'top 85%' } }); });
</script>
<x-success-modal />
</body>
</html>