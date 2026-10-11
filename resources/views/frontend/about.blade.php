<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — About, Quality & Downloads</title>
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
<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a>
        <nav class="nav-links">
            <a href="index.html" data-i18n="nav.home">Home</a>
            <a href="about.html" class="active" data-i18n="nav.about">About Us</a>
            <a href="services.html" data-i18n="nav.services">Services</a>
            <a href="products.html" data-i18n="nav.products">Products</a>
            <a href="projects.html" data-i18n="nav.projects">Projects</a>
            <a href="contact.html" data-i18n="nav.contact">Contact</a>
        </nav>
        <div class="nav-right">
            <button class="lang-switch" onclick="toggleLanguage()"><span id="lang-en" class="active-lang">EN</span> | <span id="lang-ar">عربي</span></button>
            <a class="quote" href="contact.html" data-i18n="nav.quote">Request a Quote</a>
        </div>
    </div>
</header>

<main>
    <section class="inner-hero">
        <div class="container gsap-hero">
            <div class="label" data-i18n="about.heroLabel">01 / Story & Governance</div>
            <h1 data-i18n="about.heroTitle">Engineered Trust. <br>Proven Excellence.</h1>
            <p style="color: rgba(255,255,255,0.85); max-width:600px;" data-i18n="about.heroDesc">Discover our history, quality standards, safety certifications, and technical resources all in one place.</p>
        </div>
    </section>

    <!-- SECTION 1: STORY -->
    <section class="section-pad">
        <div class="container about-grid">
            <div class="gsap-fade-up">
                <div class="label" style="color:var(--ocean)" data-i18n="about.stdLabel">The BETA Standard</div>
                <h2 style="font-size:38px; line-height:1.1; margin-bottom:20px;" data-i18n="about.stdTitle">Precision is not a feature. It is the foundation.</h2>
                <p style="color:var(--muted); margin-bottom:15px;" data-i18n="about.desc1">Established to redefine industrial manufacturing, BETA specializes in engineering, manufacturing, and installing metal and steel structures.</p>
                <p style="color:var(--muted);" data-i18n="about.desc2">Our state-of-the-art facilities ensure every project meets rigorous Egyptian and Saudi structural standards.</p>
            </div>
            <div class="about-image gsap-fade-up">
                <img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=1000&q=80" alt="Factory Interior">
            </div>
        </div>
    </section>

    <!-- SECTION 2: QUALITY & STANDARDS -->
    <section class="section-pad" style="background:var(--white);">
        <div class="container">
            <div class="label" style="color:var(--ocean)" data-i18n="q.label">02 / Quality & Compliance</div>
            <h2 style="font-size:36px;" data-i18n="q.title">Uncompromising Quality Controls</h2>
            <div class="q-grid">
                <div class="q-card gsap-fade-up">
                    <h3 data-i18n="q.c1.t">Material Traceability</h3>
                    <p style="font-size:14px; color:var(--muted);" data-i18n="q.c1.d">Complete mill test certificate tracking for raw steel procurement.</p>
                </div>
                <div class="q-card gsap-fade-up">
                    <h3 data-i18n="q.c2.t">Certified Welding</h3>
                    <p style="font-size:14px; color:var(--muted);" data-i18n="q.c2.d">Operating under AWS and international structural welding codes.</p>
                </div>
                <div class="q-card gsap-fade-up">
                    <h3 data-i18n="q.c3.t">NDT & Inspection</h3>
                    <p style="font-size:14px; color:var(--muted);" data-i18n="q.c3.d">In-house non-destructive ultrasonic and magnetic particle testing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: DOWNLOADS -->
    <section class="section-pad">
        <div class="container">
            <div class="label" style="color:var(--ocean)" data-i18n="dl.label">03 / Resource Center</div>
            <h2 style="font-size:36px;" data-i18n="dl.title">Technical Downloads</h2>
            <div class="dl-grid">
                <div class="dl-card gsap-fade-up">
                    <h3 data-i18n="dl.d1">Company Profile PDF</h3>
                    <a href="#" class="btn-dl" data-i18n="dl.btn">Download</a>
                </div>
                <div class="dl-card gsap-fade-up">
                    <h3 data-i18n="dl.d2">ISO & Welding Certificates</h3>
                    <a href="#" class="btn-dl" data-i18n="dl.btn">Download</a>
                </div>
                <div class="dl-card gsap-fade-up">
                    <h3 data-i18n="dl.d3">Product Catalog 2026</h3>
                    <a href="#" class="btn-dl" data-i18n="dl.btn">Download</a>
                </div>
            </div>
        </div>
    </section>
        <section class="stats-banner gsap-fade-up">
        <div class="container stats-grid">
            <div class="stat-item">
                <h4>16</h4>
                <p data-i18n="stats.exp">Years of Experience</p>
            </div>
            <div class="stat-item">
                <h4>850+</h4>
                <p data-i18n="stats.projects">Projects Completed</p>
            </div>
            <div class="stat-item">
                <h4>50k</h4>
                <p data-i18n="stats.tons">Tons Fabricated</p>
            </div>
            <div class="stat-item">
                <h4>12</h4>
                <p data-i18n="stats.industries">Industries Served</p>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span data-i18n="footer.tagline">Engineer. Manufacture. Install.</span></div>
    </div>
</footer>

<script>
    const translations = {
        en: { nav: { home: "Home", about: "About Us", services: "Services", products: "Products", projects: "Projects", contact: "Contact", quote: "Request Quote" }, about: { heroLabel: "01 / Story & Governance", heroTitle: "Engineered Trust. <br>Proven Excellence.", heroDesc: "Discover our history, quality standards, safety certifications, and technical resources all in one place.", stdLabel: "The BETA Standard", stdTitle: "Precision is not a feature. It is the foundation.", desc1: "Established to redefine industrial manufacturing, BETA specializes in engineering, manufacturing, and installing metal and steel structures.", desc2: "Our state-of-the-art facilities ensure every project meets rigorous Egyptian and Saudi structural standards." }, q: { label: "02 / Quality & Compliance", title: "Uncompromising Quality Controls", c1: { t: "Material Traceability", d: "Complete mill test certificate tracking for raw steel procurement." }, c2: { t: "Certified Welding", d: "Operating under AWS and international structural welding codes." }, c3: { t: "NDT & Inspection", d: "In-house non-destructive ultrasonic and magnetic particle testing." } }, dl: { label: "03 / Resource Center", title: "Technical Downloads", d1: "Company Profile PDF", d2: "ISO & Welding Certificates", d3: "Product Catalog 2026", btn: "Download" }, footer: { tagline: "Engineer. Manufacture. Install." } },
        ar: { nav: { home: "الرئيسية", about: "عن الشركة", services: "الخدمات", products: "المنتجات", projects: "المشاريع", contact: "اتصل بنا", quote: "طلب عرض سعر" }, about: { heroLabel: "01 / القصة والحوكمة", heroTitle: "ثقة هندسية. <br>تميز مثبت.", heroDesc: "اكتشف تاريخنا، معايير الجودة، شهادات السلامة، والموارد التقنية في مكان واحد.", stdLabel: "معيار بيتا", stdTitle: "الدقة ليست ميزة. إنها الأساس.", desc1: "تأسست بيتا برؤية لإعادة تعريف التصنيع الصناعي، وتتخصص في هندسة وتصنيع وتركيب الهياكل المعدنية والفولاذية.", desc2: "تضمن مرافقنا الحديثة تلبية كل مشروع للمواصفات الهيكلية الصارمة في مصر والسعودية." }, q: { label: "02 / الجودة والامتثال", title: "ضوابط جودة لا تقبل المساومة", c1: { t: "تتبع المواد", d: "تتبع كامل لشهادات اختبار المصنع لجميع مشتريات الصلب الخام." }, c2: { t: "لحام معتمد", d: "العمل وفقًا لقواعد اللحام الهيكلي الدولية وشهادات AWS." }, c3: { t: "الاختبارات غير المدمرة", d: "فحوصات الموجات فوق الصوتية والجسيمات المغناطيسية داخل المصنع." } }, dl: { label: "03 / مركز الموارد", title: "التحميلات التقنية", d1: "الملف التعريفي للشركة (PDF)", d2: "شهادات ISO واللحام", d3: "كتالوج المنتجات 2026", btn: "تحميل" }, footer: { tagline: "هندسة. تصنيع. تركيب." } }
    };
    let currentLang = localStorage.getItem('beta_lang') || 'en'; gsap.registerPlugin(ScrollTrigger);
    function resolvePath(p, o) { return p.split('.').reduce((a, b) => a && a[b], o); }
    function updateLang() { document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr'; document.documentElement.lang = currentLang; document.getElementById('lang-en').className = currentLang === 'en' ? 'active-lang' : ''; document.getElementById('lang-ar').className = currentLang === 'ar' ? 'active-lang' : ''; document.querySelectorAll('[data-i18n]').forEach(e => { const t = resolvePath(e.getAttribute('data-i18n'), translations[currentLang]); if(t) e.innerHTML = t; }); setTimeout(() => ScrollTrigger.refresh(), 150); }
    function toggleLanguage() { currentLang = currentLang === 'en' ? 'ar' : 'en'; localStorage.setItem('beta_lang', currentLang); updateLang(); }
    document.addEventListener("DOMContentLoaded", () => { updateLang(); gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1 }); gsap.fromTo('.gsap-fade-up', { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.gsap-fade-up', start: 'top 85%' } }); });
</script>
</body>
</html>