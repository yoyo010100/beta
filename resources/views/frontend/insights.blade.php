<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Insights</title>
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
<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a>
        <nav class="nav-links">
            <a href="index.html" data-i18n="nav.home">Home</a>
            <a href="insights.html" class="active" data-i18n="nav.insights">Insights</a>
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
            <div class="label" data-i18n="ins.label">Content Hub</div>
            <h1 data-i18n="ins.title">Engineering <br>Insights.</h1>
        </div>
    </section>
    <section class="section-pad">
        <div class="container">
            <div class="filter-tags gsap-fade-up">
                <span data-i18n="ins.t1">Steel Structures</span>
                <span data-i18n="ins.t2">Metal Fabrication</span>
                <span data-i18n="ins.t3">Construction Solutions</span>
                <span data-i18n="ins.t4">Safety</span>
                <span data-i18n="ins.t5">Case Studies</span>
            </div>
            <div class="blog-grid">
                <div class="blog-card gsap-fade-up">
                    <div class="blog-img"><img src="https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=600&q=80" alt="Blog Image"></div>
                    <div class="blog-info">
                        <small data-i18n="ins.t1">Steel Structures</small>
                        <h3 data-i18n="ins.b1.title">Advancements in Load-Bearing Architectural Steel</h3>
                        <p data-i18n="ins.b1.desc">Exploring modern fabrication techniques that allow for wider spans and increased structural integrity in commercial buildings.</p>
                        <a href="insight-detail.html" data-i18n="ins.read">Read Article →</a>
                    </div>
                </div>
                <div class="blog-card gsap-fade-up">
                    <div class="blog-img"><img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80" alt="Blog Image"></div>
                    <div class="blog-info">
                        <small data-i18n="ins.t4">Safety</small>
                        <h3 data-i18n="ins.b2.title">Implementing HSE Standards in Heavy Fabrication</h3>
                        <p data-i18n="ins.b2.desc">A deep dive into BETA's safety protocols and how stringent regulations improve factory floor efficiency.</p>
                        <a href="insight-detail.html" data-i18n="ins.read">Read Article →</a>
                    </div>
                </div>
                <div class="blog-card gsap-fade-up">
                    <div class="blog-img"><img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=600&q=80" alt="Blog Image"></div>
                    <div class="blog-info">
                        <small data-i18n="ins.t5">Case Studies</small>
                        <h3 data-i18n="ins.b3.title">Delivering the Al-Zamil Logistics Hub</h3>
                        <p data-i18n="ins.b3.desc">How our engineering team designed and executed a 10,000-ton warehouse structure under strict deadlines.</p>
                        <a href="insight-detail.html" data-i18n="ins.read">Read Article →</a>
                    </div>
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
        en: { nav: { home: "Home", insights: "Insights", contact: "Contact", quote: "Request a Quote" }, ins: { label: "Content Hub", title: "Engineering <br>Insights.", t1: "Steel Structures", t2: "Metal Fabrication", t3: "Construction Solutions", t4: "Safety", t5: "Case Studies", b1: { title: "Advancements in Load-Bearing Architectural Steel", desc: "Exploring modern fabrication techniques that allow for wider spans and increased structural integrity in commercial buildings." }, b2: { title: "Implementing HSE Standards in Heavy Fabrication", desc: "A deep dive into BETA's safety protocols and how stringent regulations improve factory floor efficiency." }, b3: { title: "Delivering the Al-Zamil Logistics Hub", desc: "How our engineering team designed and executed a 10,000-ton warehouse structure under strict deadlines." }, read: "Read Article →" }, footer: { tagline: "Engineer. Manufacture. Install." } },
        ar: { nav: { home: "الرئيسية", insights: "الرؤى والمقالات", contact: "اتصل بنا", quote: "طلب عرض سعر" }, ins: { label: "مركز المحتوى", title: "رؤى <br>هندسية.", t1: "الهياكل الفولاذية", t2: "تشكيل المعادن", t3: "حلول البناء", t4: "السلامة", t5: "دراسات الحالة", b1: { title: "التطورات في الصلب المعماري الحامل للأوزان", desc: "استكشاف تقنيات التصنيع الحديثة التي تسمح بامتدادات أوسع وزيادة السلامة الهيكلية في المباني التجارية." }, b2: { title: "تطبيق معايير الصحة والسلامة في الصناعات الثقيلة", desc: "نظرة متعمقة على بروتوكولات السلامة في بيتا وكيف تعمل اللوائح الصارمة على تحسين كفاءة المصنع." }, b3: { title: "تسليم مركز لوجستيات الزامل", desc: "كيف قام فريقنا الهندسي بتصميم وتنفيذ هيكل مستودع بوزن 10,000 طن ضمن مواعيد نهائية صارمة." }, read: "قراءة المقال ←" }, footer: { tagline: "هندسة. تصنيع. تركيب." } }
    };
    let currentLang = localStorage.getItem('beta_lang') || 'en'; gsap.registerPlugin(ScrollTrigger);
    function resolvePath(path, obj) { return path.split('.').reduce((p, c) => p && p[c], obj); }
    function updateLang() {
        document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr'; document.documentElement.lang = currentLang;
        document.getElementById('lang-en').className = currentLang === 'en' ? 'active-lang' : ''; document.getElementById('lang-ar').className = currentLang === 'ar' ? 'active-lang' : '';
        document.querySelectorAll('[data-i18n]').forEach(el => { const text = resolvePath(el.getAttribute('data-i18n'), translations[currentLang]); if(text) el.innerHTML = text; });
        setTimeout(() => ScrollTrigger.refresh(), 150);
    }
    function toggleLanguage() { currentLang = currentLang === 'en' ? 'ar' : 'en'; localStorage.setItem('beta_lang', currentLang); updateLang(); }
    document.addEventListener("DOMContentLoaded", () => {
        updateLang();
        gsap.fromTo('.gsap-hero > *', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out" });
        document.querySelectorAll('.gsap-fade-up').forEach(el => { gsap.fromTo(el, { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, ease: "power3.out", scrollTrigger: { trigger: el, start: "top 85%" } }); });
    });
</script>
</body>
</html>