<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('about.seo_title') }}</title>
<meta name="description" content="{{ __('about.seo_description') }}">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
    --sun:#F2A900;
    --midnight:#00205B;
    --ocean:#003087;
    --ink:#071321;
    --paper:#F4F2EB;
    --white:#FFFFFF;
    --muted:#66717c;
    --line:rgba(7,19,33,.15);
    --max:1380px;
    --header-height:92px;
    --ease:cubic-bezier(0.16, 1, 0.3, 1);
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
    font-family:"DM Sans",sans-serif;
    background:var(--paper);
    color:var(--ink);
    overflow-x:hidden;
    line-height:1.6;
}
html[dir="rtl"] body{font-family:"Cairo",sans-serif}
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] h5,html[dir="rtl"] h6,
html[dir="rtl"] p,html[dir="rtl"] span,html[dir="rtl"] div{letter-spacing:0 !important}
html[dir="rtl"] h1,html[dir="rtl"] h2{line-height:1.35}
html[dir="rtl"] p,html[dir="rtl"] .about-desc,html[dir="rtl"] .section-intro p,
html[dir="rtl"] .vision-card p,html[dir="rtl"] .mission-card p,
html[dir="rtl"] .pillar p,html[dir="rtl"] .dl-description{line-height:1.8}
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] .brand{font-family:"Cairo",sans-serif}
a{text-decoration:none;color:inherit}
button{font:inherit;cursor:pointer;border:none;background:none}
img{display:block;width:100%;height:auto}

/* Reveal — visible by default, IntersectionObserver only polishes */
.reveal{opacity:1;transform:none;transition:opacity .6s ease,transform .6s var(--ease)}
.reveal.in{opacity:1;transform:none}

/* NAV — reused from index pattern */
.nav{position:fixed;top:0;left:0;width:100%;z-index:1000;background:var(--ink);color:var(--white);border-bottom:1px solid rgba(255,255,255,.1)}
.nav-inner{width:min(var(--max),calc(100% - 48px));height:var(--header-height);margin:auto;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:12px;font-size:26px;font-weight:800;letter-spacing:.08em}
.brand-mark{width:30px;height:30px;position:relative;transform:skew(-14deg) rotate(45deg);border:3px solid var(--sun)}
.brand-mark:before{content:"";position:absolute;left:6px;top:2px;width:3px;height:20px;background:var(--sun)}
.nav-links{display:flex;gap:32px}
.nav-links a{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.7);transition:.25s}
.nav-links a:hover,.nav-links a.active{color:var(--sun)}
.nav-right{display:flex;align-items:center;gap:20px}
.lang{font-size:10px;opacity:.6;letter-spacing:.12em}
.quote{background:var(--sun);color:var(--ink);padding:14px 19px;font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;transition:.3s}
.quote:hover{background:white;transform:translateY(-2px)}
.menu{display:none;background:none;border:0;color:#fff;font-size:25px}

main{min-height:calc(100vh - var(--header-height));padding-top:var(--header-height)}
.container{width:min(var(--max),calc(100% - 64px));margin:auto}
.section-pad{padding:110px 0}
.label{font-size:10px;font-weight:800;letter-spacing:.23em;text-transform:uppercase;color:var(--sun);display:block;margin-bottom:12px}

/* SPLIT HERO */
.split-hero{position:relative;background:var(--midnight);color:var(--white);overflow:hidden;padding:130px 0 100px}
.split-hero:before{content:"";position:absolute;inset:0;background:linear-gradient(135deg,var(--midnight) 0%,rgba(0,32,91,.75) 60%,transparent 100%);z-index:1}
.split-hero .hero-grid{position:relative;z-index:2;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center}
.split-hero .hero-content{max-width:560px}
.split-hero .hero-label{font-size:10px;font-weight:800;color:var(--sun);letter-spacing:.23em;text-transform:uppercase;display:inline-block;margin-bottom:16px}
.split-hero h1{font-size:clamp(48px,7vw,80px);line-height:1.05;margin-bottom:24px;letter-spacing:-.04em}
.split-hero p{color:rgba(255,255,255,.85);font-size:17px;line-height:1.7;margin-bottom:32px;max-width:520px}
.split-hero .hero-img{position:relative;height:560px;overflow:hidden;border-radius:16px;box-shadow:0 30px 60px rgba(0,0,0,.35)}
.split-hero .hero-img img{height:100%;object-fit:cover;object-position:center;transition:transform .8s var(--ease)}
.split-hero:hover .hero-img img{transform:scale(1.03)}
.split-hero .hero-img:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 0%,rgba(0,0,0,.45) 100%)}
html[dir="rtl"] .split-hero .hero-grid{direction:rtl}
html[dir="rtl"] .split-hero h1{text-align:right}
html[dir="rtl"] .split-hero p{text-align:right}

/* STATS */
.stats{background:var(--ink);color:var(--white);padding:100px 0;position:relative;overflow:hidden}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:40px;text-align:center;position:relative;z-index:2}
.stat h4{font-size:52px;font-weight:800;color:var(--sun);line-height:1;margin-bottom:10px}
.stat p{font-size:11px;text-transform:uppercase;letter-spacing:.15em;color:#8e9ba7}
html[dir="rtl"] .stat p{direction:rtl}

/* VISION / MISSION */
.vision-mission{background:var(--white);padding:110px 0}
.vm-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:flex-start}
.vision-card,.mission-card{padding:50px;border:1px solid var(--line);border-radius:20px;position:relative;overflow:hidden}
.vision-card:before{content:"";position:absolute;top:0;left:0;width:4px;height:100%;background:var(--sun)}
.mission-card:before{content:"";position:absolute;top:0;left:0;width:4px;height:100%;background:var(--ocean)}
.vision-card h3,.mission-card h3{font-size:28px;margin-bottom:16px;line-height:1.2}
.vision-card p,.mission-card p{font-size:16px;line-height:1.75;color:var(--muted)}
html[dir="rtl"] .vision-card:before{left:auto;right:0}
html[dir="rtl"] .mission-card:before{left:auto;right:0}

/* PILLARS */
.pillars{padding:110px 0}
.pillar-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:30px}
.pillar{padding:40px 30px;border:1px solid var(--line);border-radius:16px;text-align:center;transition:var(--ease) .3s;display:flex;flex-direction:column;align-items:center}
.pillar:hover{border-color:var(--sun);transform:translateY(-6px);box-shadow:0 20px 40px rgba(0,0,0,.04)}
.pillar-icon{width:56px;height:56px;background:var(--paper);border-radius:12px;display:grid;place-items:center;margin:0 auto 20px;font-size:26px}
.pillar h4{font-size:18px;margin-bottom:12px}
.pillar p{font-size:13px;line-height:1.6;color:var(--muted)}
html[dir="rtl"] .pillar p{direction:rtl}

/* DOWNLOADS GRID — shared with certificates page */
.downloads{background:var(--paper);padding:110px 0}
.dl-header{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:8px}
.dl-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:30px;margin-top:40px}
.dl-card{background:var(--white);border:1px solid var(--line);border-radius:16px;overflow:hidden;transition:var(--ease) .3s;display:flex;flex-direction:column}
.dl-card:hover{border-color:var(--sun);transform:translateY(-6px);box-shadow:0 20px 40px rgba(0,0,0,.06)}
.dl-image{position:relative;height:220px;overflow:hidden}
.dl-image img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.dl-card:hover .dl-image img{transform:scale(1.05)}
.dl-image:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 0%,rgba(0,0,0,.15) 100%)}
.dl-image .dl-empty{width:100%;height:100%;background:var(--paper);display:grid;place-items:center;color:var(--muted);font-size:12px}
.dl-content{padding:28px;display:flex;flex-direction:column;gap:16px;flex:1}
.dl-meta{display:flex;justify-content:space-between;align-items:flex-start;gap:12px}
.dl-title{font-size:17px;font-weight:800;line-height:1.3;margin:0;color:var(--ink)}
.dl-date{font-size:11px;font-weight:700;color:var(--sun);background:rgba(242,169,0,.1);padding:5px 10px;border-radius:6px;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap}
.dl-description{font-size:14px;line-height:1.7;color:var(--muted);max-height:0;overflow:hidden;transition:max-height .5s var(--ease),opacity .4s ease;opacity:0;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical}
.dl-card.expanded .dl-description{max-height:500px;opacity:1}
html[dir="rtl"] .dl-description{text-align:right}
html[dir="rtl"] .dl-title{text-align:right}
html[dir="rtl"] .dl-date{text-align:left}
.dl-footer{padding:0 28px 28px;border-top:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;gap:12px}
.btn-toggle{font-size:11px;font-weight:800;color:var(--ocean);text-transform:uppercase;letter-spacing:.1em;padding:8px 14px;border:1px solid var(--ocean);border-radius:6px;transition:var(--ease) .25s;white-space:nowrap}
.btn-toggle:hover{background:var(--ocean);color:var(--white)}
.btn-dl{font-size:11px;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:.1em;padding:10px 18px;background:var(--sun);border-radius:6px;transition:var(--ease) .25s;white-space:nowrap}
.btn-dl:hover{background:var(--ink);color:var(--white)}
html[dir="rtl"] .btn-toggle{text-align:right}
html[dir="rtl"] .dl-footer{direction:rtl}

/* FOOTER */
footer{padding:80px 0 30px;background:var(--ink);color:var(--white)}
.footer-top{display:flex;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.15);padding-bottom:45px}
.footer-bottom{display:flex;justify-content:space-between;padding-top:25px;font-size:10px;color:#566574;text-transform:uppercase;letter-spacing:.15em}

/* RESPONSIVE */
@media(max-width:1024px){
    .split-hero .hero-grid{grid-template-columns:1fr}
    .split-hero .hero-content{max-width:100%}
    .split-hero .hero-img{height:400px}
    .vm-grid{grid-template-columns:1fr}
    .pillar-grid{grid-template-columns:repeat(2,1fr)}
    .dl-grid{grid-template-columns:repeat(2,1fr)}
    .stats-grid{grid-template-columns:repeat(2,1fr);gap:24px}
    .stat h4{font-size:40px}
}
@media(max-width:640px){
    .pillar-grid{grid-template-columns:1fr}
    .dl-grid{grid-template-columns:1fr}
    .stats-grid{grid-template-columns:repeat(2,1fr);gap:24px}
    .stat h4{font-size:32px}
    .split-hero{padding:90px 0 60px}
    .section-pad{padding:80px 0}
    .vm-card,.vision-card,.mission-card{padding:32px}
}
@media(max-width:480px){
    .nav-inner{height:80px}
    .nav-links,.quote{display:none}
    .menu{display:block}
    .section-pad{padding:60px 0}
}
</style>
</head>
<body>

<x-header />

<main>
    <!-- SPLIT HERO -->
    <section class="split-hero reveal">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <div class="hero-label">{{ __('about.hero.label') }}</div>
                    <h1>{!! __('about.hero.title') !!}</h1>
                    <p>{!! __('about.hero.desc') !!}</p>
                </div>
                <div class="hero-img">
                    <img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1200&q=80" alt="{{ __('about.hero.alt') }}">
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats reveal">
        <div class="container">
            <div class="stats-grid">
                <div class="stat"><h4 class="counter" data-target="16">0</h4><p>{{ __('about.stats.exp') }}</p></div>
                <div class="stat"><h4 class="counter" data-target="850">0</h4><p>{{ __('about.stats.projects') }}</p></div>
                <div class="stat"><h4 class="counter" data-target="50000">0</h4><p>{{ __('about.stats.tons') }}</p></div>
                <div class="stat"><h4 class="counter" data-target="12">0</h4><p>{{ __('about.stats.industries') }}</p></div>
            </div>
        </div>
    </section>

    <!-- VISION / MISSION -->
    <section class="vision-mission reveal">
        <div class="container">
            <div class="vm-grid">
                <div class="vision-card">
                    <h3>{{ __('about.vision.title') }}</h3>
                    <p>{!! __('about.vision.text') !!}</p>
                </div>
                <div class="mission-card">
                    <h3>{{ __('about.mission.title') }}</h3>
                    <p>{!! __('about.mission.text') !!}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PILLARS -->
    <section class="pillars reveal">
        <div class="container">
            <div class="pillar-grid">
                <div class="pillar">
                    <div class="pillar-icon">✦</div>
                    <h4>{{ __('about.pillars.quality.title') }}</h4>
                    <p>{!! __('about.pillars.quality.text') !!}</p>
                </div>
                <div class="pillar">
                    <div class="pillar-icon">🛡️</div>
                    <h4>{{ __('about.pillars.safety.title') }}</h4>
                    <p>{!! __('about.pillars.safety.text') !!}</p>
                </div>
                <div class="pillar">
                    <div class="pillar-icon">⚡</div>
                    <h4>{{ __('about.pillars.innovation.title') }}</h4>
                    <p>{!! __('about.pillars.innovation.text') !!}</p>
                </div>
                <div class="pillar">
                    <div class="pillar-icon">🚚</div>
                    <h4>{{ __('about.pillars.delivery.title') }}</h4>
                    <p>{!! __('about.pillars.delivery.text') !!}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TECHNICAL DOWNLOADS -->
    <section class="downloads reveal">
        <div class="container">
            <div class="dl-header">
                <div>
                    <div class="label">{{ __('about.downloads.label') }}</div>
                    <h2 style="font-size:36px;margin-top:8px">{{ __('about.downloads.title') }}</h2>
                </div>
            </div>
            <div class="dl-grid">
                @forelse($technicalDownloads as $download)
                <div class="dl-card" data-id="{{ $download->id }}">
                    <div class="dl-image">
                        @if($download->preview_image)
                            <img src="{{ asset($download->preview_image) }}" alt="{{ $download->getTitle() }}">
                        @else
                            <div class="dl-empty">No Preview</div>
                        @endif
                    </div>
                    <div class="dl-content">
                        <div class="dl-meta">
                            <h3 class="dl-title">{{ $download->getTitle() }}</h3>
                            @if($download->issuance_date)
                                <span class="dl-date">{{ $download->issuance_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                        <div class="dl-description">{!! $download->getDescription() !!}</div>
                    </div>
                    <div class="dl-footer">
                        <button class="btn-toggle" onclick="toggleDlCard(this)">{{ __('about.downloads.read_more') }}</button>
                        <a href="{{ route('technical-downloads.download', $download->id) }}" class="btn-dl">{{ __('about.downloads.download') }}</a>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1;text-align:center;color:var(--muted);padding:60px">{{ __('about.downloads.no_downloads') }}</div>
                @endforelse
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© {{ date('Y') }} BETA</span><span>{{ __('app.footer.tagline') }}</span></div>
    </div>
</footer>

<script>
/* Native reveal — no GSAP, no library. Elements visible by default. */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add('in');
        }
    });
}, {threshold:.12});

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

/* Animated counters */
const counters = document.querySelectorAll('.counter');
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            const el = entry.target;
            const target = +el.getAttribute('data-target');
            const duration = 2000;
            const startTime = performance.now();
            function animate(now){
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const value = Math.floor(progress * target);
                const display = target >= 1000 ? (value / 1000).toFixed(0) + 'k' : value;
                el.textContent = display;
                if(progress < 1) requestAnimationFrame(animate);
            }
            requestAnimationFrame(animate);
            counterObserver.unobserve(el);
        }
    });
}, {threshold:0.5});
counters.forEach(c => counterObserver.observe(c));

/* Download card expand / collapse */
function toggleDlCard(btn){
    const card = btn.closest('.dl-card');
    const isExpanded = card.classList.contains('expanded');

    document.querySelectorAll('.dl-card.expanded').forEach(c => {
        if(c !== card) c.classList.remove('expanded');
    });

    card.classList.toggle('expanded');

    const isNowExpanded = card.classList.contains('expanded');
    btn.textContent = isNowExpanded ? "{{ __('about.downloads.read_less') }}" : "{{ __('about.downloads.read_more') }}";

    if(isNowExpanded){
        setTimeout(() => card.scrollIntoView({behavior:'smooth',block:'nearest'}), 300);
    }
}
</script>

<x-success-modal />
</body>
</html>
