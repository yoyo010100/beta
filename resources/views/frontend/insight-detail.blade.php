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
:root { --sun: #F2A900; --ocean: #003087; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
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
.lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; } .lang-switch .active-lang { opacity: 1; color: var(--sun); }

.article-header { padding: calc(var(--header-height) + 80px) 0 60px; text-align: center; }
.article-header .cat { font-size: 11px; font-weight: bold; color: var(--ocean); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 15px; }
.article-header h1 { font-size: clamp(35px, 5vw, 55px); max-width: 900px; margin: 0 auto 20px; line-height: 1.1; }
.article-header .date { font-size: 13px; color: #66717c; }

.article-img { width: min(1000px, calc(100% - 40px)); margin: 0 auto 60px; height: 450px; }
.article-img img { width: 100%; height: 100%; object-fit: cover; }

.article-body { width: min(760px, calc(100% - 40px)); margin: 0 auto 100px; font-size: 17px; line-height: 1.8; color: #333; }
.article-body h2 { font-size: 28px; color: var(--ink); margin: 40px 0 20px; }
.article-body p { margin-bottom: 25px; }
.article-body blockquote { border-inline-start: 4px solid var(--sun); padding-inline-start: 20px; font-style: italic; font-size: 20px; margin: 40px 0; color: var(--ink); }
</style>
</head>
<body>
<x-header />
<main>
    <section class="article-header gsap-fade-up">
        <div class="cat">{!! __('insight-detail.art.cat') !!}</div>
        <h1>{!! __('insight-detail.art.title') !!}</h1>
        <div class="date">{!! __('insight-detail.art.date') !!}</div>
    </section>
    
    <div class="article-img gsap-fade-up">
        <img src="https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=1200&q=80" alt="Steel fabrication">
    </div>

    <div class="article-body gsap-fade-up">
        <p>{!! __('insight-detail.art.p1') !!}</p>
        <h2>{!! __('insight-detail.art.h1') !!}</h2>
        <p>{!! __('insight-detail.art.p2') !!}</p>
        <blockquote>{!! __('insight-detail.art.quote') !!}</blockquote>
        <h2>{!! __('insight-detail.art.h2') !!}</h2>
        <p>{!! __('insight-detail.art.p3') !!}</p>
    </div>
</main>
<script>
    document.addEventListener("DOMContentLoaded", () => { gsap.fromTo('.gsap-fade-up', { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.2, ease: "power3.out" }); });
</script>
<x-success-modal />
</body>
</html>