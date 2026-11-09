<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('insights.seo_title') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<style>
:root { --sun: #F2A900; --ocean: #003087; --midnight: #00205B; --sky: #307FE2; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
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
.nav-links { display: flex; gap: 32px; } .nav-links a { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .72; transition: .25s; }
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
.nav-right { display: flex; align-items: center; gap: 20px; }
.lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; }
.lang-switch .active-lang { opacity: 1; color: var(--sun); }
.quote { background: var(--sun); color: var(--ink); padding: 14px 19px; font-size: 10px; font-weight: 800; text-transform: uppercase; }

main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
.container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
.section-pad { padding: 120px 0; }
.inner-hero { background: var(--sky); color: var(--ink); padding: 120px 0 100px; position: relative; overflow: hidden; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--ink); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block;}

.filter-tags { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 40px; }
.filter-tags span { padding: 8px 15px; font-size: 11px; font-weight: bold; text-transform: uppercase; background: var(--white); border: 1px solid var(--line); border-radius: 30px; transition: 0.3s; cursor: default; }
.filter-tags span:hover { background: var(--ink); color: var(--white); }

.blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 40px; }
.blog-card { display: flex; flex-direction: column; background: var(--white); border: 1px solid var(--line); transition: 0.4s; overflow: hidden; }
.blog-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.06); border-color: var(--ocean); }
.blog-img { height: 200px; overflow: hidden; }
.blog-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.8s; }
.blog-card:hover .blog-img img { transform: scale(1.05); }
.blog-info { padding: 30px; display: flex; flex-direction: column; flex-grow: 1; }
.blog-info small { font-size: 10px; font-weight: bold; color: var(--sun); text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.1em; }
.blog-info h3 { font-size: 22px; color: var(--ink); margin-bottom: 15px; line-height: 1.3;}
.blog-info p { font-size: 14px; color: var(--muted); margin-bottom: 20px; flex-grow: 1; }
.blog-info a { font-size: 11px; font-weight: bold; color: var(--ocean); text-transform: uppercase; }

footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px;}
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em;}
</style>
</head>
<body>
<x-header />
<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label">{!! __('insights.ins.label') !!}</div>
            <h1>{!! __('insights.ins.title') !!}</h1>
        </div>
    </section>
    <section class="section-pad">
        <div class="container">
            <div class="filter-tags gsap-fade-up">
                <span>{!! __('insights.ins.t1') !!}</span>
                <span>{!! __('insights.ins.t2') !!}</span>
                <span>{!! __('insights.ins.t3') !!}</span>
                <span>{!! __('insights.ins.t4') !!}</span>
                <span>{!! __('insights.ins.t5') !!}</span>
            </div>
            <div class="blog-grid">
                <div class="blog-card gsap-fade-up">
                    <div class="blog-img"><img src="https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=600&q=80" alt="Blog Image"></div>
                    <div class="blog-info">
                        <small>{!! __('insights.ins.t1') !!}</small>
                        <h3>{!! __('insights.ins.b1.title') !!}</h3>
                        <p>{!! __('insights.ins.b1.desc') !!}</p>
                        <a href="{{ route('announcements.index') }}">{!! __('insights.ins.read') !!}</a>
                    </div>
                </div>
                <div class="blog-card gsap-fade-up">
                    <div class="blog-img"><img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80" alt="Blog Image"></div>
                    <div class="blog-info">
                        <small>{!! __('insights.ins.t4') !!}</small>
                        <h3>{!! __('insights.ins.b2.title') !!}</h3>
                        <p>{!! __('insights.ins.b2.desc') !!}</p>
                        <a href="{{ route('announcements.index') }}">{!! __('insights.ins.read') !!}</a>
                    </div>
                </div>
                <div class="blog-card gsap-fade-up">
                    <div class="blog-img"><img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=600&q=80" alt="Blog Image"></div>
                    <div class="blog-info">
                        <small>{!! __('insights.ins.t5') !!}</small>
                        <h3>{!! __('insights.ins.b3.title') !!}</h3>
                        <p>{!! __('insights.ins.b3.desc') !!}</p>
                        <a href="{{ route('announcements.index') }}">{!! __('insights.ins.read') !!}</a>
                    </div>
                </div>
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
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
        document.querySelectorAll('.gsap-fade-up').forEach(el => { gsap.fromTo(el, { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, ease: "power3.out", scrollTrigger: { trigger: el, start: "top 85%" } }); });
    });
</script>
<x-success-modal />
</body>
</html>