<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Featured Projects & Sectors</title>
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

<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a>
        <nav class="nav-links">
            <a href="index.html" data-i18n="nav.home">Home</a>
            <a href="about.html" data-i18n="nav.about">About</a>
            <a href="services.html" data-i18n="nav.services">Services</a>
            <a href="products.html" data-i18n="nav.products">Products</a>
            <a href="projects.html" class="active" data-i18n="nav.projects">Projects & Sectors</a>
            <a href="contact.html" data-i18n="nav.contact">Contact</a>
        </nav>
        <div class="nav-right">
            <button class="lang-switch" onclick="toggleLanguage()"><span id="lang-en" class="active-lang">EN</span> | <span id="lang-ar">عربي</span></button>
            <a class="quote" href="contact.html" data-i18n="nav.quote">Request Quote</a>
        </div>
    </div>
</header>

<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label" data-i18n="p.label">Proven Footprint</div>
            <h1 data-i18n="p.title">Engineered Heavy <br>Structures.</h1>
        </div>
    </section>

    <!-- SECTION 1: PROJECTS WITH FILTERING -->
    <section class="section-pad">
        <div class="container">
            <div class="filter-bar gsap-fade-up">
                <button class="filter-btn active" onclick="filterProjects('all')" data-i18n="p.fAll">All Sectors</button>
                <button class="filter-btn" onclick="filterProjects('industrial')" data-i18n="p.fInd">Industrial & Logistics</button>
                <button class="filter-btn" onclick="filterProjects('commercial')" data-i18n="p.fCom">Commercial</button>
                <button class="filter-btn" onclick="filterProjects('infra')" data-i18n="p.fInf">Infrastructure</button>
            </div>

            <div class="projects-grid">
                <div class="project-card gsap-fade-up" data-category="industrial">
                    <div class="project-img-box">
                        <span class="project-badge">Industrial</span>
                        <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=800&q=80" alt="Logistics Hub">
                    </div>
                    <div class="project-content">
                        <h3 data-i18n="p.p1.t">Al-Zamil Logistics Hub</h3>
                        <p data-i18n="p.p1.d">10,000 sqm heavy steel warehouse facility engineered with high wind-load tolerance in Jeddah, KSA[cite: 2].</p>
                        <button class="project-link" onclick="openModal('p1')" data-i18n="p.readCase">View Case Study →</button>
                    </div>
                </div>

                <div class="project-card gsap-fade-up" data-category="commercial">
                    <div class="project-img-box">
                        <span class="project-badge">Commercial</span>
                        <img src="https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=800&q=80" alt="Commercial Tower">
                    </div>
                    <div class="project-content">
                        <h3 data-i18n="p.p2.t">Cairo Executive Tower</h3>
                        <p data-i18n="p.p2.d">Complex internal structural mezzanine and architectural steel framing for multi-story headquarters[cite: 2].</p>
                        <button class="project-link" onclick="openModal('p2')" data-i18n="p.readCase">View Case Study →</button>
                    </div>
                </div>

                <div class="project-card gsap-fade-up" data-category="infra">
                    <div class="project-img-box">
                        <span class="project-badge">Infrastructure</span>
                        <img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80" alt="Bridge Framework">
                    </div>
                    <div class="project-content">
                        <h3 data-i18n="p.p3.t">Highway Flyover Beams</h3>
                        <p data-i18n="p.p3.d">Heavy plate girder fabrication and galvanizing for critical transport infrastructure[cite: 2].</p>
                        <button class="project-link" onclick="openModal('p3')" data-i18n="p.readCase">View Case Study →</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: SECTORS SERVED (Merged from industries.html) -->
    <section class="section-pad sectors-sec">
        <div class="container">
            <div class="label" style="color:var(--sun)" data-i18n="sec.label">Cross-Industry Expertise</div>
            <h2 style="font-size:38px; line-height:1.1;" data-i18n="sec.title">Sectors We Empower Across Egypt & KSA</h2>
            <div class="sectors-grid">
                <div class="sector-card gsap-fade-up"><h4 data-i18n="sec.s1">Industrial & Manufacturing</h4><p data-i18n="sec.s1d">Factories, assembly lines, overhead crane gantries[cite: 2].</p></div>
                <div class="sector-card gsap-fade-up"><h4 data-i18n="sec.s2">Logistics & Warehousing</h4><p data-i18n="sec.s2d">Wide-span clear buildings, high-bay distribution hubs[cite: 2].</p></div>
                <div class="sector-card gsap-fade-up"><h4 data-i18n="sec.s3">Energy & Petrochemicals</h4><p data-i18n="sec.s3d">Heavy pipe racks, platform structures, protective coatings[cite: 2].</p></div>
                <div class="sector-card gsap-fade-up"><h4 data-i18n="sec.s4">Commercial & Real Estate</h4><p data-i18n="sec.s4d">Multi-story framing, high-end canopies, decorative facades[cite: 2].</p></div>
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
        <div class="footer-top"><a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span data-i18n="footer.tagline">Engineer. Manufacture. Install.</span></div>
    </div>
</footer>

<script>
    const modalData = {
        p1: { title: "Al-Zamil Logistics Hub", location: "Jeddah, KSA", scope: "10,000 sqm steel fabrication, galvanizing, site erection", desc: "Delivered under tight 8-month deadline utilizing ASTM A992 structural steel designed for coastal wind environments[cite: 2]." },
        p2: { title: "Cairo Executive Tower", location: "Cairo, Egypt", scope: "Internal structural steel framing & mezzanine decks", desc: "Engineered high-yield interior columns to allow column-free floor plans across 5 executive floors[cite: 2]." },
        p3: { title: "Highway Flyover Beams", location: "Giza, Egypt", scope: "Heavy plate girders & bridge deck supports", desc: "Fabricated over 2,500 tons of heavy structural steel with 100% NDT ultrasonic weld inspection[cite: 2]." }
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
        document.getElementById('modalContent').innerHTML = `
            <h2 style="font-size:32px; margin-bottom:10px;">${data.title}</h2>
            <p style="color:var(--ocean); font-weight:bold; margin-bottom:20px;">📍 ${data.location}</p>
            <p style="margin-bottom:15px;"><strong>Scope:</strong> ${data.scope}</p>
            <p style="color:var(--muted); line-height:1.8;">${data.desc}</p>
        `;
        document.getElementById('caseModal').classList.add('active');
    }

    function closeModal() { document.getElementById('caseModal').classList.remove('active'); }

    const translations = {
        en: { nav: { home: "Home", about: "About", services: "Services", products: "Products", projects: "Projects & Sectors", contact: "Contact", quote: "Request Quote" }, p: { label: "Proven Footprint", title: "Engineered Heavy <br>Structures.", fAll: "All Sectors", fInd: "Industrial & Logistics", fCom: "Commercial", fInf: "Infrastructure", readCase: "View Case Study →", p1: { t: "Al-Zamil Logistics Hub", d: "10,000 sqm heavy steel warehouse facility engineered with high wind-load tolerance in Jeddah, KSA[cite: 2]." }, p2: { t: "Cairo Executive Tower", d: "Complex internal structural mezzanine and architectural steel framing for multi-story headquarters[cite: 2]." }, p3: { t: "Highway Flyover Beams", d: "Heavy plate girder fabrication and galvanizing for critical transport infrastructure[cite: 2]." } }, sec: { label: "Cross-Industry Expertise", title: "Sectors We Empower Across Egypt & KSA", s1: "Industrial & Manufacturing", s1d: "Factories, assembly lines, overhead crane gantries[cite: 2].", s2: "Logistics & Warehousing", s2d: "Wide-span clear buildings, high-bay distribution hubs[cite: 2].", s3: "Energy & Petrochemicals", s3d: "Heavy pipe racks, platform structures, protective coatings[cite: 2].", s4: "Commercial & Real Estate", s4d: "Multi-story framing, high-end canopies, decorative facades[cite: 2]." }, footer: { tagline: "Engineer. Manufacture. Install." } },
        ar: { nav: { home: "الرئيسية", about: "عن الشركة", services: "الخدمات", products: "المنتجات", projects: "المشاريع والقطاعات", contact: "اتصل بنا", quote: "طلب عرض سعر" }, p: { label: "سجل حافل", title: "هياكل ثقيلة <br>مهندسة بدقة.", fAll: "جميع القطاعات", fInd: "الصناعي واللوجستي", fCom: "التجاري", fInf: "البنية التحتية", readCase: "عرض دراسة الحالة ←", p1: { t: "مركز لوجستيات الزامل", d: "مستودع صلب ثقيل بمساحة 10,000 متر مربع مصمم لتحمل الرياح العالية في جدة[cite: 2]." }, p2: { t: "برج القاهرة التنفيذي", d: "ميزانين إنشائي داخلي معقد وإطار صلب معماري للمقر الرئيسي[cite: 2]." }, p3: { t: "كمرات الكباري والطرُق", d: "تصنيع وتصفيح الكمرات اللوحية الثقيلة للبنية التحتية الحيوية للنقل[cite: 2]." } }, sec: { label: "خبرة متقاطعة", title: "القطاعات التي ندعمها في مصر والسعودية", s1: "الصناعة والتصنيع", s1d: "المصانع، خطوط التجميع، وهياكل الرافعة الجسرية[cite: 2].", s2: "اللوجستيات والمستودعات", s2d: "مباني واسعة الامتداد، ومراكز توزيع مرتفعة[cite: 2].", s3: "الطاقة والبتروكيماويات", s3d: "حوامل الأنابيب الثقيلة، المنصات، وطلاءات الحماية[cite: 2].", s4: "التجاري والعقارات", s4d: "إطارات متعددة الطوابق، مظلات فاخرة، وواجهات ديكورية[cite: 2]." }, footer: { tagline: "هندسة. تصنيع. تركيب." } }
    };

    let currentLang = localStorage.getItem('beta_lang') || 'en';
    gsap.registerPlugin(ScrollTrigger);
    function resolvePath(p, o) { return p.split('.').reduce((a, b) => a && a[b], o); }
    function updateLang() { document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr'; document.documentElement.lang = currentLang; document.getElementById('lang-en').className = currentLang === 'en' ? 'active-lang' : ''; document.getElementById('lang-ar').className = currentLang === 'ar' ? 'active-lang' : ''; document.querySelectorAll('[data-i18n]').forEach(e => { const t = resolvePath(e.getAttribute('data-i18n'), translations[currentLang]); if(t) e.innerHTML = t; }); setTimeout(() => ScrollTrigger.refresh(), 150); }
    function toggleLanguage() { currentLang = currentLang === 'en' ? 'ar' : 'en'; localStorage.setItem('beta_lang', currentLang); updateLang(); }
    document.addEventListener("DOMContentLoaded", () => { updateLang(); gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1 }); gsap.fromTo('.gsap-fade-up', { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.gsap-fade-up', start: 'top 85%' } }); });
</script>
</body>
</html>