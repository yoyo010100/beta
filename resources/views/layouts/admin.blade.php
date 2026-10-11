<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'BETA Admin') — Executive Command Center</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
:root{
    --sun:#F2A900; --ocean:#003087; --ink:#071321; --charcoal:#1a202c;
    --paper:#f4f7f6; --white:#FFFFFF; --muted:#718096; --line:#e2e8f0;
    --sidebar-w:270px; --green:#38a169; --red:#e53e3e;
}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:"DM Sans",sans-serif;background:var(--paper);color:var(--ink);display:flex;height:100vh;overflow:hidden}
h1,h2,h3,h4{font-family:"Manrope",sans-serif}
a{text-decoration:none;color:inherit}
button,select,input,textarea{font:inherit;outline:none}

/* SIDEBAR */
.sidebar{width:var(--sidebar-w);background:var(--ink);color:var(--white);display:flex;flex-direction:column;flex-shrink:0;overflow-y:auto}
.sidebar-header{padding:28px 24px;border-bottom:1px solid rgba(255,255,255,.08)}
.brand{display:flex;align-items:center;gap:10px;font-size:22px;font-weight:800;letter-spacing:.08em}
.brand-mark{width:22px;height:22px;position:relative;transform:skew(-14deg) rotate(45deg);border:2px solid var(--sun);flex-shrink:0}
.brand-mark:before{content:"";position:absolute;left:4px;top:1px;width:2px;height:13px;background:var(--sun)}
.sidebar-user{padding:16px 24px;border-bottom:1px solid rgba(255,255,255,.06);font-size:12px;color:rgba(255,255,255,.5)}
.sidebar-user strong{display:block;color:rgba(255,255,255,.85);font-size:13px;margin-bottom:2px}
.nav-menu{padding:16px 0;flex-grow:1}
.nav-section{font-size:9px;font-weight:800;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.18em;padding:16px 24px 8px;display:block}
.nav-item{display:flex;align-items:center;gap:10px;padding:12px 24px;font-size:13px;font-weight:500;color:rgba(255,255,255,.55);transition:.2s;border-left:3px solid transparent;cursor:pointer}
.nav-item:hover{background:rgba(255,255,255,.05);color:var(--white)}
.nav-item.active{background:rgba(242,169,0,.1);color:var(--sun);border-left-color:var(--sun);font-weight:700}
.nav-item .icon{width:18px;text-align:center;font-size:16px}
.sidebar-footer{padding:20px 24px;border-top:1px solid rgba(255,255,255,.06)}
.sidebar-footer form button{color:rgba(255,255,255,.4);font-size:12px;letter-spacing:.05em;cursor:pointer;transition:.2s}
.sidebar-footer form button:hover{color:var(--red)}

/* MAIN */
.main-content{flex:1;display:flex;flex-direction:column;overflow-y:auto;min-width:0}
.topbar{height:72px;background:var(--white);border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 36px;position:sticky;top:0;z-index:50;flex-shrink:0}
.topbar-left{display:flex;flex-direction:column}
.page-title{font-size:18px;font-weight:700}
.page-breadcrumb{font-size:11px;color:var(--muted)}
.user-profile{display:flex;align-items:center;gap:12px;font-size:13px;font-weight:600}
.avatar{width:36px;height:36px;background:var(--ocean);color:var(--white);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;letter-spacing:.05em}
.wrapper{padding:32px 36px;flex:1}

/* UTILITY */
.flex-between{display:flex;justify-content:space-between;align-items:center}
.btn{padding:10px 20px;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;transition:.25s;border:none;display:inline-flex;align-items:center;gap:6px;letter-spacing:.03em}
.btn-primary{background:var(--ocean);color:var(--white)}
.btn-primary:hover{background:var(--ink);color:var(--sun)}
.btn-sun{background:var(--sun);color:var(--ink)}
.btn-sun:hover{background:#e09a00}
.btn-outline{border:1px solid var(--line);background:var(--white);color:var(--ink)}
.btn-outline:hover{border-color:var(--ocean);color:var(--ocean)}
.btn-danger{background:#fed7d7;color:var(--red);border:1px solid #feb2b2}
.btn-danger:hover{background:var(--red);color:var(--white)}
.btn-sm{padding:7px 14px;font-size:11px}
.badge{padding:4px 10px;border-radius:20px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.06em}
.badge-new{background:#ebf8ff;color:#2b6cb0}
.badge-reviewing{background:#faf5ff;color:#6b46c1}
.badge-quoted{background:#fefcbf;color:#744210}
.badge-accepted{background:#f0fff4;color:#276749}
.badge-rejected{background:#fff5f5;color:var(--red)}
.badge-hired{background:#f0fff4;color:#276749}
.badge-interviewing{background:#e6fffa;color:#234e52}
.badge-quarantined{background:#fff5f5;color:var(--red)}
.badge-passed{background:#f0fff4;color:#276749}

/* CARDS */
.metric-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:28px}
.metric-card{background:var(--white);padding:24px;border-radius:10px;border:1px solid var(--line)}
.metric-card h4{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px;font-weight:800}
.metric-card .value{font-size:30px;font-weight:800;color:var(--ink);line-height:1}
.metric-card .sub{font-size:12px;margin-top:6px}
.chart-card{background:var(--white);padding:24px;border-radius:10px;border:1px solid var(--line);margin-bottom:24px}
.chart-card h4{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin-bottom:20px;font-weight:800}

/* TABLES */
.table-wrap{background:var(--white);border-radius:10px;border:1px solid var(--line);overflow:hidden;margin-bottom:28px}
.table-toolbar{display:flex;gap:12px;padding:16px 20px;border-bottom:1px solid var(--line);align-items:center;flex-wrap:wrap}
.table-toolbar input,.table-toolbar select{padding:9px 14px;border:1px solid var(--line);border-radius:6px;font-size:13px;min-width:200px}
table{width:100%;border-collapse:collapse}
th,td{padding:14px 18px;text-align:left;font-size:13px;border-bottom:1px solid var(--line)}
th{background:#f8fafc;font-size:10px;font-weight:800;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;white-space:nowrap}
td{color:var(--charcoal);font-weight:500;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafbfc}
.pagination-bar{display:flex;justify-content:space-between;align-items:center;padding:14px 18px;font-size:12px;color:var(--muted)}

/* FORMS */
.form-card{background:var(--white);border-radius:10px;border:1px solid var(--line);padding:28px;margin-bottom:24px}
.form-card h3{font-size:16px;font-weight:700;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--line)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
.form-group label{font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em}
.form-group input,.form-group select,.form-group textarea{padding:10px 14px;border:1px solid var(--line);border-radius:6px;font-size:14px;transition:.2s;background:var(--white)}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--ocean);box-shadow:0 0 0 3px rgba(0,48,135,.08)}
.form-group textarea{resize:vertical;min-height:100px}
.profit-display{background:linear-gradient(135deg,#f0fff4,#e6fffa);border:2px solid var(--green);border-radius:8px;padding:16px;text-align:center}
.profit-display .profit-value{font-size:28px;font-weight:800;color:var(--green)}
.profit-display small{font-size:11px;color:#276749}

/* FLASH */
.flash-success{background:#f0fff4;border:1px solid #9ae6b4;color:#276749;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600}
.flash-error{background:#fff5f5;border:1px solid #feb2b2;color:var(--red);padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:13px}

/* STARS */
.stars span{cursor:pointer;font-size:20px;color:#ddd;transition:.2s}
.stars span.filled{color:var(--sun)}
.stars span:hover{transform:scale(1.2)}
</style>
@stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="brand"><span class="brand-mark"></span>BETA Admin</div>
    </div>
    @auth
    <div class="sidebar-user">
        <strong>{{ Auth::user()->name }}</strong>
        {{ Str::title(str_replace('_', ' ', Auth::user()->role)) }}
    </div>
    @endauth
    <nav class="nav-menu">
        <span class="nav-section">Analytics</span>
        <a href="{{ route('admin.dashboard') }}" @class(['nav-item', 'active' => request()->routeIs('admin.dashboard')])>
            <span class="icon">📊</span> Executive Dashboard
        </a>

        <span class="nav-section">Operations</span>
        <a href="{{ route('admin.leads.index') }}" @class(['nav-item', 'active' => request()->routeIs('admin.leads*')])>
            <span class="icon">📩</span> Quotation Leads
        </a>
        <a href="{{ route('admin.catalog.products') }}" @class(['nav-item', 'active' => request()->routeIs('admin.catalog.products*')])>
            <span class="icon">📦</span> Products
        </a>
        <a href="{{ route('admin.catalog.services') }}" @class(['nav-item', 'active' => request()->routeIs('admin.catalog.services*')])>
            <span class="icon">⚙️</span> Services
        </a>

        <span class="nav-section">Content & HR</span>
        <a href="{{ route('admin.announcements.index') }}" @class(['nav-item', 'active' => request()->routeIs('admin.announcements*')])>
            <span class="icon">📢</span> Announcements
        </a>
        <a href="{{ route('admin.careers.index') }}" @class(['nav-item', 'active' => request()->routeIs('admin.careers.index') || request()->routeIs('admin.careers.show')])>
            <span class="icon">👥</span> Job Applications
        </a>
        <a href="{{ route('admin.careers.postings') }}" @class(['nav-item', 'active' => request()->routeIs('admin.careers.postings*')])>
            <span class="icon">💼</span> Job Postings
        </a>
    </nav>
    <div class="sidebar-footer">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit">↩ Sign Out</button>
        </form>
    </div>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="topbar-left">
            <div class="page-title">@yield('page_title', 'Dashboard')</div>
            <div class="page-breadcrumb">BETA Industrial &rsaquo; @yield('page_title', 'Dashboard')</div>
        </div>
        <div class="user-profile">
            @auth
            <div style="text-align:right">
                <div style="font-size:13px;font-weight:700">{{ Auth::user()->name }}</div>
                <div style="font-size:11px;color:var(--muted)">{{ Str::upper(Auth::user()->role) }}</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            @endauth
        </div>
    </header>

    <div class="wrapper">
        @if(session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">⚠ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="flash-error">
                @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
    gsap.fromTo('.metric-card, .chart-card, .table-wrap, .form-card',
        { y: 18, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.5, stagger: 0.07, ease: 'power2.out' }
    );
});
</script>
@stack('scripts')
</body>
</html>
