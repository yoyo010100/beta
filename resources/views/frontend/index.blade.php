<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('home.seo_title') }}</title>
<meta name="description" content="{{ __('home.seo_description') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
    --ink:#071321;
    --midnight:#00205B;
    --ocean:#003087;
    --sun:#F2A900;
    --paper:#F4F2EB;
    --white:#FFFFFF;
    --muted:#66717c;
    --line:rgba(7,19,33,.15);
    --max:1380px;
    --ease:cubic-bezier(0.16, 1, 0.3, 1);
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
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] h5,html[dir="rtl"] h6,
html[dir="rtl"] p,html[dir="rtl"] span,html[dir="rtl"] div{letter-spacing:0 !important}
html[dir="rtl"] h1,html[dir="rtl"] h2{line-height:1.35}
html[dir="rtl"] p,html[dir="rtl"] .hero-description,html[dir="rtl"] .intro-text,
html[dir="rtl"] .section-heading p,html[dir="rtl"] .process-heading p,
html[dir="rtl"] .solution p,html[dir="rtl"] .step p,
html[dir="rtl"] .project-info p,html[dir="rtl"] .footer-desc,html[dir="rtl"] .news-card p,
html[dir="rtl"] .video-stat .t{line-height:1.8}
html[dir="rtl"] h1,html[dir="rtl"] h2,html[dir="rtl"] h3,html[dir="rtl"] h4,html[dir="rtl"] .brand{font-family:"Cairo",sans-serif}
a{text-decoration:none;color:inherit}
button{font:inherit}
img{display:block;width:100%;height:auto}

/* ---------------- NAV ---------------- */
.nav{position:absolute;top:0;left:0;width:100%;z-index:100;color:white}
.nav-inner{width:min(var(--max),calc(100% - 64px));height:92px;margin:auto;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.25)}
.brand{display:flex;align-items:center;gap:12px;font-family:Manrope,sans-serif;font-size:26px;font-weight:800;letter-spacing:.08em}
.brand-mark{width:30px;height:30px;position:relative;transform:skew(-14deg) rotate(45deg);border:3px solid var(--sun)}
.brand-mark:before{content:"";position:absolute;left:6px;top:2px;width:3px;height:20px;background:var(--sun)}
.nav-links{display:flex;gap:32px}
.nav-links a{font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;opacity:.72;transition:.25s}
.nav-links a:hover{opacity:1;color:var(--sun)}
.nav-right{display:flex;align-items:center;gap:20px}
.lang{font-size:10px;opacity:.6;letter-spacing:.12em}
.quote{background:var(--sun);color:var(--ink);padding:14px 19px;font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;transition:.3s}
.quote:hover{background:white;transform:translateY(-2px)}
.menu{display:none;background:none;border:0;color:#fff;font-size:25px}

/* ---------------- HERO ---------------- */
.hero{position:relative;min-height:850px;height:100vh;max-height:980px;background:var(--ink);color:#fff;overflow:hidden}
.hero-photo{position:absolute;right:0;top:0;width:67%;height:100%;background:linear-gradient(90deg,var(--ink) 0%,rgba(7,19,33,.9) 7%,rgba(7,19,33,.12) 45%,rgba(7,19,33,.05)),url("https://images.unsplash.com/photo-1511818966892-d7d671e672a2?auto=format&fit=crop&w=2200&q=90") center/cover;clip-path:polygon(14% 0,100% 0,100% 100%,0 100%)}
.hero-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(115deg,rgba(18,100,199,.13),transparent 40%)}
.blue-wash{position:absolute;width:520px;height:520px;right:-120px;bottom:-240px;border-radius:50%;background:var(--ocean);filter:blur(100px);opacity:.45}
.grid{position:absolute;inset:0;opacity:.12;background-image:linear-gradient(rgba(255,255,255,.35) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.35) 1px,transparent 1px);background-size:90px 90px;mask-image:linear-gradient(90deg,#000,transparent 62%)}
.hero-content{width:min(var(--max),calc(100% - 64px));margin:auto;position:relative;z-index:3;padding-top:210px}
.overline{display:flex;align-items:center;gap:12px;color:var(--sun);font-size:10px;font-weight:800;letter-spacing:.25em;text-transform:uppercase;margin-bottom:24px}
.overline i{width:48px;height:1px;background:var(--sun)}
.hero h1{max-width:790px;font-family:Manrope,sans-serif;font-size:clamp(65px,8.3vw,124px);font-weight:800;line-height:.82;letter-spacing:-.075em}
.hero h1 em{color:var(--sun);font-style:normal;position:relative}
.hero h1 em:after{content:"";position:absolute;left:2px;right:0;bottom:-7px;margin:28px 0;height:5px;background:var(--sun);transform-origin:left}
.hero-description{width:380px;margin-top:35px;color:#c2cbd4;font-size:clamp(16px,1.2vw,19px);line-height:1.75}
.hero-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:29px}
.btn{display:inline-flex;align-items:center;gap:25px;padding:15px 18px;border:1px solid rgba(255,255,255,.45);color:#fff;font-size:10px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;transition:.3s}
.btn.primary{background:var(--sun);border-color:var(--sun);color:var(--ink);position:relative;overflow:hidden}
.btn.primary:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);transform:translateX(-100%);transition:.6s var(--ease)}
.btn.primary:hover:before{transform:translateX(100%)}
.btn:hover{background:#fff;border-color:#fff;color:var(--ink);transform:translateY(-3px)}

/* Stat badge — glassmorphic */
.hero-code{position:absolute;right:0;bottom:46px;z-index:4;width:285px;padding:20px;background:rgba(7,19,33,.84);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,.35)}
.hero-code-top{display:flex;justify-content:space-between;font-size:8px;color:#93a2b1;letter-spacing:.16em;text-transform:uppercase}
.hero-code-line{height:1px;background:rgba(255,255,255,.15);margin:15px 0}
.code-number{font-family:Manrope,sans-serif;font-size:40px;font-weight:800}
.code-number span{color:var(--sun)}
.code-small{color:#9ba8b4;font-size:9px;letter-spacing:.13em;text-transform:uppercase}

.hero-scroll{position:absolute;left:32px;bottom:38px;z-index:5;display:flex;align-items:center;gap:12px;font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:#aeb9c3}
.hero-scroll span{display:block;width:35px;height:1px;background:var(--sun);animation:pulseLine 1.5s infinite}
@keyframes pulseLine{0%,100%{transform:scaleX(.4);transform-origin:left}50%{transform:scaleX(1)}}

/* RTL HERO mirroring */
html[dir="rtl"] .hero-photo{left:0;right:auto;transform:scaleX(-1)}
html[dir="rtl"] .blue-wash{left:-120px;right:auto}
html[dir="rtl"] .grid{mask-image:linear-gradient(90deg,transparent 38%,#000)}
html[dir="rtl"] .hero-code{left:0;right:auto}
html[dir="rtl"] .hero-scroll{left:auto;right:32px}
html[dir="rtl"] .hero-content{text-align:right}
html[dir="rtl"] .overline{justify-content:flex-end}
html[dir="rtl"] .hero-actions{justify-content:flex-end}
html[dir="rtl"] .hero h1 em:after{left:auto;right:2px;transform-origin:right}

/* ---------------- VIDEO SHOWCASE ---------------- */
.video-showcase{padding:120px 0;background:var(--midnight);color:#fff;overflow:hidden}
.video-section{width:min(var(--max),calc(100% - 64px));margin:auto;display:flex;flex-direction:column;gap:50px;align-items:center}
.video-header{text-align:center;max-width:760px}
.video-header .label{color:var(--sun);font-size:10px;font-weight:800;letter-spacing:.25em;text-transform:uppercase}
.video-header h2{font-family:Manrope,sans-serif;font-size:clamp(40px,5vw,64px);line-height:1.12;letter-spacing:-.05em;margin-top:14px}
.video-header p{color:#c2cbd4;font-size:clamp(15px,1.2vw,18px);line-height:1.75;margin-top:20px}
.video-frame-wrap{position:relative;width:100%;max-width:1180px;aspect-ratio:16/9;background:var(--ink);border:1px solid rgba(255,255,255,.12);border-radius:22px;overflow:hidden;box-shadow:0 30px 70px rgba(7,19,33,0.3)}
.video-corner{position:absolute;width:26px;height:26px;border:2px solid var(--sun)}
.video-corner.tl{top:0;left:0;border-right:none;border-bottom:none}
.video-corner.tr{top:0;right:0;border-left:none;border-bottom:none}
.video-corner.bl{bottom:0;left:0;border-right:none;border-top:none}
.video-corner.br{bottom:0;right:0;border-left:none;border-top:none}
.video-blueprint{position:absolute;inset:0;opacity:.05;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.12) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.12) 1px,transparent 1px);background-size:48px 48px}
.video-player{position:absolute;inset:0;width:100%;height:100%;background:#000;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:opacity .4s ease}
.video-player video{width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity .7s ease}
.video-player.video-active video{opacity:1}
.video-poster{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:opacity .5s ease,transform .6s ease;background:linear-gradient(135deg,var(--midnight) 0%,var(--ink) 100%)}
.video-player.video-active .video-poster{opacity:0;transform:scale(1.03)}
.video-play{position:absolute;z-index:6;width:86px;height:86px;background:var(--sun);color:var(--ink);border-radius:50%;display:flex;align-items:center;justify-content:center;animation:pulseRing 2.6s infinite;cursor:pointer;box-shadow:0 0 0 0 rgba(242,169,0,.45),0 20px 40px rgba(0,0,0,.35)}
.video-play:before{content:"";display:block;width:0;height:0;border-left:18px solid var(--ink);border-top:11px solid transparent;border-bottom:11px solid transparent;margin-left:3px}
.video-play:hover{transform:scale(1.08);background:#fff}
.video-duration{position:absolute;bottom:18px;right:20px;z-index:6;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#fff;background:rgba(7,19,33,.65);padding:7px 12px;border-radius:8px;backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.15)}
.video-info-bar{position:relative;z-index:2;background:rgba(7,19,33,.84);border:1px solid rgba(255,255,255,.14);border-radius:20px;padding:26px 34px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);display:flex;gap:34px;align-items:center;flex-wrap:wrap}
.video-stat{display:flex;flex-direction:column;gap:7px}
.video-stat .num{font-family:Manrope,sans-serif;font-size:26px;font-weight:800;color:#fff;letter-spacing:-.04em}
.video-stat .num span{color:var(--sun)}
.video-stat .t{font-size:9px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:#93a2b1}
.video-divider{width:1px;height:28px;background:rgba(255,255,255,.18)}
@keyframes pulseRing{0%{box-shadow:0 0 0 0 rgba(242,169,0,.5)}70%{box-shadow:0 0 0 24px rgba(242,169,0,0)}100%{box-shadow:0 0 0 0 rgba(242,169,0,0)}}

html[dir="rtl"] .video-info-bar{direction:rtl}
html[dir="rtl"] .video-play:before{margin-left:auto;margin-right:3px}

/* ---------------- INTRO ---------------- */
.intro{position:relative;padding:145px 0 120px;background:var(--paper);overflow:hidden}
.container{width:min(var(--max),calc(100% - 64px));margin:auto}
.intro-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:90px;align-items:start}
.label{font-size:9px;font-weight:800;color:var(--ocean);letter-spacing:.23em;text-transform:uppercase}
.intro-big{font-family:Manrope,sans-serif;font-size:clamp(42px,5.4vw,76px);line-height:.95;letter-spacing:-.065em;font-weight:800;max-width:820px}
.intro-big strong{color:var(--ocean)}
.intro-text{max-width:550px;margin-top:30px;color:var(--muted);line-height:1.8;font-size:clamp(16px,1.2vw,19px)}
.intro-line{margin-top:35px;display:flex;align-items:center;gap:13px;color:var(--ink);font-size:10px;font-weight:800;letter-spacing:.16em;text-transform:uppercase}
.intro-line:before{content:"";width:42px;height:2px;background:var(--sun)}

/* ---------------- MARQUEE ---------------- */
.marquee{overflow:hidden;background:var(--midnight);color:white;border-top:1px solid rgba(255,255,255,.2);border-bottom:1px solid rgba(255,255,255,.2)}
.marquee-track{width:max-content;display:flex;animation:marquee 24s linear infinite}
.marquee span{display:flex;align-items:center;gap:32px;padding:21px 32px;font-family:Manrope,sans-serif;font-size:17px;font-weight:700;letter-spacing:.05em;white-space:nowrap}
.marquee b{color:var(--sun);font-size:20px}
@keyframes marquee{to{transform:translateX(-50%)}}

/* ---------------- SOLUTIONS ---------------- */
.solutions{padding:125px 0;background:var(--paper)}
.section-heading{display:flex;justify-content:space-between;align-items:end;gap:40px;margin-bottom:55px}
.section-heading h2{margin-top:12px;font-family:Manrope,sans-serif;font-size:clamp(42px,5.2vw,74px);line-height:.9;letter-spacing:-.065em}
.section-heading p{max-width:390px;color:var(--muted);font-size:clamp(16px,1.2vw,19px);line-height:1.75}
.solution-list{border-top:1px solid var(--line)}
.solution{min-height:150px;display:grid;grid-template-columns:75px 1fr 1.2fr 65px;align-items:center;gap:25px;border-bottom:1px solid var(--line);transition:var(--ease) .45s;position:relative;overflow:hidden}
.solution:before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--sun);transform:scaleX(0);transform-origin:left;transition:var(--ease) .35s}
.solution:hover{padding-left:20px;background:white}
.solution:hover:before{transform:scaleX(1)}
.solution-no{font-family:Manrope,sans-serif;font-size:12px;font-weight:800;color:#9ba4ab}
.solution h3{font-family:Manrope,sans-serif;font-size:clamp(23px,3vw,39px);letter-spacing:-.045em}
.solution p{color:var(--muted);max-width:430px;font-size:clamp(16px,1.2vw,19px);line-height:1.65}
.solution-arrow{width:46px;height:46px;display:grid;place-items:center;border:1px solid var(--line);font-size:18px;transition:var(--ease) .35s}
.solution:hover .solution-arrow{background:var(--sun);border-color:var(--sun);transform:rotate(-45deg)}

/* ---------------- FEATURED GRIDS (News / Projects / Products / Services) ---------------- */
.featured-grids{padding:110px 0;background:var(--paper)}
.featured-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;margin-top:30px}
.featured-card{position:relative;background:var(--white);border:1px solid var(--line);border-radius:16px;overflow:hidden;transition:var(--ease) .3s;display:flex;flex-direction:column;aspect-ratio:4/5}
.featured-card:hover{transform:translateY(-8px);border-color:var(--sun);box-shadow:0 24px 48px rgba(7,19,33,.12)}
.featured-card:before{content:"";position:absolute;top:0;left:0;width:100%;height:4px;background:var(--sun);transform:scaleX(0);transform-origin:left;transition:var(--ease) .3s}
.featured-card:hover:before{transform:scaleX(1)}
.featured-image{position:relative;height:55%;overflow:hidden}
.featured-image img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.featured-card:hover .featured-image img{transform:scale(1.05)}
.featured-badge{position:absolute;top:12px;left:12px;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--ink);background:var(--white);padding:6px 10px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,.08);z-index:2}
html[dir="rtl"] .featured-badge{left:auto;right:12px}
.featured-content{padding:16px 18px 18px;flex:1;display:flex;flex-direction:column}
.featured-tag{font-size:8px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:var(--ocean);margin-bottom:8px;display:block}
.featured-card h3{font-family:Manrope,sans-serif;font-size:15px;line-height:1.3;letter-spacing:-.02em;color:var(--ink);margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.featured-card p{font-size:12px;line-height:1.5;color:var(--muted);margin-top:auto;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

/* News cards — editorial row */
.news-card{background:var(--white);border:1px solid var(--line);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;transition:var(--ease) .35s;position:relative}
.news-card:hover{transform:translateY(-6px);border-color:var(--sun);box-shadow:0 16px 40px rgba(7,19,33,.10)}
.news-card:before{content:"";position:absolute;top:0;left:0;width:100%;height:3px;background:var(--sun);transform:scaleX(0);transform-origin:left;transition:var(--ease) .3s}
.news-card:hover:before{transform:scaleX(1)}
.news-card-image{position:relative;height:200px;overflow:hidden}
.news-card-image img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.news-card:hover .news-card-image img{transform:scale(1.05)}
.news-card-date{position:absolute;bottom:12px;left:12px;font-size:9px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--ink);background:var(--white);padding:6px 10px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,.08);z-index:2}
html[dir="rtl"] .news-card-date{left:auto;right:12px}
.news-meta{display:flex;justify-content:space-between;align-items:center;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:var(--ocean);margin-bottom:14px}
.news-meta time{color:var(--muted)}
.news-card h3{font-family:Manrope,sans-serif;font-size:20px;margin-bottom:12px;line-height:1.25;letter-spacing:-.03em;color:var(--ink)}
.news-card p{font-size:clamp(15px,1.2vw,18px);color:var(--muted);line-height:1.75;margin-bottom:20px;flex-grow:1;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.news-link{font-size:10px;font-weight:800;text-transform:uppercase;color:var(--ocean);display:inline-flex;align-items:center;gap:6px;transition:var(--ease) .3s}
.news-link:hover{color:var(--sun);gap:10px}

/* ---------------- PROCESS ---------------- */
.process{background:var(--ink);color:white;padding:130px 0;position:relative;overflow:hidden}
.process:before{content:"";position:absolute;width:650px;height:650px;right:-300px;top:-280px;border:1px solid rgba(255,255,255,.12);border-radius:50%;box-shadow:0 0 0 80px rgba(255,255,255,.025),0 0 0 160px rgba(255,255,255,.018)}
.process-heading{display:flex;justify-content:space-between;gap:40px;align-items:end;margin-bottom:80px}
.process-heading h2{font-family:Manrope,sans-serif;font-size:clamp(44px,6vw,80px);line-height:.88;letter-spacing:-.07em}
.process-heading h2 span{color:var(--sun)}
.process-heading p{max-width:380px;color:#8e9ba7;font-size:clamp(16px,1.2vw,19px);line-height:1.8}
.steps{display:grid;grid-template-columns:repeat(5,1fr);position:relative}
.steps:before{content:"";position:absolute;top:28px;left:28px;right:28px;height:1px;background:rgba(255,255,255,.2)}
.step{position:relative;padding-right:25px}
.step-dot{width:57px;height:57px;border:1px solid rgba(255,255,255,.35);display:grid;place-items:center;background:var(--ink);color:var(--sun);font-size:11px;font-weight:800;position:relative;z-index:2;transition:var(--ease) .4s}
.step:hover .step-dot{background:var(--sun);color:var(--ink);transform:rotate(45deg)}
.step h3{margin-top:27px;font-family:Manrope,sans-serif;font-size:18px}
.step p{margin-top:10px;color:#82909c;font-size:clamp(16px,1.2vw,19px);line-height:1.65;max-width:170px}

/* ---------------- PROJECT ---------------- */
.projects{padding:130px 0;background:white}
.project-feature{display:grid;grid-template-columns:1.55fr .75fr;min-height:620px}
.project-visual{position:relative;overflow:hidden;background:#142535}
.project-visual img{height:100%;object-fit:cover;transition:transform 1.2s cubic-bezier(.2,.7,.2,1)}
.project-feature:hover .project-visual img{transform:scale(1.04)}
.project-index{position:absolute;left:25px;top:25px;z-index:2;padding:9px 12px;background:var(--sun);color:var(--ink);font-size:9px;font-weight:800;letter-spacing:.15em}
.project-caption{position:absolute;left:35px;bottom:30px;z-index:2;color:white}
.project-caption small{color:var(--sun);font-size:9px;font-weight:800;letter-spacing:.2em;text-transform:uppercase}
.project-caption h3{margin-top:8px;font-family:Manrope,sans-serif;font-size:35px;letter-spacing:-.05em}
.project-info{padding:55px 48px;background:var(--midnight);color:white;display:flex;flex-direction:column;justify-content:flex-end}
.project-info .label{color:var(--sun)}
.project-info h2{font-family:Manrope,sans-serif;font-size:clamp(35px,4vw,57px);line-height:.9;letter-spacing:-.06em;margin:18px 0 23px}
.project-info p{color:#c3d2df;font-size:clamp(16px,1.2vw,19px);line-height:1.8}
.project-info a{margin-top:38px;color:var(--sun);font-size:10px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}

/* ---------------- CTA ---------------- */
.cta{position:relative;overflow:hidden;padding:125px 0;background:var(--sun)}
.cta:before{content:"";position:absolute;width:500px;height:500px;right:-180px;top:-180px;border:1px solid rgba(7,19,33,.15);border-radius:50%;box-shadow:0 0 0 70px rgba(7,19,33,.04),0 0 0 140px rgba(7,19,33,.03)}
.cta-accent{position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(90deg,transparent,var(--ink),transparent);opacity:.6}
.cta-inner{position:relative;z-index:2;display:flex;align-items:end;justify-content:space-between;gap:40px}
.cta h2{margin-top:14px;font-family:Manrope,sans-serif;font-size:clamp(45px,6.3vw,90px);line-height:.86;letter-spacing:-.075em}
.cta .btn{background:var(--ink);border-color:var(--ink);white-space:nowrap}
.cta .btn:hover{background:white;border-color:white}

/* ---------------- FOOTER ---------------- */
footer{padding:55px 0 25px;background:var(--ink);color:white}
.footer-top{display:flex;justify-content:space-between;gap:50px;padding-bottom:45px;border-bottom:1px solid rgba(255,255,255,.14)}
.footer-desc{max-width:330px;margin-top:18px;color:#788694;font-size:clamp(16px,1.2vw,19px);line-height:1.7}
.footer-columns{display:flex;gap:80px;flex-wrap:wrap}
.footer-col h4{font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#fff;margin-bottom:17px}
.footer-col a{display:block;color:#7e8b97;font-size:11px;margin:10px 0;transition:.2s}
.footer-col a:hover{color:var(--sun)}
.footer-bottom{display:flex;justify-content:space-between;padding-top:20px;color:#566574;font-size:8px;letter-spacing:.15em}

/* ---------------- REVEAL (visible by default — IntersectionObserver only polishes) ---------------- */
.reveal{opacity:1;transform:none;transition:opacity .6s ease,transform .6s ease}
.reveal.in{opacity:1;transform:none}

/* ---------------- RTL SECTION MIRRORING ---------------- */
html[dir="rtl"] .project-feature{grid-template-columns:.75fr 1.55fr}
html[dir="rtl"] .project-index{left:auto;right:25px}
html[dir="rtl"] .project-caption{left:auto;right:35px}
html[dir="rtl"] .process:before{right:auto;left:-300px}
html[dir="rtl"] .intro-grid{grid-template-columns:1.2fr .8fr}
html[dir="rtl"] .footer-bottom{flex-direction:row}

/* ---------------- RESPONSIVE ---------------- */
@media(max-width:1024px){
    .hero{min-height:700px}
    .hero-photo{display:none;}
    .hero-content{width:100%;padding-top:160px;padding-left:20px;padding-right:20px;}
    .hero h1{font-size:clamp(50px,10vw,90px);max-width:none;}
    .hero-description{width:100%;max-width:420px}
    .hero-code{width:240px;padding:16px;right:20px;bottom:30px}
    .intro-grid{grid-template-columns:1fr;gap:50px}
    .solution{grid-template-columns:60px 1fr 1fr 50px;gap:16px}
    .featured-grid{grid-template-columns:repeat(2,1fr);gap:20px}
    .project-feature{grid-template-columns:1fr;min-height:560px}
    .project-info{padding:40px 30px}
    .video-section{gap:34px}
    .video-info-bar{flex-direction:column;align-items:flex-start;gap:18px}
    .video-divider{width:24px;height:1px}
    .cta-inner{flex-direction:column;align-items:flex-start;gap:30px}
}
@media(max-width:768px){
    .hero{min-height:640px;height:auto;padding-bottom:80px}
    .hero-content{padding-top:100px}
    .hero h1{font-size:clamp(45px,12vw,70px)}
    .hero-actions{flex-direction:column;align-items:flex-start}
    .hero-code{position:static;width:100%;max-width:420px;margin:40px auto 0;text-align:left}
    html[dir="rtl"] .hero-code{text-align:right}
    .blue-wash{width:360px;height:360px;right:-80px;bottom:-160px}
    html[dir="rtl"] .blue-wash{left:-80px;right:auto}
    .intro{padding:100px 0 80px}
    .solutions{padding:90px 0}
    .solution{grid-template-columns:1fr;min-height:auto;padding:20px;border:none}
    .solution:before{display:none}
    .solution:hover{padding:20px}
    .solution-list{border:none}
    .steps{grid-template-columns:repeat(2,1fr);gap:20px}
    .process{padding:90px 0}
    .featured-grid{grid-template-columns:1fr}
    .projects{padding:90px 0}
    .project-feature{min-height:420px}
    .project-index{left:16px;top:16px}
    .project-caption{left:20px;bottom:20px}
    .cta{padding:90px 0}
    .video-showcase{padding:80px 0}
    .video-header h2{font-size:clamp(28px,8vw,42px)}
    .video-info-bar{padding:20px 24px}
    .footer-columns{flex-direction:column;gap:40px}
    .footer-top{flex-direction:column;gap:30px}
}
@media(max-width:480px){
    .nav-inner{height:80px}
    .nav-links,.quote{display:none}
    .menu{display:block}
    .hero{min-height:560px}
    .hero-content{padding-top:90px}
    .hero h1{font-size:clamp(40px,14vw,60px)}
    .hero-description{font-size:15px}
    .hero-code{padding:14px}
    .intro{padding:80px 0 60px}
    .intro-big{font-size:clamp(32px,12vw,52px)}
    .solutions{padding:70px 0}
    .process{padding:70px 0}
    .steps{grid-template-columns:1fr}
    .process-heading{flex-direction:column;align-items:flex-start}
    .projects{padding:70px 0}
    .cta{padding:70px 0}
    .cta h2{font-size:clamp(32px,8vw,56px)}
    .video-showcase{padding:60px 0}
    .video-player{position:relative;height:300px}
    .video-poster{height:300px}
    .video-play{width:64px;height:64px}
    .video-corner{width:18px;height:18px;border-width:1.5px}
    .footer-bottom{flex-direction:column;gap:12px;text-align:center}
    html[dir="rtl"] .footer-bottom{text-align:right}
}
</style>
</head>

<body>

<x-header />

<main>

<!-- HERO -->
<section class="hero" id="home">
    <div class="hero-photo"></div>
    <div class="grid"></div>
    <div class="blue-wash"></div>

    <div class="hero-content">
        <div class="overline">
            {{ __('app.nav.services') }}
            @if($firstService?->getTranslated('title'))
                {{ $firstService?->getTranslated('title') }}
            @else
                {{ __('home.overline_fallback') }}
            @endif
        </div>

        <h1>
            {!! __('home.hero_headline') !!}
        </h1>

        <p class="hero-description">
            {{ __('home.hero_description') }}
        </p>

        <div class="hero-actions">
            <a href="#solutions" class="btn primary">{{ __('home.explore_solutions') }} <b>↗</b></a>
            <a href="{{ route('contact') }}" class="btn">{{ __('home.start_project') }} <b>→</b></a>
        </div>
    </div>

    <div class="hero-code">
        <div class="hero-code-top">
            <span>{{ __('home.project_capacity') }}</span>
            <span>01 / 04</span>
        </div>
        <div class="hero-code-line"></div>
        <div class="code-number">16<span>+</span></div>
        <div class="code-small">{{ __('home.years_experience') }}</div>
    </div>

    <div class="hero-scroll"><span></span> {{ __('home.scroll_explore') }}</div>
</section>

<!-- VIDEO SHOWCASE -->
<section class="video-showcase">
    <div class="video-section">
        <div class="video-header reveal">
            <div class="label">{{ __('home.video_label') }}</div>
            <h2>{!! __('home.video_heading') !!}</h2>
            <p>{{ __('home.video_sub') }}</p>
        </div>

        <div class="video-frame-wrap reveal">
            <div class="video-corner tl"></div>
            <div class="video-corner tr"></div>
            <div class="video-corner bl"></div>
            <div class="video-corner br"></div>
            <div class="video-blueprint"></div>
            <div class="video-player">
                <video poster="https://images.unsplash.com/photo-1504773480763-0c67b821e09f?auto=format&fit=crop&w=1200&q=80" preload="metadata" playsinline>
                    <source src="https://cdn.plyr.io/static/demo/View_From_A_Cloud_Moonrise.mp4" type="video/mp4">
                </video>
                <div class="video-poster"></div>
                <button class="video-play" type="button" aria-label="{{ __('home.video_play') }}"></button>
                <div class="video-duration">02:14</div>
            </div>
        </div>

        <div class="video-info-bar reveal">
            <div class="video-stat">
                <div class="num"><span>45,000</span> tons</div>
                <div class="t">{{ __('home.video_capacity') }}</div>
            </div>
            <div class="video-divider"></div>
            <div class="video-stat">
                <div class="num">Tekla 3D</div>
                <div class="t">{{ __('home.video_precision') }}</div>
            </div>
            <div class="video-divider"></div>
            <div class="video-stat">
                <div class="num">Turnkey</div>
                <div class="t">{{ __('home.video_execution') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- INTRO -->
<section class="intro">
    <div class="container intro-grid">
        <div class="reveal-left">
            <div class="label">{{ __('home.standard_label') }}</div>
        </div>

        <div class="reveal">
            <h2 class="intro-big">
                {!! __('home.standard_heading') !!}
            </h2>

            <p class="intro-text">
                {{ __('home.standard_body') }}
            </p>

            <div class="intro-line">{{ __('home.standard_line') }}</div>
        </div>
    </div>
</section>

<!-- MOVING STATEMENT -->
<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <span>
            {{ __('app.nav.services') }} <b>✦</b>
            {{ $firstService?->getTranslated('title') ? ucfirst($firstService?->getTranslated('title')) : __('home.word_manufacture') }} <b>✦</b>
            {{ __('home.word_install') }} <b>✦</b>
            {{ $firstService?->getTranslated('title') ? ucfirst($firstService?->getTranslated('title')) : __('home.word_manufacture') }} <b>✦</b>
        </span>
        <span>{{ __('home.word_engineer') }} <b>✦</b> {{ __('home.word_manufacture') }} <b>✦</b> {{ __('home.word_install') }} <b>✦</b> {{ __('home.word_engineer') }} <b>✦</b> {{ __('home.word_manufacture') }} <b>✦</b> {{ __('home.word_install') }} <b>✦</b></span>
    </div>
</div>

<!-- FEATURED GRIDS (News) -->
<section class="featured-grids reveal">
    <div class="container">
        <div class="section-heading" style="justify-content:flex-start;gap:20px;margin-bottom:40px">
            <div>
                <div class="label">{{ __('home.news_label') }}</div>
                <h2 style="font-size:clamp(32px,4vw,56px);line-height:1.1;letter-spacing:-.04em">{!! __('home.news_heading') !!}</h2>
            </div>
            <a href="{{ route('announcements.index') }}" class="btn btn-outline" style="padding:10px 20px;font-size:10px">{{ __('home.view_all') }} <b>→</b></a>
        </div>

        <div class="news-grid">
            @forelse($announcements ?? [] as $item)
                @php
                    $homeTypeLabels = [
                        'news' => __('home.type_news'),
                        'press_release' => __('home.type_press'),
                        'event' => __('home.type_event'),
                    ];
                    $homeType = $homeTypeLabels[$item->type] ?? ucfirst(str_replace('_', ' ', $item->type ?? 'news'));
                @endphp
                <article class="news-card reveal">
                    <div class="news-card-image">
                        @if($item->attachments->isNotEmpty())
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->attachments->first()->file_path ?? $item->attachments->first()->preview_image ?? '') }}" alt="{{ $item->getTranslated('title') }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80" alt="{{ $item->getTranslated('title') }}">
                        @endif
                        <span class="news-card-date">{{ $item->published_at?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="featured-content">
                        <span class="featured-tag">{{ $homeType }}</span>
                        <h3><a href="{{ route('announcements.show', $item->public_id) }}">{{ $item->getTranslated('title') }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->getTranslated('excerpt') ?: $item->getTranslated('content')), 110) }}</p>
                    </div>
                </article>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);border:1px dashed var(--line);border-radius:12px">
                    <p>{{ __('home.no_announcements') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FEATURED PROJECTS -->
<section class="featured-grids reveal">
    <div class="container">
        <div class="section-heading" style="justify-content:flex-start;gap:20px;margin-bottom:40px">
            <div>
                <div class="label">{{ __('home.work_label') }}</div>
                <h2 style="font-size:clamp(32px,4vw,56px);line-height:1.1;letter-spacing:-.04em">{{ __('home.featured_projects') }}</h2>
            </div>
            <a href="{{ route('projects') }}" class="btn btn-outline" style="padding:10px 20px;font-size:10px">{{ __('home.view_all') }} <b>→</b></a>
        </div>

        <div class="featured-grid">
            @forelse($featuredProjects ?? [] as $project)
                <a class="featured-card reveal" href="{{ route('projects') }}">
                    <div class="featured-image">
                        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80" alt="{{ $project->getTitle() }}">
                        <span class="featured-badge">{{ __('home.project_small') }}</span>
                    </div>
                    <div class="featured-content">
                        <span class="featured-tag">{{ __('home.project_code') }}</span>
                        <h3>{{ $project->getTitle() }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($project->getDescription()), 90) }}</p>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);border:1px dashed var(--line);border-radius:12px">
                    <p>No featured projects available.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="featured-grids reveal">
    <div class="container">
        <div class="section-heading" style="justify-content:flex-start;gap:20px;margin-bottom:40px">
            <div>
                <div class="label">{{ __('home.products_label') }}</div>
                <h2 style="font-size:clamp(32px,4vw,56px);line-height:1.1;letter-spacing:-.04em">{{ __('home.featured_products') }}</h2>
            </div>
            <a href="{{ route('products') }}" class="btn btn-outline" style="padding:10px 20px;font-size:10px">{{ __('home.view_all') }} <b>→</b></a>
        </div>

        <div class="featured-grid">
            @forelse($featuredProducts ?? [] as $product)
                <a class="featured-card reveal" href="{{ route('products.show', $product->slug) }}">
                    <div class="featured-image">
                        @php $cover = $product->images->first(); @endphp
                        @if ($cover)
                            <img src="{{ asset($cover->image_path) }}" alt="{{ $product->getTitle() }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80" alt="{{ $product->getTitle() }}">
                        @endif
                        <span class="featured-badge">{{ __('home.project_small') }}</span>
                    </div>
                    <div class="featured-content">
                        <span class="featured-tag">{{ __('home.product_code') }}</span>
                        <h3>{{ $product->getTitle() }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($product->getDescription()), 90) }}</p>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);border:1px dashed var(--line);border-radius:12px">
                    <p>No featured products available.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FEATURED SERVICES -->
<section class="featured-grids reveal">
    <div class="container">
        <div class="section-heading" style="justify-content:flex-start;gap:20px;margin-bottom:40px">
            <div>
                <div class="label">{{ __('home.services_label') }}</div>
                <h2 style="font-size:clamp(32px,4vw,56px);line-height:1.1;letter-spacing:-.04em">{{ __('home.featured_services') }}</h2>
            </div>
            <a href="{{ route('services') }}" class="btn btn-outline" style="padding:10px 20px;font-size:10px">{{ __('home.view_all') }} <b>→</b></a>
        </div>

        <div class="featured-grid">
            @forelse($featuredServices ?? [] as $service)
                <a class="featured-card reveal" href="{{ route('services') }}">
                    <div class="featured-image">
                        @if($service->attachments->isNotEmpty())
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($service->attachments->first()->file_path ?? $service->attachments->first()->preview_image ?? '') }}" alt="{{ $service->getTitle() }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1486325212027-8081e485225e?auto=format&fit=crop&w=800&q=80" alt="{{ $service->getTitle() }}">
                        @endif
                        <span class="featured-badge">{{ __('home.services_label') }}</span>
                    </div>
                    <div class="featured-content">
                        <span class="featured-tag">{{ __('home.project_code') }}</span>
                        <h3>{{ $service->getTitle() }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($service->getDescription()), 90) }}</p>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);border:1px dashed var(--line);border-radius:12px">
                    <p>No featured services available.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- SOLUTIONS -->
<section class="solutions" id="solutions">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <div class="label">{{ __('home.solutions_label') }}</div>
                <h2>{!! __('home.solutions_heading') !!}</h2>
            </div>
            <p>
                {{ __('home.solutions_sub') }}
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
                <div class="label" style="color:var(--sun)">{{ __('home.process_label') }}</div>
                <h2>{!! __('home.process_heading') !!}</h2>
            </div>
            <p>
                {{ __('home.process_sub') }}
            </p>
        </div>

        <div class="steps">
            <div class="step reveal">
                <div class="step-dot">01</div>
                <div><h3>{{ __('home.step_consult') }}</h3><p>{{ __('home.step_consult_desc') }}</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">02</div>
                <div><h3>{{ __('home.step_engineer') }}</h3><p>{{ __('home.step_engineer_desc') }}</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">03</div>
                <div><h3>{{ __('home.step_manufacture') }}</h3><p>{{ __('home.step_manufacture_desc') }}</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">04</div>
                <div><h3>{{ __('home.step_install') }}</h3><p>{{ __('home.step_install_desc') }}</p></div>
            </div>
            <div class="step reveal">
                <div class="step-dot">05</div>
                <div><h3>{{ __('home.step_support') }}</h3><p>{{ __('home.step_support_desc') }}</p></div>
            </div>
        </div>
    </div>
</section>

<!-- PROJECT -->
<section class="projects" id="projects">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <div class="label">{{ __('home.work_label') }}</div>
                <h2>{!! __('home.work_heading') !!}</h2>
            </div>
            <p>{{ __('home.work_sub') }}</p>
        </div>

        <div class="project-feature reveal">
            <div class="project-visual">
                <div class="project-index">{{ __('home.project_code') }}</div>
                <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1700&q=90" alt="{{ __('home.img_alt') }}">
                <div class="project-caption">
                    <small>{{ __('home.project_small') }}</small>
                    <h3>{{ __('home.project_built') }}</h3>
                </div>
            </div>

            <div class="project-info">
                <div class="label">{{ __('home.featured_label') }}</div>
                <h2>{!! __('home.featured_heading') !!}</h2>
                <p>
                    {{ __('home.featured_body') }}
                </p>
                <a href="{{ route('projects') }}">{{ __('home.view_project') }} →</a>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta" id="contact">
    <div class="cta-accent"></div>
    <div class="container cta-inner reveal">
        <div>
            <div class="label">{{ __('home.cta_label') }}</div>
            <h2>{!! __('home.cta_heading_home') !!}</h2>
        </div>
        <div style="display:flex;gap:15px;flex-wrap:wrap;">
            <a href="{{ route('contact') }}" class="btn" style="background:var(--ink);color:var(--sun);border-color:var(--ink);">{{ __('app.nav.request_quote') }} <b>↗</b></a>
            <a href="mailto:info@beta-industries.com" class="btn" style="background:transparent;color:var(--ink);border-color:var(--ink);">{{ __('home.talk_engineering') }} <b>✉</b></a>
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
                    {{ __('home.footer_desc') }}
                </p>
            </div>

            <div class="footer-columns">
                <div class="footer-col">
                    <h4>{{ __('home.footer_explore') }}</h4>
                    <a href="{{ route('services') }}">{{ __('app.footer.services') }}</a>
                    <a href="#process">{{ __('home.footer_capabilities') }}</a>
                    <a href="{{ route('projects') }}">{{ __('app.nav.projects') }}</a>
                </div>
                <div class="footer-col">
                    <h4>{{ __('app.footer.company') }}</h4>
                    <a href="{{ route('about') }}">{{ __('app.nav.about') }}</a>
                    <a href="{{ route('careers') }}">{{ __('app.nav.careers') }}</a>
                    <a href="{{ route('announcements.index') }}">{{ __('app.nav.announcements') }}</a>
                </div>
                <div class="footer-col">
                    <h4>{{ __('app.nav.contact') }}</h4>
                    <a href="{{ route('contact') }}">{{ __('app.nav.request_quote') }}</a>
                    <a href="mailto:info@beta-industries.com">{{ __('home.footer_email') }}</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} BETA — {{ __('app.footer.rights') }}</span>
            <span>{{ __('app.footer.tagline') }}</span>
        </div>
    </div>
</footer>

<script>
/* Native reveal animation — no GSAP, no library. Elements visible by default. */
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add("in");
        }
    });
}, {threshold:.12});

document.querySelectorAll(".reveal").forEach(el => {
    revealObserver.observe(el);
});

/* Video player toggle — swap poster for HTML5 video on play */
const videoPlayer = document.querySelector('.video-player');
const videoEl = document.querySelector('.video-player video');
const playBtn = document.querySelector('.video-play');
if(videoPlayer && playBtn && videoEl){
    playBtn.addEventListener('click', function(){
        videoPlayer.classList.add('video-active');
        videoEl.play().catch(function(){});
    });
}

/* Hero photo parallax */
const heroPhoto = document.querySelector(".hero-photo");
window.addEventListener("scroll", function(){
    if(window.scrollY < window.innerHeight && heroPhoto){
        heroPhoto.style.transform = `translateY(${window.scrollY * 0.12}px)`;
    }
}, {passive:true});
</script>

<x-success-modal />
</body>
</html>
