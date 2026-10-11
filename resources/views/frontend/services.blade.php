<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Services & Custom Solutions</title>
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

.nav { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: var(--ink); color: var(--white); border-bottom: 1px solid rgba(255,255,255,.1); }
.nav-inner { width: min(var(--max), calc(100% - 64px)); height: var(--header-height); margin: auto; display: flex; align-items: center; justify-content: space-between; }
.brand { display: flex; align-items: center; gap: 12px; font-size: 26px; font-weight: 800; letter-spacing: .08em; }
.brand-mark { width: 30px; height: 30px; position: relative; transform: skew(-14deg) rotate(45deg); border: 3px solid var(--sun); }
.brand-mark:before { content: ""; position: absolute; left: 6px; top: 2px; width: 3px; height: 20px; background: var(--sun); }
.nav-links { display: flex; gap: 32px; } .nav-links a { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .72; }
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
.lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; } .lang-switch .active-lang { opacity: 1; color: var(--sun); }
.quote { background: var(--sun); color: var(--ink); padding: 14px 19px; font-size: 10px; font-weight: 800; text-transform: uppercase; }

main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
.container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
.section-pad { padding: 100px 0; }
.inner-hero { background: var(--midnight); color: var(--white); padding: 120px 0 100px; text-align: center; }
.inner-hero h1 { font-size: clamp(50px, 6vw, 80px); line-height: 1; margin-bottom: 20px; }
.label { font-size: 10px; font-weight: 800; color: var(--sun); letter-spacing: .23em; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }

.journey-step { display: flex; gap: 40px; margin-bottom: 40px; }
.step-num { width: 60px; height: 60px; background: var(--ocean); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; flex-shrink: 0; }
.step-body { background: var(--white); padding: 30px; border: 1px solid var(--line); flex-grow: 1; }

.custom-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 40px; }
.custom-card { background: var(--white); border: 1px solid var(--line); padding: 35px; transition: 0.3s; }
.custom-card:hover { border-color: var(--sun); transform: translateY(-5px); }

footer { padding: 80px 0 30px; background: var(--ink); color: var(--white); }
.footer-top { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 45px; }
.footer-bottom { display: flex; justify-content: space-between; padding-top: 25px; font-size: 10px; color: #566574; text-transform: uppercase; letter-spacing: .15em; }
</style>
</head>
<body>
<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a>
        <nav class="nav-links">
            <a href="index.html" data-i18n="nav.home">Home</a>
            <a href="about.html" data-i18n="nav.about">About</a>
            <a href="services.html" class="active" data-i18n="nav.services">Services & Solutions</a>
            <a href="products.html" data-i18n="nav.products">Products</a>
            <a href="projects.html" data-i18n="nav.projects">Projects</a>
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
            <div class="label" data-i18n="s.label">End-to-End Capabilities</div>
            <h1 data-i18n="s.title">Consultation to <br>Final Erection.</h1>
        </div>
    </section>

    <!-- SECTION 1: THE JOURNEY -->
    <section class="section-pad">
        <div class="container">
            <div class="label" style="color:var(--ocean)" data-i18n="s.jlabel">01 / Delivery Journey</div>
            <h2 style="font-size:36px; margin-bottom:40px;" data-i18n="s.jtitle">How We Execute Your Project</h2>
            
            <div class="journey-step gsap-fade-up">
                <div class="step-num">01</div>
                <div class="step-body">
                    <h3 style="font-size:22px; margin-bottom:10px;" data-i18n="s.st1.t">Engineering & Shop Drawings</h3>
                    <p style="color:var(--muted);" data-i18n="s.st1.d">In-house structural analysis, Tekla 3D modeling, and approved shop drawings.</p>
                </div>
            </div>
            <div class="journey-step gsap-fade-up">
                <div class="step-num">02</div>
                <div class="step-body">
                    <h3 style="font-size:22px; margin-bottom:10px;" data-i18n="s.st2.t">Precision Fabrication</h3>
                    <p style="color:var(--muted);" data-i18n="s.st2.d">CNC plasma cutting, automated beam welding, and quality assembly.</p>
                </div>
            </div>
            <div class="journey-step gsap-fade-up">
                <div class="step-num">03</div>
                <div class="step-body">
                    <h3 style="font-size:22px; margin-bottom:10px;" data-i18n="s.st3.t">Site Erection & Handover</h3>
                    <p style="color:var(--muted);" data-i18n="s.st3.d">Certified erection crews equipped for heavy lifting and anchor installation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: CUSTOM SOLUTIONS -->
    <section class="section-pad" style="background:var(--white);">
        <div class="container">
            <div class="label" style="color:var(--ocean)" data-i18n="s.clabel">02 / Custom Solutions</div>
            <h2 style="font-size:36px;" data-i18n="s.ctitle">Built Around Special Requirements</h2>
            <div class="custom-grid">
                <div class="custom-card gsap-fade-up">
                    <h3 style="font-size:20px; margin-bottom:10px;" data-i18n="s.c1.t">Non-Standard Dimensions</h3>
                    <p style="font-size:14px; color:var(--muted);" data-i18n="s.c1.d">Bespoke heavy framing tailored to unique site constraints.</p>
                </div>
                <div class="custom-card gsap-fade-up">
                    <h3 style="font-size:20px; margin-bottom:10px;" data-i18n="s.c2.t">Special Protective Coatings</h3>
                    <p style="font-size:14px; color:var(--muted);" data-i18n="s.c2.d">Hot-dip galvanizing, intumescent fireproofing, and marine-grade paint.</p>
                </div>
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
        en: { nav: { home: "Home", about: "About", services: "Services & Solutions", products: "Products", projects: "Projects", contact: "Contact", quote: "Request Quote" }, s: { label: "End-to-End Capabilities", title: "Consultation to <br>Final Erection.", jlabel: "01 / Delivery Journey", jtitle: "How We Execute Your Project", st1: { t: "Engineering & Shop Drawings", d: "In-house structural analysis, Tekla 3D modeling, and approved shop drawings." }, st2: { t: "Precision Fabrication", d: "CNC plasma cutting, automated beam welding, and quality assembly." }, st3: { t: "Site Erection & Handover", d: "Certified erection crews equipped for heavy lifting and anchor installation." }, clabel: "02 / Custom Solutions", ctitle: "Built Around Special Requirements", c1: { t: "Non-Standard Dimensions", d: "Bespoke heavy framing tailored to unique site constraints." }, c2: { t: "Special Protective Coatings", d: "Hot-dip galvanizing, intumescent fireproofing, and marine-grade paint." } }, footer: { tagline: "Engineer. Manufacture. Install." } },
        ar: { nav: { home: "الرئيسية", about: "عن الشركة", services: "الخدمات والحلول", products: "المنتجات", projects: "المشاريع", contact: "اتصل بنا", quote: "طلب عرض سعر" }, s: { label: "قدرات شاملة", title: "من الاستشارة حتى <br>التركيب النهائي.", jlabel: "01 / رحلة التنفيذ", jtitle: "كيف ننفذ مشروعك", st1: { t: "الهندسة والرسومات التنفيذية", d: "تحليل إنشائي داخلي، نمذجة ثلاثية الأبعاد عبر Tekla، ورسومات معتمدة." }, st2: { t: "التصنيع الدقيق", d: "قطع البلازما بالكمبيوتر، لحام الآلات التلقائي، وتجميع عالي الجودة[cite: 2]." }, st3: { t: "التركيب والتسليم في الموقع", d: "فرق تركيب معتمدة مجهزة للرفع الثقيل والتثبيت الآمن[cite: 2]." }, clabel: "02 / حلول مخصصة", ctitle: "مصممة وفقًا لمتطلبات خاصة", c1: { t: "أبعاد غير قياسية", d: "إطارات ثقيلة مخصصة حسب قيود الموقع الفريدة[cite: 2]." }, c2: { t: "طلاءات حماية خاصة", d: "جلفنة بالغمس الساخن، طلاءات مقاومة للحريق، ودهانات بحرية[cite: 2]." } }, footer: { tagline: "هندسة. تصنيع. تركيب." } }
    };
    let currentLang = localStorage.getItem('beta_lang') || 'en'; gsap.registerPlugin(ScrollTrigger);
    function resolvePath(p, o) { return p.split('.').reduce((a, b) => a && a[b], o); }
    function updateLang() { document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr'; document.documentElement.lang = currentLang; document.getElementById('lang-en').className = currentLang === 'en' ? 'active-lang' : ''; document.getElementById('lang-ar').className = currentLang === 'ar' ? 'active-lang' : ''; document.querySelectorAll('[data-i18n]').forEach(e => { const t = resolvePath(e.getAttribute('data-i18n'), translations[currentLang]); if(t) e.innerHTML = t; }); setTimeout(() => ScrollTrigger.refresh(), 150); }
    function toggleLanguage() { currentLang = currentLang === 'en' ? 'ar' : 'en'; localStorage.setItem('beta_lang', currentLang); updateLang(); }
    document.addEventListener("DOMContentLoaded", () => { updateLang(); gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8 }); gsap.fromTo('.gsap-fade-up', { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, scrollTrigger: { trigger: '.gsap-fade-up', start: 'top 85%' } }); });
</script>
</body>
</html>