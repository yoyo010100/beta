<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Request a Quote & Contact</title>
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
.captcha-row { display: flex; align-items: center; gap: 12px; }
/* Honeypot: invisible to humans, present for bots */
.hp-field { position: absolute !important; left: -9999px !important; top: -9999px !important; width: 1px !important; height: 1px !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important; }
.submit-btn { background: var(--ink); color: var(--sun); padding: 18px; width: 100%; border: none; font-weight: 800; text-transform: uppercase; cursor: pointer; transition: 0.3s; }
.submit-btn:hover { background: var(--ocean); color: var(--white); }
        .spinner { display: inline-block; width: 1rem; height: 1rem; vertical-align: middle; border: 0.25em solid currentColor; border-radius: 50%; border-top-color: transparent; animation: spin 0.8s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner.hidden { display: none; }
</style>
</head>
<body>
<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a>
        <nav class="nav-links">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')]) data-i18n="nav.home">Home</a>
            <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')]) data-i18n="nav.about">About</a>
            <a href="{{ route('services') }}" @class(['active' => request()->routeIs('services')]) data-i18n="nav.services">Services</a>
            <a href="{{ route('products') }}" @class(['active' => request()->routeIs('products')]) data-i18n="nav.products">Products</a>
            <a href="{{ route('projects') }}" @class(['active' => request()->routeIs('projects')]) data-i18n="nav.projects">Projects</a>
            <a href="{{ route('announcements') }}" @class(['active' => request()->routeIs('announcements*')]) data-i18n="nav.news">Announcements</a>
            <a href="{{ route('careers') }}" @class(['active' => request()->routeIs('careers*')]) data-i18n="nav.careers">Careers</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')]) data-i18n="nav.contact">Contact</a>
        </nav>
        <div class="nav-right">
            <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="lang-switch" style="text-decoration:none;"><span class="active-lang">{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</span></a>
        </div>
    </div>
</header>

<main class="section-pad">
    <div class="container">
        @if(session('success') || session('quote_submitted'))
            <div style="background:#f0fff4;color:#276749;border:1px solid #c6f6d5;padding:16px 20px;border-radius:6px;margin-bottom:24px;font-weight:600;">
                ✓ {{ session('success') ?? __('Your quote request has been submitted successfully! Our engineering team will review it shortly.') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fff5f5;color:#c53030;border:1px solid #feb2b2;padding:16px 20px;border-radius:6px;margin-bottom:24px;">
                <ul style="margin-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tabs">
            <button class="tab-btn active" id="btn-quote" data-i18n="tab.quote">Request a Quote</button>
        </div>

        <!-- FORM 1: REQUEST QUOTE -->
        <div class="form-box fade-in-up" id="form-quote">
            <h2 style="margin-bottom:30px;" data-i18n="q.title">Project Quotation & Inquiries</h2>
            <form action="{{ route('quote.submit') }}" method="POST" enctype="multipart/form-data" id="quoteForm">
                @csrf
                <!-- Anti-spam: honeypot (hidden) -->
                <div class="hp-field" aria-hidden="true">
                    <label for="quote-hp-check">Do not fill this field</label>
                    <input type="text" id="quote-hp-check" name="hp_check" tabindex="-1" autocomplete="off">
                </div>
                <div class="hp-field" aria-hidden="true">
                    <input type="text" name="website_url_hp" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" id="quote-hp-time" name="hp_time" value="">
                <!-- Service/Product/Country Dropdowns -->
                <div class="form-grid" style="margin-bottom:20px;">
                    <div class="form-group"><label>Interested Service</label>
                        <select name="service_id" class="form-select">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->getTranslated('title') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>Interested Product</label>
                        <select name="product_id" class="form-select">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->getTranslated('title') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>Country</label>
                        <select name="country_id" class="form-select">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group"><label data-i18n="f.name">Full Name *</label><input type="text" name="client_name" value="{{ old('client_name') }}" required></div>
                    <div class="form-group"><label data-i18n="f.email">Email Address *</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                    <div class="form-group"><label data-i18n="f.company">Company Name</label><input type="text" name="company_name" value="{{ old('company_name') }}"></div>
                    <div class="form-group"><label data-i18n="f.phone">Phone Number *</label><input type="tel" name="phone" value="{{ old('phone') }}" required></div>
                    <div class="form-group full"><label data-i18n="f.boq">Upload Drawing / BOQ (PDF, DWG)</label><input type="file" name="drawing" accept=".pdf,.dwg,.dxf,.jpg,.png"></div>
                    <div class="form-group full"><label data-i18n="f.details">Project Scope & Details *</label><textarea name="client_message" required>{{ old('client_message') }}</textarea></div>
                    <div class="form-group full">
                        <button type="submit" class="submit-btn" id="quoteSubmitBtn" data-i18n="f.submitQ">
                            <span id="quoteSubmitText">Submit Quote Request</span>
                            <span id="quoteSubmitSpinner" class="spinner hidden" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
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
        en: { nav: { home: "Home", about: "About", services: "Services", products: "Products", projects: "Projects", contact: "Contact & Careers" }, tab: { quote: "Request a Quote", career: "Join Our Team (Careers)" }, q: { title: "Project Quotation & Inquiries" }, c: { title: "Career Application", pos: "Target Position *", cv: "Upload CV / Resume (PDF) *", submitC: "Submit Job Application" }, f: { name: "Full Name *", email: "Email Address *", company: "Company Name", phone: "Phone Number *", boq: "Upload Drawing / BOQ (PDF)", details: "Project Scope & Details", submitQ: "Submit Quote Request", captcha: "Security Check *" }, footer: { tagline: "Engineer. Manufacture. Install." } },
        ar: { nav: { home: "الرئيسية", about: "عن الشركة", services: "الخدمات", products: "المنتجات", projects: "المشاريع", contact: "التواصل والتوظيف" }, tab: { quote: "طلب عرض سعر", career: "الانضمام للفريق (الوظائف)" }, q: { title: "استفسارات وعروض أسعار المشاريع" }, c: { title: "تقديم طلب توظيف", pos: "الوظيفة المستهدفة *", cv: "رفع السيرة الذاتية (PDF) *", submitC: "إرسال طلب التوظيف" }, f: { name: "الاسم الكامل *", email: "البريد الإلكتروني *", company: "اسم الشركة", phone: "رقم الهاتف *", boq: "رفع المخطط / مقايسة الأسعار (PDF)", details: "تفاصيل ونطاق المشروع", submitQ: "إرسال طلب عرض السعر", captcha: "التحقق الأمني *" }, footer: { tagline: "هندسة. تصنيع. تركيب." } }
    };
    let currentLang = localStorage.getItem('beta_lang') || 'en';
    function resolvePath(p, o) { return p.split('.').reduce((a, b) => a && a[b], o); }
    function updateLang() { document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr'; document.documentElement.lang = currentLang; document.getElementById('lang-en').className = currentLang === 'en' ? 'active-lang' : ''; document.getElementById('lang-ar').className = currentLang === 'ar' ? 'active-lang' : ''; document.querySelectorAll('[data-i18n]').forEach(e => { const t = resolvePath(e.getAttribute('data-i18n'), translations[currentLang]); if(t) e.innerHTML = t; }); }
    function toggleLanguage() { currentLang = currentLang === 'en' ? 'ar' : 'en'; localStorage.setItem('beta_lang', currentLang); updateLang(); }

    // ---- Anti-spam: honeypot + time-trap ----
    document.addEventListener("DOMContentLoaded", function() {
        // Timestamp trap: record page-load time
        var hpTime = document.getElementById('quote-hp-time');
        if (hpTime) hpTime.value = Date.now();

        var form = document.getElementById('quoteForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                // 1) Honeypot: bots tend to fill every field
                var hpCheck = form.elements['hp_check'];
                if (hpCheck && hpCheck.value.trim() !== '') {
                    event.preventDefault();
                    return false;
                }

                // 2) Time trap check
                var hpTimeVal = parseInt(form.elements['hp_time'] ? form.elements['hp_time'].value : 0, 10) || 0;
                if (hpTimeVal && Date.now() - hpTimeVal < 2000) {
                    event.preventDefault();
                    alert('Please wait a moment before submitting.');
                    return false;
                }

                // Submit animation: disable button, show spinner
                var btn = document.getElementById('quoteSubmitBtn');
                var submitText = document.getElementById('quoteSubmitText');
                var submitSpinner = document.getElementById('quoteSubmitSpinner');
                if (btn && submitText && submitSpinner) {
                    btn.disabled = true;
                    submitText.classList.add('hidden');
                    submitSpinner.classList.remove('hidden');
                }

                return true;
            });
        }
    });
</script>
</body>
</html>