<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Engineering The Future</title>
<meta name="description" content="BETA — Engineering, manufacturing and installation of metal and steel structures.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
    --ink:#071321;
    --blue:#0b3f8f;
    --blue2:#1264c7;
    --yellow:#f4b900;
    --paper:#f4f2eb;
    --white:#fff;
    --muted:#66717c;
    --line:rgba(7,19,33,.15);
    --max:1380px;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
    font-family:"DM Sans",sans-serif;
    background:var(--paper);
    color:var(--ink);
    overflow-x:hidden;
}
html[dir="rtl"] body{font-family:"Cairo",sans-serif}
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] .brand{font-family:"Cairo",sans-serif}
a{text-decoration:none;color:inherit}
button{font:inherit}
img{display:block;width:100%}

/* ---------------- NAV ---------------- */
.nav{
    position:absolute;
    top:0;left:0;width:100%;
    z-index:100;
    color:white;
}
.nav-inner{
    width:min(var(--max),calc(100% - 64px));
    height:92px;margin:auto;
    display:flex;align-items:center;
    justify-content:space-between;
    border-bottom:1px solid rgba(255,255,255,.25);
}
.brand{
    display:flex;align-items:center;gap:12px;
    font-family:Manrope,sans-serif;
    font-size:26px;font-weight:800;
    letter-spacing:.08em;
}
.brand-mark{
    width:30px;height:30px;
    position:relative;
    transform:skew(-14deg) rotate(45deg);
    border:3px solid var(--yellow);
}
.brand-mark:before{
    content:"";position:absolute;
    left:6px;top:2px;width:3px;height:20px;
    background:var(--yellow);
}
.nav-links{display:flex;gap:32px}
.nav-links a{
    font-size:10px;font-weight:700;
    letter-spacing:.14em;text-transform:uppercase;
    opacity:.72;transition:.25s;
}
.nav-links a:hover{opacity:1;color:var(--yellow)}
.nav-right{display:flex;align-items:center;gap:20px}
.lang{font-size:10px;opacity:.6;letter-spacing:.12em}
.quote{
    background:var(--yellow);
    color:var(--ink);
    padding:14px 19px;
    font-size:10px;font-weight:800;
    letter-spacing:.12em;text-transform:uppercase;
    transition:.3s;
}
.quote:hover{background:white;transform:translateY(-2px)}
.menu{display:none;background:none;border:0;color:#fff;font-size:25px}

/* ---------------- HERO ---------------- */
.hero{
    position:relative;
    min-height:850px;
    height:100vh;
    max-height:980px;
    background:var(--ink);
    color:#fff;
    overflow:hidden;
}
.hero-photo{
    position:absolute;
    right:0;top:0;
    width:67%;height:100%;
    background:
        linear-gradient(90deg,var(--ink) 0%,rgba(7,19,33,.9) 7%,rgba(7,19,33,.12) 45%,rgba(7,19,33,.05)),
        url("https://images.unsplash.com/photo-1511818966892-d7d671e672a2?auto=format&fit=crop&w=2200&q=90") center/cover;
    clip-path:polygon(14% 0,100% 0,100% 100%,0 100%);
    animation:photoIn 1.4s cubic-bezier(.2,.8,.2,1) both;
}
@keyframes photoIn{
    from{transform:translateX(80px);opacity:0}
    to{transform:none;opacity:1}
}
.hero-photo:after{
    content:"";
    position:absolute;inset:0;
    background:linear-gradient(115deg,rgba(18,100,199,.13),transparent 40%);
}
.blue-wash{
    position:absolute;
    width:520px;height:520px;
    right:-120px;bottom:-240px;
    border-radius:50%;
    background:var(--blue);
    filter:blur(100px);
    opacity:.45;
}
.grid{
    position:absolute;inset:0;
    opacity:.12;
    background-image:
        linear-gradient(rgba(255,255,255,.35) 1px,transparent 1px),
        linear-gradient(90deg,rgba(255,255,255,.35) 1px,transparent 1px);
    background-size:90px 90px;
    mask-image:linear-gradient(90deg,#000,transparent 62%);
}
.hero-content{
    width:min(var(--max),calc(100% - 64px));
    margin:auto;
    position:relative;z-index:3;
    padding-top:210px;
}
.overline{
    display:flex;align-items:center;gap:12px;
    color:var(--yellow);
    font-size:10px;font-weight:800;
    letter-spacing:.25em;text-transform:uppercase;
    margin-bottom:24px;
}
.overline i{width:48px;height:1px;background:var(--yellow)}
.hero h1{
    max-width:790px;
    font-family:Manrope,sans-serif;
    font-size:clamp(65px,8.3vw,124px);
    font-weight:800;
    line-height:.82;
    letter-spacing:-.075em;
}
.hero h1 em{
    color:var(--yellow);
    font-style:normal;
    position:relative;
}
.hero h1 em:after{
    content:"";
    position:absolute;
    left:2px;right:0;bottom:-7px;
    height:5px;
    background:var(--yellow);
    transform-origin:left;
    animation:lineIn 1s .8s both;
}
@keyframes lineIn{from{transform:scaleX(0)}to{transform:scaleX(1)}}
.hero-description{
    width:380px;
    margin-top:35px;
    color:#c2cbd4;
    font-size:14px;
    line-height:1.75;
}
.hero-actions{display:flex;gap:10px;margin-top:29px}
.btn{
    display:inline-flex;align-items:center;gap:25px;
    padding:15px 18px;
    border:1px solid rgba(255,255,255,.45);
    color:#fff;
    font-size:10px;font-weight:800;
    letter-spacing:.14em;text-transform:uppercase;
    transition:.3s;
}
.btn.primary{background:var(--yellow);border-color:var(--yellow);color:var(--ink)}
.btn:hover{background:#fff;border-color:#fff;color:var(--ink);transform:translateY(-3px)}
.hero-code{
    position:absolute;
    right:0;bottom:46px;
    z-index:4;
    width:285px;
    padding:20px;
    background:rgba(7,19,33,.84);
    border:1px solid rgba(255,255,255,.2);
    backdrop-filter:blur(12px);
}
.hero-code-top{
    display:flex;justify-content:space-between;
    font-size:8px;color:#93a2b1;
    letter-spacing:.16em;text-transform:uppercase;
}
.hero-code-line{
    height:1px;background:rgba(255,255,255,.15);
    margin:15px 0;
}
.code-number{
    font-family:Manrope,sans-serif;
    font-size:40px;font-weight:800;
}
.code-number span{color:var(--yellow)}
.code-small{
    color:#9ba8b4;font-size:9px;
    letter-spacing:.13em;text-transform:uppercase;
}
.hero-scroll{
    position:absolute;
    left:32px;bottom:38px;
    z-index:5;
    display:flex;align-items:center;gap:12px;
    font-size:9px;letter-spacing:.2em;
    text-transform:uppercase;color:#aeb9c3;
}
.hero-scroll span{
    display:block;width:35px;height:1px;background:var(--yellow);
    animation:pulseLine 1.5s infinite;
}
@keyframes pulseLine{
    0%,100%{transform:scaleX(.4);transform-origin:left}
    50%{transform:scaleX(1)}
}

/* ---------------- INTRO ---------------- */
.intro{
    position:relative;
    padding:145px 0 120px;
    background:var(--paper);
    overflow:hidden;
}
.container{width:min(var(--max),calc(100% - 64px));margin:auto}
.intro-grid{
    display:grid;
    grid-template-columns:.8fr 1.2fr;
    gap:90px;
    align-items:start;
}
.label{
    font-size:9px;font-weight:800;
    color:var(--blue);
    letter-spacing:.23em;text-transform:uppercase;
}
.intro-big{
    font-family:Manrope,sans-serif;
    font-size:clamp(42px,5.4vw,76px);
    line-height:.95;
    letter-spacing:-.065em;
    font-weight:800;
    max-width:820px;
}
.intro-big strong{color:var(--blue)}
.intro-text{
    max-width:550px;
    margin-top:30px;
    color:var(--muted);
    line-height:1.8;
    font-size:14px;
}
.intro-line{
    margin-top:35px;
    display:flex;align-items:center;gap:13px;
    color:var(--ink);
    font-size:10px;font-weight:800;
    letter-spacing:.16em;text-transform:uppercase;
}
.intro-line:before{content:"";width:42px;height:2px;background:var(--yellow)}

/* ---------------- MARQUEE ---------------- */
.marquee{
    overflow:hidden;
    background:var(--blue);
    color:white;
    border-top:1px solid rgba(255,255,255,.2);
    border-bottom:1px solid rgba(255,255,255,.2);
}
.marquee-track{
    width:max-content;
    display:flex;
    animation:marquee 24s linear infinite;
}
.marquee span{
    display:flex;align-items:center;gap:32px;
    padding:21px 32px;
    font-family:Manrope,sans-serif;
    font-size:17px;font-weight:700;
    letter-spacing:.05em;
    white-space:nowrap;
}
.marquee b{color:var(--yellow);font-size:20px}
@keyframes marquee{to{transform:translateX(-50%)}}

/* ---------------- SOLUTIONS ---------------- */
.solutions{
    padding:125px 0;
    background:var(--paper);
}
.section-heading{
    display:flex;justify-content:space-between;
    align-items:end;gap:40px;
    margin-bottom:55px;
}
.section-heading h2{
    margin-top:12px;
    font-family:Manrope,sans-serif;
    font-size:clamp(42px,5.2vw,74px);
    line-height:.9;
    letter-spacing:-.065em;
}
.section-heading p{
    max-width:390px;
    color:var(--muted);
    font-size:13px;line-height:1.75;
}
.solution-list{
    border-top:1px solid var(--line);
}
.solution{
    min-height:150px;
    display:grid;
    grid-template-columns:75px 1fr 1.2fr 65px;
    align-items:center;
    gap:25px;
    border-bottom:1px solid var(--line);
    transition:.45s;
    position:relative;
    overflow:hidden;
}
.solution:before{
    content:"";
    position:absolute;
    left:0;top:0;bottom:0;
    width:4px;background:var(--yellow);
    transform:scaleY(0);
    transition:.35s;
}
.solution:hover{
    padding-left:20px;
    background:white;
}
.solution:hover:before{transform:scaleY(1)}
.solution-no{
    font-family:Manrope,sans-serif;
    font-size:12px;font-weight:800;color:#9ba4ab;
}
.solution h3{
    font-family:Manrope,sans-serif;
    font-size:clamp(23px,3vw,39px);
    letter-spacing:-.045em;
}
.solution p{
    color:var(--muted);
    max-width:430px;
    font-size:12px;line-height:1.65;
}
.solution-arrow{
    width:46px;height:46px;
    display:grid;place-items:center;
    border:1px solid var(--line);
    font-size:18px;
    transition:.35s;
}
.solution:hover .solution-arrow{
    background:var(--yellow);
    border-color:var(--yellow);
    transform:rotate(-45deg);
}

/* ---------------- PROCESS ---------------- */
.process{
    background:var(--ink);
    color:white;
    padding:130px 0;
    position:relative;
    overflow:hidden;
}
.process:before{
    content:"";
    position:absolute;
    width:650px;height:650px;
    right:-300px;top:-280px;
    border:1px solid rgba(255,255,255,.12);
    border-radius:50%;
    box-shadow:0 0 0 80px rgba(255,255,255,.025),
               0 0 0 160px rgba(255,255,255,.018);
}
.process-heading{
    display:flex;justify-content:space-between;
    gap:40px;align-items:end;
    margin-bottom:80px;
}
.process-heading h2{
    font-family:Manrope,sans-serif;
    font-size:clamp(44px,6vw,80px);
    line-height:.88;
    letter-spacing:-.07em;
}
.process-heading h2 span{color:var(--yellow)}
.process-heading p{max-width:380px;color:#8e9ba7;font-size:13px;line-height:1.8}
.steps{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    position:relative;
}
.steps:before{
    content:"";
    position:absolute;
    top:28px;left:28px;right:28px;
    height:1px;background:rgba(255,255,255,.2);
}
.step{position:relative;padding-right:25px}
.step-dot{
    width:57px;height:57px;
    border:1px solid rgba(255,255,255,.35);
    display:grid;place-items:center;
    background:var(--ink);
    color:var(--yellow);
    font-size:11px;font-weight:800;
    position:relative;z-index:2;
    transition:.4s;
}
.step:hover .step-dot{
    background:var(--yellow);
    color:var(--ink);
    transform:rotate(45deg);
}
.step h3{
    margin-top:27px;
    font-family:Manrope,sans-serif;
    font-size:18px;
}
.step p{
    margin-top:10px;
    color:#82909c;font-size:11px;
    line-height:1.65;max-width:170px;
}

/* ---------------- PROJECT ---------------- */
.projects{
    padding:130px 0;
    background:white;
}
.project-feature{
    display:grid;
    grid-template-columns:1.55fr .75fr;
    min-height:620px;
}
.project-visual{
    position:relative;overflow:hidden;
    background:#142535;
}
.project-visual img{
    height:100%;object-fit:cover;
    transition:transform 1.2s cubic-bezier(.2,.7,.2,1);
}
.project-feature:hover .project-visual img{transform:scale(1.04)}
.project-index{
    position:absolute;left:25px;top:25px;
    z-index:2;
    padding:9px 12px;
    background:var(--yellow);
    color:var(--ink);
    font-size:9px;font-weight:800;
    letter-spacing:.15em;
}
.project-caption{
    position:absolute;left:35px;bottom:30px;z-index:2;
    color:white;
}
.project-caption small{
    color:var(--yellow);
    font-size:9px;font-weight:800;letter-spacing:.2em;
    text-transform:uppercase;
}
.project-caption h3{
    margin-top:8px;
    font-family:Manrope,sans-serif;
    font-size:35px;letter-spacing:-.05em;
}
.project-info{
    padding:55px 48px;
    background:var(--blue);
    color:white;
    display:flex;flex-direction:column;justify-content:flex-end;
}
.project-info .label{color:var(--yellow)}
.project-info h2{
    font-family:Manrope,sans-serif;
    font-size:clamp(35px,4vw,57px);
    line-height:.9;
    letter-spacing:-.06em;
    margin:18px 0 23px;
}
.project-info p{color:#c3d2df;font-size:12px;line-height:1.8}
.project-info a{
    margin-top:38px;
    color:var(--yellow);
    font-size:10px;font-weight:800;
    letter-spacing:.17em;text-transform:uppercase;
}

/* ---------------- CTA ---------------- */
.cta{
    position:relative;
    overflow:hidden;
    padding:125px 0;
    background:var(--yellow);
}
.cta:before{
    content:"";
    position:absolute;
    width:500px;height:500px;
    right:-180px;top:-180px;
    border:1px solid rgba(7,19,33,.15);
    border-radius:50%;
    box-shadow:0 0 0 70px rgba(7,19,33,.04),
               0 0 0 140px rgba(7,19,33,.03);
}
.cta-inner{
    position:relative;z-index:2;
    display:flex;align-items:end;
    justify-content:space-between;
    gap:40px;
}
.cta h2{
    margin-top:14px;
    font-family:Manrope,sans-serif;
    font-size:clamp(45px,6.3vw,90px);
    line-height:.86;
    letter-spacing:-.075em;
}
.cta .btn{
    background:var(--ink);
    border-color:var(--ink);
    white-space:nowrap;
}
.cta .btn:hover{background:white;border-color:white}

/* ---------------- FOOTER ---------------- */
footer{
    padding:55px 0 25px;
    background:var(--ink);
    color:white;
}
.footer-top{
    display:flex;justify-content:space-between;
    gap:50px;padding-bottom:45px;
    border-bottom:1px solid rgba(255,255,255,.14);
}
.footer-desc{
    max-width:330px;
    margin-top:18px;color:#788694;
    font-size:12px;line-height:1.7;
}
.footer-columns{display:flex;gap:80px}
.footer-col h4{
    font-size:9px;letter-spacing:.18em;
    text-transform:uppercase;color:#fff;
    margin-bottom:17px;
}
.footer-col a{
    display:block;color:#7e8b97;
    font-size:11px;margin:10px 0;
    transition:.2s;
}
.footer-col a:hover{color:var(--yellow)}
.footer-bottom{
    display:flex;justify-content:space-between;
    padding-top:20px;
    color:#566574;
    font-size:8px;letter-spacing:.15em;
/* ---------------- ANNOUNCEMENTS PREVIEW ---------------- */
.announcements-sec{
    padding:115px 0;
    background:#ffffff;
    border-top:1px solid var(--line);
    border-bottom:1px solid var(--line);
}
.news-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:30px;
    margin-top:45px;
}
.news-card{
    background:var(--paper);
    border:1px solid var(--line);
    padding:32px 28px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    transition:.35s;
    position:relative;
}
.news-card:hover{
    transform:translateY(-5px);
    border-color:var(--yellow);
    background:#fff;
    box-shadow:0 14px 35px rgba(7,19,33,.07);
}
.news-meta{
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:10px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.14em;
    color:var(--blue);
    margin-bottom:14px;
}
.news-meta time{color:var(--muted)}
.news-card h3{
    font-family:Manrope,sans-serif;
    font-size:22px;
    margin-bottom:12px;
    line-height:1.25;
    letter-spacing:-.03em;
}
.news-card p{
    font-size:13px;
    color:var(--muted);
    line-height:1.75;
    margin-bottom:24px;
    flex-grow:1;
}
.news-link{
    font-size:10px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.16em;
    color:var(--ink);
    display:inline-flex;
    align-items:center;
    gap:8px;
    transition:.25s;
}
.news-card:hover .news-link{
    color:var(--yellow);
    gap:12px;
}

/* ---------------- REVEALS ---------------- */
.reveal{
    opacity:0;
    transform:translateY(35px);
    transition:
        opacity .8s ease,
        transform .8s cubic-bezier(.2,.75,.2,1);
}
.reveal.in{opacity:1;transform:none}
.reveal-left{
    opacity:0;transform:translateX(-40px);
    transition:opacity .9s ease,transform .9s cubic-bezier(.2,.75,.2,1);
}
.reveal-left.in{opacity:1;transform:none}

/* ---------------- MOBILE ---------------- */
@media(max-width:900px){
    .nav-inner,.container,.hero-content{width:calc(100% - 40px)}
    .nav-links,.nav-right .lang,.nav-right .quote{display:none}
    .menu{display:block}
    .nav-links.mobile{
        display:flex;position:absolute;
        top:80px;left:20px;right:20px;
        background:rgba(7,19,33,.97);
        padding:25px;
        flex-direction:column;
        gap:20px;
        border:1px solid rgba(255,255,255,.2);
    }
    .hero{min-height:800px}
    .hero-photo{width:100%;clip-path:none;opacity:.42}
    .hero-content{padding-top:190px}
    .hero h1{font-size:65px}
    .hero-description{width:min(100%,420px)}
    .hero-code{display:none}
    .intro-grid{grid-template-columns:1fr;gap:35px}
    .section-heading,.process-heading{align-items:flex-start;flex-direction:column}
    .solution{grid-template-columns:45px 1fr 50px;gap:15px}
    .solution p{display:none}
    .steps{grid-template-columns:1fr;gap:35px}
    .steps:before{left:28px;top:28px;bottom:28px;width:1px;height:auto}
    .step{display:grid;grid-template-columns:75px 1fr;gap:15px;padding:0}
    .step h3{margin-top:5px}
    .step p{grid-column:2}
    .step-dot{grid-row:1/3}
    .project-feature{grid-template-columns:1fr;min-height:850px}
    .project-visual{min-height:480px}
    .project-info{min-height:370px}
    .cta-inner{flex-direction:column;align-items:flex-start}
    .footer-top{flex-direction:column}
    .footer-columns{gap:45px}
}
@media(max-width:560px){
    .nav-inner,.container,.hero-content{width:calc(100% - 30px)}
    .hero h1{font-size:54px}
    .hero{min-height:760px}
    .hero-actions{flex-direction:column;align-items:flex-start}
    .hero-scroll{left:15px}
    .intro,.solutions,.process,.projects{padding:90px 0}
    .solution{min-height:120px}
    .project-info{padding:38px 28px}
    .footer-columns{display:grid;grid-template-columns:1fr 1fr}
    .footer-bottom{flex-direction:column;gap:10px}
}
</style>
</head>

<body>

<header class="nav">
    <div class="nav-inner">
    <a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a>

        <nav class="nav-links" id="nav">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')]) data-i18n="nav.home">Home</a>
            <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')]) data-i18n="nav.about">About</a>
            <a href="{{ route('services') }}" @class(['active' => request()->routeIs('services')]) data-i18n="nav.services">Services</a>
            <a href="{{ route('products') }}" @class(['active' => request()->routeIs('products')]) data-i18n="nav.products">Products</a>
            <a href="{{ route('projects') }}" @class(['active' => request()->routeIs('projects')]) data-i18n="nav.projects">Projects & Sectors</a>
            <a href="{{ route('announcements') }}" @class(['active' => request()->routeIs('announcements*')]) data-i18n="nav.news">Announcements</a>
            <a href="{{ route('careers') }}" @class(['active' => request()->routeIs('careers*')]) data-i18n="nav.careers">Careers</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')]) data-i18n="nav.contact">Contact</a>
        </nav>

        <div class="nav-right">
            <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="lang" style="color:white;text-decoration:none;font-weight:700;">{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</a>
            <a class="quote" href="{{ route('contact') }}">Request a Quote →</a>
            <button class="menu" id="menu" aria-label="Menu">☰</button>
        </div>
    </div>
</header>

<main>

<!-- HERO -->
<section class="hero" id="home">
    <div class="hero-photo"></div>
    <div class="grid"></div>
    <div class="blue-wash"></div>

    <div class="hero-content">
        <div class="overline">
            {{ __('app.services') }}
            @if($firstService?->getTranslated('title'))
                {{ $firstService?->getTranslated('title') }}
            @else
                Industrial Engineering & Steel Structures
            @endif
        </div>

        <h1>
            WE BUILD<br>
            WHAT<br>
            <em>MATTERS.</em>
        </h1>

        <p class="hero-description">
            Engineering, manufacturing and installation under one roof.
            BETA turns demanding structural requirements into precise,
            durable and buildable solutions.
        </p>

        <div class="hero-actions">
            <a href="#solutions" class="btn primary">Explore Solutions <b>↗</b></a>
            <a href="{{ route('contact') }}" class="btn">Start a Project <b>→</b></a>
        </div>
    </div>

    <div class="hero-code">
        <div class="hero-code-top">
            <span>Project Capacity</span>
            <span>01 / 04</span>
        </div>
        <div class="hero-code-line"></div>
        <div class="code-number">16<span>+</span></div>
        <div class="code-small">Years of engineering experience</div>
    </div>

    <div class="hero-scroll"><span></span> Scroll to explore</div>
</section>

<!-- INTRO -->
<section class="intro">
    <div class="container intro-grid">
        <div class="reveal-left">
            <div class="label">01 / The BETA Standard</div>
        </div>

        <div class="reveal">
            <h2 class="intro-big">
                Precision is not a feature.
                <strong>It is the foundation.</strong>
            </h2>

            <p class="intro-text">
                From structural steel and metal fabrication to complete
                installation, BETA brings the full project journey together.
                One team. One process. One accountable partner.
            </p>

            <div class="intro-line">Engineering · Manufacturing · Installation</div>
        </div>
    </div>
</section>

<!-- MOVING STATEMENT -->
<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <span>
            {{ __('app.services') }} <b>✦</b>
            {{ $firstService?->getTranslated('title') ? ucfirst($firstService?->getTranslated('title')) : 'MANUFACTURE' }} <b>✦</b>
            INSTALL <b>✦</b>
            {{ $firstService?->getTranslated('title') ? ucfirst($firstService?->getTranslated('title')) : 'MANUFACTURE' }}
        </span>
        <span>ENGINEER <b>✦</b> MANUFACTURE <b>✦</b> INSTALL <b>✦</b> ENGINEER <b>✦</b> MANUFACTURE <b>✦</b> INSTALL <b>✦</b></span>
    </div>
</div>

<!-- SOLUTIONS -->
<section class="solutions" id="solutions">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <div class="label">02 / Solutions</div>
                <h2>Built around<br>the challenge.</h2>
            </div>
            <p>
                From large-scale structures to specialist fabrication,
                our capabilities adapt to the technical demands of the project.
            </p>
        </div>

        <div class="solution-list">
            @forelse(($services ?? collect())->take(4) as $index => $service)
                <a class="solution reveal" href="{{ route('services') }}">
                    <span class="solution-no">{{ $index + 1 }}</span>
                    <h3>{{ $service?->getTranslated('title') }}</h3>
                    <p>{{ $service?->getTranslated('description') }}</p>
                    <span class="solution-arrow">↗</span>
                </a>
            @empty
            @endforelse
        </div>
    </div>
</section>

<!-- PROCESS -->
<section class="process" id="process">
    <div class="container">
        <div class="process-heading reveal">
            <div>
                <div class="label" style="color:var(--yellow)">03 / How We Deliver</div>
                <h2>One process.<br><span>Zero gaps.</span></h2>
            </div>
            <p>
                The BETA journey connects consultation, engineering,
                fabrication, quality control and installation into one
                controlled delivery process.
            </p>
        </div>

        <div class="steps">
            <div class="step reveal">
                <div class="step-dot">01</div>
                <div><h3>Consult</h3><p>Understand the requirement and define the project.</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">02</div>
                <div><h3>Engineer</h3><p>Develop drawings, calculations and technical solutions.</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">03</div>
                <div><h3>Manufacture</h3><p>Fabricate with controlled quality and material traceability.</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">04</div>
                <div><h3>Install</h3><p>Execute safely and efficiently on site.</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">05</div>
                <div><h3>Support</h3><p>Stay involved beyond delivery when the project requires it.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- PROJECT -->
<section class="projects" id="projects">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <div class="label">04 / Selected Work</div>
                <h2>Projects that<br>speak for us.</h2>
            </div>
            <p>Use BETA's real project photography here to immediately communicate scale, capability and execution.</p>
        </div>

        <div class="project-feature reveal">
            <div class="project-visual">
                <div class="project-index">PROJECT / 001</div>
                <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1700&q=90" alt="Steel architecture project">
                <div class="project-caption">
                    <small>Industrial Structure</small>
                    <h3>Built with purpose.</h3>
                </div>
            </div>

            <div class="project-info">
                <div class="label">Featured Project</div>
                <h2>From drawing<br>to reality.</h2>
                <p>
                    A complete engineering and fabrication story.
                    This area becomes the visual proof of BETA's ability
                    to execute projects at scale.
                </p>
                <a href="{{ route('projects') }}">View Project →</a>
            </div>
        </div>
    </div>
</section>

<!-- ANNOUNCEMENTS PREVIEW -->
<section class="announcements-sec" id="announcements">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <div class="label">05 / Latest Updates</div>
                <h2>News &amp; Engineering<br>Insights.</h2>
            </div>
            <div>
                <p>Stay informed on our latest projects, company news, and technical innovations.</p>
                <div style="margin-top:15px;">
                    <a href="{{ route('announcements') }}" class="btn" style="color:var(--ink);border-color:var(--ink);">View All Announcements <b>↗</b></a>
                </div>
            </div>
        </div>

        <div class="news-grid">
            @forelse($announcements ?? [] as $item)
                <article class="news-card reveal">
                    <div>
                        <div class="news-meta">
                            <span>{{ ucfirst($item->type ?? 'news') }}</span>
                            <time datetime="{{ $item->published_at?->format('Y-m-d') }}">{{ $item->published_at?->format('M d, Y') ?? '—' }}</time>
                        </div>
                        <h3><a href="{{ route('announcements.show', $item->public_id) }}">{{ $item->getTranslated('title') }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->getTranslated('excerpt') ?: $item->getTranslated('content')), 130) }}</p>
                    </div>
                    <div>
                        <a href="{{ route('announcements.show', $item->public_id) }}" class="news-link">
                            Read Update <b>→</b>
                        </a>
                    </div>
                </article>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--muted);border:1px dashed var(--line);">
                    <p>New announcements and project updates will be posted here soon.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta" id="contact">
    <div class="container cta-inner reveal">
        <div>
            <div class="label">06 / Start a Conversation</div>
            <h2>Have a project<br>in mind?</h2>
        </div>
        <div style="display:flex;gap:15px;flex-wrap:wrap;">
            <a href="{{ route('contact') }}" class="btn" style="background:var(--ink);color:var(--yellow);border-color:var(--ink);">Request a Quote <b>↗</b></a>
            <a href="mailto:info@beta-industries.com" class="btn" style="background:transparent;color:var(--ink);border-color:var(--ink);">Talk to Engineering <b>✉</b></a>
        </div>
    </div>
</section>

</main>

<footer>
    <div class="container">
        <div class="footer-top">
            <div>
                <a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a>
                <p class="footer-desc">
                    Engineering, manufacturing and installation of
                    industrial metal and steel solutions.
                </p>
            </div>

            <div class="footer-columns">
                <div class="footer-col">
                    <h4>Explore</h4>
                    <a href="{{ route('services') }}">Solutions</a>
                    <a href="#process">Capabilities</a>
                    <a href="{{ route('projects') }}">Projects</a>
                </div>
                <div class="footer-col">
                    <h4>Company</h4>
                    <a href="{{ route('about') }}">About BETA</a>
                    <a href="{{ route('careers') }}">Careers</a>
                    <a href="{{ route('announcements') }}">Announcements &amp; Insights</a>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <a href="{{ route('contact') }}">Request a Quote</a>
                    <a href="mailto:info@beta-industries.com">Email</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 BETA — All Rights Reserved</span>
            <span>Engineer. Manufacture. Install.</span>
        </div>
    </div>
</footer>

<script>
/* Mobile navigation */
const menu = document.getElementById("menu");
const nav = document.getElementById("nav");

if (menu && nav) {
    menu.addEventListener("click", () => {
        nav.classList.toggle("mobile");
        menu.textContent = nav.classList.contains("mobile") ? "×" : "☰";
    });

    nav.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => {
            nav.classList.remove("mobile");
            menu.textContent = "☰";
        });
    });
}

/* Native reveal animation — no GSAP, no library */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add("in");
            revealObserver.unobserve(entry.target);
        }
    });
}, {threshold:.13});

document.querySelectorAll(".reveal, .reveal-left").forEach(el => {
    revealObserver.observe(el);
});

/* Small parallax effect for the hero image */
const heroPhoto = document.querySelector(".hero-photo");

window.addEventListener("scroll", () => {
    if(window.scrollY < window.innerHeight){
        heroPhoto.style.transform = `translateY(${window.scrollY * 0.12}px)`;
    }
}, {passive:true});
</script>

</body>
</html>
