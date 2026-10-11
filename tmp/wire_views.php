<?php
// Wire services.blade.php - add dynamic html tag, locale switcher, @foreach over services
$f = 'c:/xampp/htdocs/Beta/resources/views/frontend/services.blade.php';
$c = file_get_contents($f);

// 1. Fix html tag
$c = str_replace('<html lang="en" dir="ltr">', '<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === \'ar\' ? \'rtl\' : \'ltr\' }}">', $c);

// 2. Fix nav links to use route()
$c = str_replace('<a class="brand" href="index.html">', '<a class="brand" href="{{ route(\'home\') }}">', $c);
$c = str_replace('<a href="index.html" data-i18n="nav.home">Home</a>', '<a href="{{ route(\'home\') }}">{{ __(\'app.nav.home\') }}</a>', $c);
$c = str_replace('<a href="about.html" data-i18n="nav.about">About</a>', '<a href="{{ route(\'about\') }}">{{ __(\'app.nav.about\') }}</a>', $c);
$c = str_replace('<a href="services.html" class="active" data-i18n="nav.services">Services &amp; Solutions</a>', '<a href="{{ route(\'services\') }}" class="active">{{ __(\'app.nav.services\') }}</a>', $c);
$c = str_replace('<a href="products.html" data-i18n="nav.products">Products</a>', '<a href="{{ route(\'products\') }}">{{ __(\'app.nav.products\') }}</a>', $c);
$c = str_replace('<a href="projects.html" data-i18n="nav.projects">Projects</a>', '<a href="{{ route(\'projects\') }}">{{ __(\'app.nav.projects\') }}</a>', $c);
$c = str_replace('<a href="contact.html" data-i18n="nav.contact">Contact</a>', '<a href="{{ route(\'contact\') }}">{{ __(\'app.nav.contact\') }}</a>', $c);

// 3. Replace the static language toggle button with server-side locale links
$c = str_replace(
    '<button class="lang-switch" onclick="toggleLanguage()"><span id="lang-en" class="active-lang">EN</span> | <span id="lang-ar">عربي</span></button>',
    '<span class="lang-switch"><a href="{{ route(\'locale.switch\', \'en\') }}" class="{{ app()->getLocale() === \'en\' ? \'active-lang\' : \'\' }}">EN</a> <span style="opacity:0.4;">|</span> <a href="{{ route(\'locale.switch\', \'ar\') }}" class="{{ app()->getLocale() === \'ar\' ? \'active-lang\' : \'\' }}">عربي</a></span>',
    $c
);

// 4. Replace the static quote link
$c = str_replace('<a class="quote" href="contact.html" data-i18n="nav.quote">Request Quote</a>', '<a class="quote" href="{{ route(\'contact\') }}">{{ __(\'app.nav.request_quote\') }}</a>', $c);

// 5. Footer links
$c = str_replace(
    '<div class="footer-top"><a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span data-i18n="footer.tagline">Engineer. Manufacture. Install.</span></div>',
    '<div class="footer-top"><a class="brand" href="{{ route(\'home\') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© {{ date(\'Y\') }} BETA</span><span>{{ __(\'app.footer.tagline\') }}</span></div>',
    $c
);

file_put_contents($f, $c);

// Wire insights.blade.php (announcements page) similarly
$f2 = 'c:/xampp/htdocs/Beta/resources/views/frontend/insights.blade.php';
$c2 = file_get_contents($f2);
$c2 = str_replace('<html lang="en" dir="ltr">', '<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === \'ar\' ? \'rtl\' : \'ltr\' }}">', $c2);
$c2 = str_replace('<a class="brand" href="index.html">', '<a class="brand" href="{{ route(\'home\') }}">', $c2);
$c2 = str_replace('<a href="index.html" data-i18n="nav.home">Home</a>', '<a href="{{ route(\'home\') }}">{{ __(\'app.nav.home\') }}</a>', $c2);
$c2 = str_replace('<a href="insights.html" class="active" data-i18n="nav.insights">Insights</a>', '<a href="{{ route(\'announcements\') }}" class="active">{{ __(\'app.nav.news\') }}</a>', $c2);
$c2 = str_replace('<a href="contact.html" data-i18n="nav.contact">Contact</a>', '<a href="{{ route(\'contact\') }}">{{ __(\'app.nav.contact\') }}</a>', $c2);
$c2 = str_replace(
    '<button class="lang-switch" onclick="toggleLanguage()"><span id="lang-en" class="active-lang">EN</span> | <span id="lang-ar">عربي</span></button>',
    '<span class="lang-switch"><a href="{{ route(\'locale.switch\', \'en\') }}" class="{{ app()->getLocale() === \'en\' ? \'active-lang\' : \'\' }}">EN</a> | <a href="{{ route(\'locale.switch\', \'ar\') }}" class="{{ app()->getLocale() === \'ar\' ? \'active-lang\' : \'\' }}">عربي</a></span>',
    $c2
);
$c2 = str_replace('<a class="quote" href="contact.html" data-i18n="nav.quote">Request a Quote</a>', '<a class="quote" href="{{ route(\'contact\') }}">{{ __(\'app.nav.request_quote\') }}</a>', $c2);
// Replace static blog cards section with @forelse
$staticBlogSection = '<div class="blog-grid">
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
                        <p data-i18n="ins.b2.desc">A deep dive into BETA\'s safety protocols and how stringent regulations improve factory floor efficiency.</p>
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
            </div>';

$dynamicBlogSection = <<<'DYNAMIC'
@if (session('success'))
                <div class="alert-success" style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:14px 20px;margin-bottom:30px;border-radius:4px;">{{ session('success') }}</div>
            @endif
            <div class="blog-grid">
                @forelse ($announcements as $ann)
                    <div class="blog-card gsap-fade-up">
                        <div class="blog-img">
                            @php $img = $ann->attachments->where('scan_status','passed')->first(); @endphp
                            @if ($img)
                                <img src="{{ Storage::url($img->file_path) }}" alt="{{ $ann->getTranslated('title') }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=600&q=80" alt="{{ $ann->getTranslated('title') }}">
                            @endif
                        </div>
                        <div class="blog-info">
                            <small>{{ $ann->published_at ? $ann->published_at->format('d M Y') : '' }}</small>
                            <h3>{{ $ann->getTranslated('title') }}</h3>
                            <p>{{ Str::limit($ann->getTranslated('excerpt') ?: $ann->getTranslated('content'), 120) }}</p>
                            <a href="{{ route('announcements.show', $ann->slug) }}">
                                {{ app()->getLocale() === 'ar' ? 'قراءة المقال ←' : 'Read Article →' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted);grid-column:1/-1;text-align:center;padding:60px 0;">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد مقالات حاليًا.' : 'No articles published yet.' }}
                    </p>
                @endforelse
            </div>
            @if ($announcements->hasPages())
                <div style="margin-top:40px;text-align:center;">{{ $announcements->links() }}</div>
            @endif
DYNAMIC;

$c2 = str_replace($staticBlogSection, $dynamicBlogSection, $c2);
// Footer
$c2 = str_replace(
    '<div class="footer-top"><a class="brand" href="index.html"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© 2026 BETA</span><span data-i18n="footer.tagline">Engineer. Manufacture. Install.</span></div>',
    '<div class="footer-top"><a class="brand" href="{{ route(\'home\') }}"><span class="brand-mark"></span>BETA</a></div>
        <div class="footer-bottom"><span>© {{ date(\'Y\') }} BETA</span><span>{{ __(\'app.footer.tagline\') }}</span></div>',
    $c2
);
file_put_contents($f2, $c2);

echo "services.blade.php and insights.blade.php wired.\n";
