<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('projects.seo_title') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<style>
:root { --sun: #F2A900; --ocean: #00205B; --sky: #307FE2; --ink: #071321; --paper: #F4F2EB; --white: #FFFFFF; --muted: #66717c; --line: rgba(7, 19, 33, 0.15); --max: 1380px; --header-height: 92px; }
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
.inner-hero { background: var(--ocean); color: var(--white); padding: 120px 0 100px; text-align: center; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }

/* Filter Buttons */
.filter-bar { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: 50px; }
.filter-btn { padding: 12px 24px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; border: 1px solid var(--line); background: var(--white); transition: 0.3s; }
.filter-btn:hover, .filter-btn.active { background: var(--ink); color: var(--sun); border-color: var(--ink); }

/* Projects Grid */
.projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 30px; }
.project-card { background: var(--white); border: 1px solid var(--line); overflow: hidden; position: relative; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease; }
.project-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.project-img-box { height: 260px; overflow: hidden; position: relative; }
.project-img-box img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s ease; }
.project-card:hover .project-img-box img { transform: scale(1.08); }
.project-badge { position: absolute; top: 15px; left: 15px; background: var(--ink); color: var(--sun); font-size: 9px; font-weight: 800; padding: 6px 12px; text-transform: uppercase; letter-spacing: 0.1em; z-index: 2; }
html[dir="rtl"] .project-badge { left: auto; right: 15px; }
.project-content { padding: 30px; }
.project-content h3 { font-size: 24px; color: var(--ink); margin-bottom: 10px; }
.project-content p { font-size: 14px; color: var(--muted); margin-bottom: 20px; line-height: 1.6; }
.project-link { font-size: 11px; font-weight: 800; color: var(--ocean); text-transform: uppercase; letter-spacing: 0.1em; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
.project-card:hover .project-link { color: var(--sun); }

/* Sectors Grid Section */
.sectors-sec { background: var(--ink); color: var(--white); }
.sectors-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; margin-top: 40px; }
.sector-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 30px; transition: 0.4s; }
.sector-card:hover { background: var(--sun); color: var(--ink); transform: translateY(-5px); border-color: var(--sun); }
.sector-card h4 { font-size: 20px; margin-bottom: 8px; }
.sector-card p { font-size: 13px; opacity: 0.8; }
.sector-card:hover p { opacity: 1; color: var(--ink); }

/* Modal Case Study */
.modal-overlay { position: fixed; inset: 0; background: rgba(7, 19, 33, 0.85); backdrop-filter: blur(8px); z-index: 2000; opacity: 0; pointer-events: none; transition: opacity 0.4s ease; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-overlay.active { opacity: 1; pointer-events: all; }
.modal-card { background: var(--white); width: min(900px, 100%); max-height: 90vh; overflow-y: auto; border: 1px solid var(--line); position: relative; transform: translateY(40px); transition: transform 0.4s ease; padding: 50px; }
.modal-overlay.active .modal-card { transform: translateY(0); }
.modal-close { position: absolute; top: 20px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; color: var(--ink); }
html[dir="rtl"] .modal-close { right: auto; left: 20px; }

footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }
@media(max-width:768px){ .projects-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<x-header />

<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label">{!! __('projects.p.label') !!}</div>
            <h1>{!! __('projects.p.title') !!}</h1>
        </div>
    </section>

    <!-- SECTION 1: PROJECTS WITH FILTERING -->
    <section class="section-pad">
        <div class="container">
            <div class="filter-bar gsap-fade-up">
                <button class="filter-btn active" onclick="filterProjects('all')">{!! __('projects.p.fAll') !!}</button>
                <button class="filter-btn" onclick="filterProjects('industrial')">{!! __('projects.p.fInd') !!}</button>
                <button class="filter-btn" onclick="filterProjects('commercial')">{!! __('projects.p.fCom') !!}</button>
                <button class="filter-btn" onclick="filterProjects('infra')">{!! __('projects.p.fInf') !!}</button>
            </div>

            <div class="projects-grid">
                @foreach ($projects as $project)
                    <div class="project-card gsap-fade-up {{ $project->category }}">
                        <div class="project-img-box">
                            <span class="project-badge">{{ ucfirst(__('categories.'.$project->category)) }}</span>
                            <img src="{{ $project->image_path }}" alt="{{ $project->getTranslated('title') }}">
                        </div>
                        <div class="project-content">
                            <h3>{{ $project->getTranslated('title') }}</h3>
                            <p>{{ $project->getTranslated('description') }}</p>
                            <button class="project-link" onclick="openModal('{{ $project->public_id }}')">{!! __('projects.p.readCase') !!}</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 2: SECTORS SERVED (Merged from industries.html) -->
    <section class="section-pad sectors-sec">
        <div class="container">
            <div class="label" style="color:var(--sun)">{!! __('projects.sec.label') !!}</div>
            <h2 style="font-size:38px; line-height:1.1;">{!! __('projects.sec.title') !!}</h2>
            <div class="sectors-grid">
                <div class="sector-card gsap-fade-up"><h4>{!! __('projects.sec.s1') !!}</h4><p>{!! __('projects.sec.s1d') !!}</p></div>
                <div class="sector-card gsap-fade-up"><h4>{!! __('projects.sec.s2') !!}</h4><p>{!! __('projects.sec.s2d') !!}</p></div>
                <div class="sector-card gsap-fade-up"><h4>{!! __('projects.sec.s3') !!}</h4><p>{!! __('projects.sec.s3d') !!}</p></div>
                <div class="sector-card gsap-fade-up"><h4>{!! __('projects.sec.s4') !!}</h4><p>{!! __('projects.sec.s4d') !!}</p></div>
            </div>
        </div>
    </section>
</main>

<!-- MODAL POPUP FOR CASE STUDIES -->
<div class="modal-overlay" id="caseModal">
    <div class="modal-card">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div id="modalContent"></div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span>{!! __('app.footer.tagline') !!}</span></div>
    </div>
</footer>

<script>
    const modalData = {
        {{ $projects->map(function ($p) {
            $desc = $p->description[app()->getLocale() ?? 'en'];
            $cat = ucfirst($p->category);
            return "'" . $p->public_id . "': { title: '" . $p->getTranslated('title') . "', location: '" . ucfirst($p->client_name) . "', scope: '" . addslashes($desc) . "', desc: '" . addslashes($desc) . "' };
        })->implode(',') }
    };

    function filterProjects(cat) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        document.querySelectorAll('.project-card').forEach(card => {
            if(cat === 'all' || card.dataset.category === cat) {
                gsap.to(card, { display: 'block', opacity: 1, scale: 1, duration: 0.4 });
            } else {
                gsap.to(card, { opacity: 0, scale: 0.9, duration: 0.3, onComplete: () => card.style.display = 'none' });
            }
        });
    }

    function openModal(id) {
        const data = modalData[id];
        if (!data) return;
        document.getElementById('modalContent').innerHTML = `
            <h2 style="font-size:32px; margin-bottom:10px;">${data.title}</h2>
            <p style="color:var(--ocean); font-weight:bold; margin-bottom:20px;">📍 ${data.location}</p>
            <p style="margin-bottom:15px;"><strong>Scope:</strong> ${data.scope}</p>
            <p style="color:var(--muted); line-height:1.8;">${data.desc}</p>
        `;
        document.getElementById('caseModal').classList.add('active');
    }

    function closeModal() { document.getElementById('caseModal').classList.remove('active'); }


    gsap.registerPlugin(ScrollTrigger);
    document.addEventListener("DOMContentLoaded", () => { gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1 }); gsap.fromTo('.gsap-fade-up', { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.gsap-fade-up', start: 'top 85%' } }); });
</script>
<x-success-modal />
</body>
</html>