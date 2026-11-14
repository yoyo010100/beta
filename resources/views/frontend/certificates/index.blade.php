<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('certificates.seo_title') }}</title>
<meta name="description" content="{{ __('certificates.seo_description') }}">
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

/* Reveal — visible by default */
.reveal{opacity:1;transform:none;transition:opacity .6s ease,transform .6s var(--ease)}
.reveal.in{opacity:1;transform:none}

/* NAV */
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
.section-pad{padding:100px 0}
.label-tag{font-size:10px;font-weight:800;color:var(--sun);letter-spacing:.23em;text-transform:uppercase;display:inline-block;margin-bottom:16px}

/* Page Hero */
.page-hero{background:var(--midnight);color:var(--white);padding:120px 0 90px;position:relative;overflow:hidden}
.page-hero:before{content:"";position:absolute;inset:0;background:linear-gradient(135deg,var(--midnight) 0%,rgba(0,32,91,.6) 100%);z-index:1}
.page-hero .container{position:relative;z-index:2}
.page-hero h1{font-size:clamp(40px,6vw,72px);line-height:1.05;margin-bottom:16px;letter-spacing:-.04em}
.page-hero p{color:rgba(255,255,255,.85);font-size:18px;line-height:1.7;max-width:600px}

/* Certificate Cards Grid — shared dl-card design */
.certs-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:30px;margin-top:40px}
.cert-card{background:var(--white);border:1px solid var(--line);border-radius:16px;overflow:hidden;transition:var(--ease) .3s;display:flex;flex-direction:column}
.cert-card:hover{border-color:var(--sun);transform:translateY(-6px);box-shadow:0 20px 40px rgba(0,0,0,.06)}
.cert-image{position:relative;height:220px;overflow:hidden}
.cert-image img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.cert-card:hover .cert-image img{transform:scale(1.05)}
.cert-image:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 0%,rgba(0,0,0,.15) 100%)}
.cert-image .cert-empty{width:100%;height:100%;background:var(--paper);display:grid;place-items:center;color:var(--muted);font-size:12px}
.cert-content{padding:28px;display:flex;flex-direction:column;gap:16px;flex:1}
.cert-meta{display:flex;justify-content:space-between;align-items:flex-start;gap:12px}
.cert-title{font-size:17px;font-weight:800;line-height:1.3;margin:0;color:var(--ink)}
.cert-date-badge{font-size:11px;font-weight:700;color:var(--sun);background:rgba(242,169,0,.1);padding:5px 10px;border-radius:6px;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap}
.cert-description{font-size:14px;line-height:1.7;color:var(--muted);max-height:0;overflow:hidden;transition:max-height .5s var(--ease),opacity .4s ease;opacity:0;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical}
.cert-card.expanded .cert-description{max-height:500px;opacity:1}
html[dir="rtl"] .cert-description{text-align:right}
html[dir="rtl"] .cert-title{text-align:right}
html[dir="rtl"] .cert-date-badge{text-align:left}
.cert-footer{padding:0 28px 28px;border-top:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;gap:12px}
.btn-toggle{font-size:11px;font-weight:800;color:var(--ocean);text-transform:uppercase;letter-spacing:.1em;padding:8px 14px;border:1px solid var(--ocean);border-radius:6px;transition:var(--ease) .25s;white-space:nowrap}
.btn-toggle:hover{background:var(--ocean);color:var(--white)}
.btn-download{font-size:11px;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:.1em;padding:10px 18px;background:var(--sun);border-radius:6px;transition:var(--ease) .25s;white-space:nowrap}
.btn-download:hover{background:var(--ink);color:var(--white)}
html[dir="rtl"] .btn-toggle{text-align:right}
html[dir="rtl"] .cert-footer{direction:rtl}

/* Empty State */
.empty-state{text-align:center;padding:80px 20px;color:var(--muted)}
.empty-state p{font-size:16px;margin-top:12px}

/* Responsive */
@media(max-width:1024px){.certs-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){.certs-grid{grid-template-columns:1fr}}
@media(max-width:480px){
    .nav-inner{height:80px}
    .nav-links,.quote{display:none}
    .menu{display:block}
}
</style>
</head>
<body>

<x-header />

<main class="pt-[92px] min-h-screen">
    <!-- Page Hero -->
    <section class="page-hero reveal">
        <div class="container">
            <h1>{{ __('certificates.title') }}</h1>
            <p>{{ __('certificates.subtitle') }}</p>
        </div>
    </section>

    <!-- Certificates Grid -->
    <section class="section-pad reveal">
        <div class="container">
            <div class="certs-grid">
                @forelse($certificates as $certificate)
                <div class="cert-card" data-id="{{ $certificate->id }}">
                    <div class="cert-image">
                        @if($certificate->preview_image)
                            <img src="{{ asset($certificate->preview_image) }}" alt="{{ $certificate->getTitle() }}">
                        @else
                            <div class="cert-empty">No Preview</div>
                        @endif
                    </div>
                    <div class="cert-content">
                        <div class="cert-meta">
                            <h3 class="cert-title">{{ $certificate->getTitle() }}</h3>
                            @if($certificate->issuance_date)
                                <span class="cert-date-badge">{{ $certificate->issuance_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                        <div class="cert-description">{!! $certificate->getDescription() !!}</div>
                    </div>
                    <div class="cert-footer">
                        <button class="btn-toggle" onclick="toggleCertCard(this)">{{ __('about.downloads.read_more') }}</button>
                        <a href="{{ route('certificates.download', $certificate->id) }}" class="btn-download">{{ __('about.downloads.download') }}</a>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1">
                    <div class="empty-state">
                        <p>{{ __('certificates.none') }}</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</main>

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

/* Certificate card expand / collapse */
function toggleCertCard(btn){
    const card = btn.closest('.cert-card');
    const isExpanded = card.classList.contains('expanded');

    document.querySelectorAll('.cert-card.expanded').forEach(c => {
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
