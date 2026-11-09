<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('app.contact.seo_title') }}</title>
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
<x-header />

<main class="section-pad">
    <div class="container">
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
            <button class="tab-btn active" id="btn-quote">{!! __('contact.tab.quote') !!}</button>
        </div>

        <!-- FORM 1: REQUEST QUOTE -->
        <div class="form-box fade-in-up" id="form-quote">
            <h2 style="margin-bottom:30px;">{!! __('contact.q.title') !!}</h2>
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
                    <div class="form-group"><label>{{ __('app.contact.field_service') }}</label>
                        <select name="service_id" class="form-select">
                            <option value="">{{ __('app.contact.select_service') }}</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->getTranslated('title') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>{{ __('app.contact.field_product') }}</label>
                        <select name="product_id" class="form-select">
                            <option value="">{{ __('app.contact.select_product') }}</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->getTranslated('title') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>{{ __('app.contact.field_country') }}</label>
                        <select name="country_id" class="form-select">
                            <option value="">{{ __('app.contact.select_country') }}</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group"><label>{!! __('contact.f.name') !!}</label><input type="text" name="client_name" value="{{ old('client_name') }}" required></div>
                    <div class="form-group"><label>{!! __('contact.f.email') !!}</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                    <div class="form-group"><label>{!! __('contact.f.company') !!}</label><input type="text" name="company_name" value="{{ old('company_name') }}"></div>
                    <div class="form-group"><label>{!! __('contact.f.phone') !!}</label><input type="tel" name="phone" value="{{ old('phone') }}" required></div>
                    <div class="form-group full"><label>{!! __('contact.f.boq') !!}</label><input type="file" name="drawing" accept=".pdf,.dwg,.dxf,.jpg,.png"></div>
                    <div class="form-group full"><label>{!! __('contact.f.details') !!}</label><textarea name="client_message" required>{{ old('client_message') }}</textarea></div>
                    <div class="form-group full">
                        <button type="submit" class="submit-btn" id="quoteSubmitBtn">{!! __('contact.f.submitQ') !!}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<footer>
    <div class="container">
        <div class="footer-top"><a class="brand" href="{{ route('home') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span>{!! __('app.footer.tagline') !!}</span></div>
    </div>
</footer>

<script>
    function switchForm(type) {
        document.getElementById('form-quote').style.display = type === 'quote' ? 'block' : 'none';
        document.getElementById('form-career').style.display = type === 'career' ? 'block' : 'none';
        document.getElementById('btn-quote').className = `tab-btn ${type === 'quote' ? 'active' : ''}`;
        document.getElementById('btn-career').className = `tab-btn ${type === 'career' ? 'active' : ''}`;
    }

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
<x-success-modal />
</body>
</html>