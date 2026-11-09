{{--
    Unified public header — single source of truth for every public page.
    Fixed dark bar: BETA brand, primary navigation (server-rendered, active state
    via routeIs), locale switch (session-driven), quote CTA and a mobile
    hamburger with a collapsible panel. RTL-safe via logical properties.
    Self-contained styles/script so pages need no per-page header code.
--}}
@php
    $locale = app()->getLocale();
    $isAr = $locale === 'ar';
    $navItems = [
        ['route' => 'home',            'pattern' => ['home'],                        'key' => 'app.nav.home'],
        ['route' => 'about',           'pattern' => ['about'],                       'key' => 'app.nav.about'],
        ['route' => 'services',        'pattern' => ['services', 'service.detail'],  'key' => 'app.nav.services'],
        ['route' => 'products',        'pattern' => ['products', 'product.detail'],  'key' => 'app.nav.products'],
        ['route' => 'projects',        'pattern' => ['projects'],                    'key' => 'app.nav.projects'],
        ['route' => 'announcements.index', 'pattern' => ['announcements*', 'insights*'], 'key' => 'app.nav.announcements'],
        ['route' => 'careers',         'pattern' => ['careers*'],                    'key' => 'app.nav.careers'],
        ['route' => 'contact',         'pattern' => ['contact', 'quote.submit'],     'key' => 'app.nav.contact'],
    ];
    $navActive = fn (array $patterns) => collect($patterns)->contains(fn ($p) => request()->routeIs($p));
    $globalServices = $globalServices ?? collect();
@endphp

<style>
    .nav{position:fixed;top:0;left:0;width:100%;z-index:1000;background:var(--ink,#071321);color:var(--white,#fff);border-bottom:1px solid rgba(255,255,255,.1);}
    .nav-inner{width:min(var(--max,1380px),calc(100% - 48px));height:var(--header-height,92px);margin-inline:auto;display:flex;align-items:center;justify-content:space-between;gap:24px;}
    .brand{display:flex;align-items:center;gap:12px;font-family:"Manrope",sans-serif;font-size:26px;font-weight:800;letter-spacing:.08em;color:var(--white,#fff);text-decoration:none;flex:0 0 auto;}
    .brand:hover{color:var(--white,#fff);}
    .brand-mark{width:30px;height:30px;position:relative;transform:skew(-14deg) rotate(45deg);border:3px solid var(--sun,#F2A900);flex:0 0 auto;}
    .brand-mark:before{content:"";position:absolute;left:6px;top:2px;width:3px;height:20px;background:var(--sun,#F2A900);}
    .nav-links{display:flex;gap:28px;list-style:none;margin:0;padding:0;align-items:center;}
    .nav-links a{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.7);transition:color .2s;text-decoration:none;white-space:nowrap;}
    .nav-links a:hover,.nav-links a.active{color:var(--sun,#F2A900);}
    .nav-right{display:flex;align-items:center;gap:14px;flex:0 0 auto;}
    .lang{font-size:11px;font-weight:800;letter-spacing:.12em;color:var(--white,#fff);text-decoration:none;padding:10px 14px;border:1px solid rgba(255,255,255,.25);transition:border-color .2s,color .2s;text-transform:uppercase;}
    .lang:hover{border-color:var(--sun,#F2A900);color:var(--sun,#F2A900);}
    .quote{background:var(--sun,#F2A900);color:var(--ink,#071321);padding:12px 18px;font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;text-decoration:none;transition:background .2s;white-space:nowrap;}
    .quote:hover{background:#fff;}
    .menu{display:none;background:none;border:0;color:#fff;font-size:26px;line-height:1;cursor:pointer;padding:4px 8px;}
    .nav-mobile{display:none;position:fixed;top:var(--header-height,92px);inset-inline:0;z-index:999;background:rgba(7,19,33,.98);border-bottom:1px solid rgba(255,255,255,.12);padding:14px 24px 20px;max-height:calc(100vh - var(--header-height,92px));overflow-y:auto;}
    .nav-mobile.open{display:block;}
    .nav-mobile a{display:block;padding:13px 6px;font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.78);text-decoration:none;border-bottom:1px solid rgba(255,255,255,.08);}
    .nav-mobile a:last-child{border-bottom:0;}
    .nav-mobile a:hover,.nav-mobile a.active{color:var(--sun,#F2A900);}
    @media (max-width:1180px){.nav-links{gap:16px;}.quote{padding:12px 12px;}}
    @media (max-width:1024px){.nav-links,.quote{display:none;}.menu{display:block;}}
    @media (min-width:1025px){.nav-mobile{display:none !important;}}
</style>

<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="BETA Industrial"><span class="brand-mark" aria-hidden="true"></span>BETA</a>

        <nav class="nav-links" aria-label="{{ __('app.nav.main') }}">
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" @class(['active' => $navActive($item['pattern'])])>{{ __($item['key']) }}</a>
            @endforeach
        </nav>

        <div class="nav-right">
            <a class="lang" href="{{ route('locale.switch', $isAr ? 'en' : 'ar') }}" rel="alternate" hreflang="{{ $isAr ? 'en' : 'ar' }}" lang="{{ $isAr ? 'en' : 'ar' }}">{{ $isAr ? 'EN' : 'عربي' }}</a>
            <a class="quote" href="{{ route('contact') }}">{{ __('app.nav.request_quote') }}</a>
            <button class="menu" id="menu" type="button" aria-label="{{ __('app.nav.menu') }}" aria-expanded="false" aria-controls="nav-mobile">☰</button>
        </div>
    </div>

    <nav class="nav-mobile" id="nav-mobile" aria-label="{{ __('app.nav.main') }}">
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}" @class(['active' => $navActive($item['pattern'])])>{{ __($item['key']) }}</a>
        @endforeach
    </nav>
</header>

<script>
    (function () {
        var btn = document.getElementById('menu');
        var panel = document.getElementById('nav-mobile');
        if (!btn || !panel || btn.dataset.bound === '1') return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', function () {
            var open = panel.classList.toggle('open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.textContent = open ? '×' : '☰';
        });
        panel.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                panel.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                btn.textContent = '☰';
            });
        });
    })();
</script>
