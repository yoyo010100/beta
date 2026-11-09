<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('about.seo_title') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<style>
:root { --sun: #F2A900; --ocean: #003087; --midnight: #00205B; --sky: #307FE2; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); overflow-x: hidden; line-height: 1.6; }
html[dir="rtl"] body { font-family: "Cairo", sans-serif; }
h1, h2, h3, h4, .brand { font-family: "Manrope", sans-serif; }
html[dir="rtl"] h1, html[dir="rtl"] h2, html[dir="rtl"] h3, html[dir="rtl"] h4, html[dir="rtl"] .brand { font-family: "Cairo", sans-serif; }
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
.inner-hero { background: var(--ocean); color: var(--white); padding: 120px 0 100px; position: relative; overflow: hidden; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }

.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.about-image { position: relative; overflow: hidden; height: 500px; }
.about-image img { height: 100%; object-fit: cover; }

.stats-banner { background: var(--ink); color: var(--white); padding: 60px 0; margin-top: 100px; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; text-align: center; }
.stat-item h4 { font-size: 48px; color: var(--sun); margin-bottom: 10px; line-height: 1; }
.stat-item p { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #8e9ba7; }

.q-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 40px; }
.q-card { background: var(--white); border: 1px solid var(--line); padding: 35px; transition: 0.3s; }
.q-card:hover { border-color: var(--ocean); transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.q-card h3 { font-size: 20px; margin-bottom: 10px; color: var(--ink); }

.dl-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 40px; }
.dl-card { background: var(--white); padding: 30px; border: 1px solid var(--line); text-align: center; transition: 0.3s; }
.dl-card:hover { border-color: var(--sun); transform: translateY(-5px); }
.btn-dl { display: inline-block; padding: 10px 18px; margin-top: 15px; border: 1px solid var(--ink); font-size: 10px; font-weight: bold; text-transform: uppercase; transition: 0.3s; }
.dl-card:hover .btn-dl { background: var(--ink); color: var(--white); }

footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }
@media(max-width:992px){ .about-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<x-header />

<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label">{!! __('about.about.heroLabel') !!}</div>
            <h1>{!! __('about.about.heroTitle') !!}</h1>
            <p style="color: rgba(255,255,255,0.85); max-width:600px;">{!! __('about.about.heroDesc') !!}</p>
        </div>
    </section>

    <!-- SECTION 1: STORY -->
    <section class="section-pad">
        <div class="container about-grid">
            <div class="gsap-fade-up">
                <div class="label" style="color:var(--ocean)">{!! __('about.about.stdLabel') !!}</div>
                <h2 style="font-size:38px; line-height:1.1; margin-bottom:20px;">{!! __('about.about.stdTitle') !!}</h2>
                <p style="color:var(--muted); margin-bottom:15px;">{!! __('about.about.desc1') !!}</p>
                <p style="color:var(--muted);">{!! __('about.about.desc2') !!}</p>
            </div>
            <div class="about-image gsap-fade-up">
                <img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1000&q=80" alt="Factory Interior">
            </div>
        </div>
    </section>

    <!-- SECTION 2: QUALITY & STANDARDS -->
    <section class="section-pad" style="background:var(--white);">
        <div class="container">
            <div class="label" style="color:var(--ocean)">{!! __('about.q.label') !!}</div>
            <h2 style="font-size:36px;">{!! __('about.q.title') !!}</h2>
            <div class="q-grid">
                <div class="q-card gsap-fade-up">
                    <h3>{!! __('about.q.c1.t') !!}</h3>
                    <p style="font-size:14px; color:var(--muted);">{!! __('about.q.c1.d') !!}</p>
                </div>
                <div class="q-card gsap-fade-up">
                    <h3>{!! __('about.q.c2.t') !!}</h3>
                    <p style="font-size:14px; color:var(--muted);">{!! __('about.q.c2.d') !!}</p>
                </div>
                <div class="q-card gsap-fade-up">
                    <h3>{!! __('about.q.c3.t') !!}</h3>
                    <p style="font-size:14px; color:var(--muted);">{!! __('about.q.c3.d') !!}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: DOWNLOADS -->
    <section class="section-pad">
        <div class="container">
            <div class="label" style="color:var(--ocean)">{!! __('about.dl.label') !!}</div>
            <h2 style="font-size:36px;">{!! __('about.dl.title') !!}</h2>
            <div class="dl-grid">
                <div class="dl-card gsap-fade-up">
                    <h3>{!! __('about.dl.d1') !!}</h3>
                    <a href="#" class="btn-dl">{!! __('about.dl.btn') !!}</a>
                </div>
                <div class="dl-card gsap-fade-up">
                    <h3>{!! __('about.dl.d2') !!}</h3>
                    <a href="#" class="btn-dl">{!! __('about.dl.btn') !!}</a>
                </div>
                <div class="dl-card gsap-fade-up">
                    <h3>{!! __('about.dl.d3') !!}</h3>
                    <a href="#" class="btn-dl">{!! __('about.dl.btn') !!}</a>
                </div>
            </div>
        </div>
    </section>
        <section class="stats-banner gsap-fade-up">
        <div class="container stats-grid">
            <div class="stat-item">
                <h4>16</h4>
                <p>{!! __('about.stats.exp') !!}</p>
            </div>
            <div class="stat-item">
                <h4>850+</h4>
                <p>{!! __('about.stats.projects') !!}</p>
            </div>
            <div class="stat-item">
                <h4>50k</h4>
                <p>{!! __('about.stats.tons') !!}</p>
            </div>
            <div class="stat-item">
                <h4>12</h4>
                <p>{!! __('about.stats.industries') !!}</p>
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
    document.addEventListener("DOMContentLoaded", () => { gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1 }); gsap.fromTo('.gsap-fade-up', { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.gsap-fade-up', start: 'top 85%' } }); });
</script>
<x-success-modal />
</body>
</html>