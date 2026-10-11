<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Contact & Careers</title>
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
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--sun); }
.lang-switch { font-size: 11px; font-weight: 700; color: var(--white); opacity: 0.8; } .lang-switch .active-lang { opacity: 1; color: var(--sun); }

main { min-height: calc(100vh - var(--header-height)); padding-top: var(--header-height); }
.container { width: min(var(--max), calc(100% - 64px)); margin: auto; }
.section-pad { padding: 80px 0 120px; }

.tabs { display: flex; gap: 15px; margin-bottom: 30px; }
.tab-btn { padding: 14px 28px; font-size: 11px; font-weight: 800; text-transform: uppercase; border: 1px solid var(--line); background: var(--white); transition: 0.3s; }
.tab-btn.active { background: var(--ocean); color: var(--white); border-color: var(--ocean); }

.form-box { background: var(--white); padding: 50px; border: 1px solid var(--line); }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
.form-group.full { grid-column: 1 / -1; }
.form-group label { font-size: 11px; font-weight: bold; text-transform: uppercase; }
.form-group input, .form-group select, .form-group textarea { padding: 15px; border: 1px solid var(--line); background: var(--paper); font-family: inherit; }
.form-group textarea { min-height: 120px; }
.submit-btn { background: var(--ink); color: var(--sun); padding: 18px; width: 100%; border: none; font-weight: 800; text-transform: uppercase; cursor: pointer; transition: 0.3s; }
.submit-btn:hover { background: var(--ocean); color: var(--white); }

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
            <a href="services.html" data-i18n="nav.services">Services</a>
            <a href="products.html" data-i18n="nav.products">Products</a>
            <a href="projects.html" data-i18n="nav.projects">Projects</a>
            <a href="contact.html" class="active" data-i18n="nav.contact">Contact & Careers</a>
        </nav>
        <div class="nav-right">
            <button class="lang-switch" onclick="toggleLanguage()"><span id="lang-en" class="active-lang">EN</span> | <span id="lang-ar">عربي</span></button>
        </div>
    </div>
</header>

<main class="section-pad">
    <div class="container">
        <div class="tabs">
            <button class="tab-btn active" onclick="switchForm('quote')" id="btn-quote" data-i18n="tab.quote">Request a Quote</button>
            <button class="tab-btn" onclick="switchForm('career')" id="btn-career" data-i18n="tab.career">Join Our Team (Careers)</button>
        </div>

        <!-- FORM 1: REQUEST QUOTE -->
        <div class="form-box" id="form-quote">
            <h2 style="margin-bottom:30px;" data-i18n="q.title">Project Quotation & Inquiries</h2>
            <form onsubmit="event.preventDefault(); alert('Quote Request Sent!');">
                <div class="form-grid">
                    <div class="form-group"><label data-i18n="f.name">Full Name *</label><input type="text" required></div>
                    <div class="form-group"><label data-i18n="f.email">Email Address *</label><input type="email" required></div>
                    <div class="form-group"><label data-i18n="f.company">Company Name</label><input type="text"></div>
                    <div class="form-group"><label data-i18n="f.phone">Phone Number *</label><input type="tel" required></div>
                    <div class="form-group full"><label data-i18n="f.boq">Upload Drawing / BOQ (PDF)</label><input type="file" accept=".pdf,.dwg"></div>
                    <div class="form-group full"><label data-i18n="f.details">Project Scope & Details</label><textarea required></textarea></div>
                </div>
                <button type="submit" class="submit-btn" data-i18n="f.submitQ">Submit Quote Request</button>
            </form>
        </div>

        <!-- FORM 2: CAREERS -->
        <div class="form-box" id="form-career" style="display:none;">
            <h2 style="margin-bottom:30px;" data-i18n="c.title">Career Application</h2>
            <form onsubmit="event.preventDefault(); alert('Application Submitted!');">
                <div class="form-grid">
                    <div class="form-group"><label data-i18n="f.name">Full Name *</label><input type="text" required></div>
                    <div class="form-group"><label data-i18n="f.email">Email Address *</label><input type="email" required></div>
                    <div class="form-group full"><label data-i18n="c.pos">Target Position *</label>
                        <select required>
                            <option value="eng">Structural Engineer</option>
                            <option value="wld">Certified Welder</option>
                            <option value="pm">Project Manager</option>
                        </select>
                    </div>
                    <div class="form-group full"><label data-i18n="c.cv">Upload CV / Resume (PDF) *</label><input type="file" accept=".pdf" required></div>
                </div>
                <button type="submit" class="submit-btn" data-i18n="c.submitC">Submit Job Application</button>
            </form>
        </div>
    </div>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span data-i18n="footer.tagline">Engineer. Manufacture. Install.</span></div>
    </div>
</footer>

<script>
    function switchForm(type) {
        document.getElementById('form-quote').style.display = type === 'quote' ? 'block' : 'none';
        document.getElementById('form-career').style.display = type === 'career' ? 'block' : 'none';
        document.getElementById('btn-quote').className = `tab-btn ${type === 'quote' ? 'active' : ''}`;
        document.getElementById('btn-career').className = `tab-btn ${type === 'career' ? 'active' : ''}`;
    }

    const translations = {
        en: { nav: { home: "Home", about: "About", services: "Services", products: "Products", projects: "Projects", contact: "Contact & Careers" }, tab: { quote: "Request a Quote", career: "Join Our Team (Careers)" }, q: { title: "Project Quotation & Inquiries" }, c: { title: "Career Application", pos: "Target Position *", cv: "Upload CV / Resume (PDF) *", submitC: "Submit Job Application" }, f: { name: "Full Name *", email: "Email Address *", company: "Company Name", phone: "Phone Number *", boq: "Upload Drawing / BOQ (PDF)", details: "Project Scope & Details", submitQ: "Submit Quote Request" }, footer: { tagline: "Engineer. Manufacture. Install." } },
        ar: { nav: { home: "الرئيسية", about: "عن الشركة", services: "الخدمات", products: "المنتجات", projects: "المشاريع", contact: "التواصل والتوظيف" }, tab: { quote: "طلب عرض سعر", career: "الانضمام للفريق (الوظائف)" }, q: { title: "استفسارات وعروض أسعار المشاريع" }, c: { title: "تقديم طلب توظيف", pos: "الوظيفة المستهدفة *", cv: "رفع السيرة الذاتية (PDF) *", submitC: "إرسال طلب التوظيف" }, f: { name: "الاسم الكامل *", email: "البريد الإلكتروني *", company: "اسم الشركة", phone: "رقم الهاتف *", boq: "رفع المخطط / مقايسة الأسعار (PDF)", details: "تفاصيل ونطاق المشروع", submitQ: "إرسال طلب عرض السعر" }, footer: { tagline: "هندسة. تصنيع. تركيب." } }
    };
    let currentLang = localStorage.getItem('beta_lang') || 'en';
    function resolvePath(p, o) { return p.split('.').reduce((a, b) => a && a[b], o); }
    function updateLang() { document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr'; document.documentElement.lang = currentLang; document.getElementById('lang-en').className = currentLang === 'en' ? 'active-lang' : ''; document.getElementById('lang-ar').className = currentLang === 'ar' ? 'active-lang' : ''; document.querySelectorAll('[data-i18n]').forEach(e => { const t = resolvePath(e.getAttribute('data-i18n'), translations[currentLang]); if(t) e.innerHTML = t; }); }
    function toggleLanguage() { currentLang = currentLang === 'en' ? 'ar' : 'en'; localStorage.setItem('beta_lang', currentLang); updateLang(); }
    document.addEventListener("DOMContentLoaded", () => { updateLang(); });
</script>
</body>
</html>