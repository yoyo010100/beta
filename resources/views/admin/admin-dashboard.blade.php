<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Executive Admin</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
:root { 
    --sun: #F2A900; --ocean: #003087; --ink: #071321; --charcoal: #1a202c; 
    --paper: #f4f7f6; --white: #FFFFFF; --muted: #718096; --line: #e2e8f0; 
    --sidebar-width: 280px; 
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); display: flex; height: 100vh; overflow: hidden; }
h1, h2, h3, h4 { font-family: "Manrope", sans-serif; }
a { text-decoration: none; color: inherit; }
button, select, input { font: inherit; outline: none; }

/* SIDEBAR */
.sidebar { width: var(--sidebar-width); background: var(--ink); color: var(--white); display: flex; flex-direction: column; flex-shrink: 0; }
.sidebar-header { padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.brand { display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 800; letter-spacing: .08em; }
.brand-mark { width: 24px; height: 24px; position: relative; transform: skew(-14deg) rotate(45deg); border: 2px solid var(--sun); }
.brand-mark:before { content: ""; position: absolute; left: 4px; top: 1px; width: 2px; height: 14px; background: var(--sun); }
.nav-menu { padding: 20px 0; flex-grow: 1; overflow-y: auto; }
.nav-label { font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.15em; padding: 0 30px; margin-bottom: 10px; display: block; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 15px 30px; font-size: 13px; font-weight: 600; color: #a0aec0; transition: 0.2s; border-left: 3px solid transparent; }
.nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
.nav-item.active { background: rgba(242, 169, 0, 0.1); color: var(--sun); border-left-color: var(--sun); }

/* MAIN CONTENT */
.main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; }
.topbar { height: 80px; background: var(--white); border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; padding: 0 40px; position: sticky; top: 0; z-index: 50; }
.page-title { font-size: 20px; font-weight: 700; }
.user-profile { display: flex; align-items: center; gap: 15px; font-size: 13px; font-weight: 600; }
.avatar { width: 36px; height: 36px; background: var(--ocean); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; }
.wrapper { padding: 40px; max-width: 1600px; margin: 0 auto; width: 100%; }

/* UTILITY UI */
.flex-between { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.btn { padding: 10px 20px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; border: none; display: inline-flex; align-items: center; gap: 8px; }
.btn-primary { background: var(--ocean); color: var(--white); }
.btn-primary:hover { background: var(--ink); color: var(--sun); }
.btn-outline { border: 1px solid var(--line); background: var(--white); color: var(--ink); }
.btn-outline:hover { border-color: var(--ocean); }
.badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }

/* DATA CARDS & GRIDS */
.metric-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 30px; }
.metric-card { background: var(--white); padding: 25px; border-radius: 8px; border: 1px solid var(--line); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
.metric-card h4 { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
.metric-card .value { font-size: 32px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }

/* TABLES */
.toolbar { display: flex; gap: 15px; padding: 20px; background: var(--white); border: 1px solid var(--line); border-bottom: none; border-radius: 8px 8px 0 0; }
.toolbar input, .toolbar select { padding: 10px 15px; border: 1px solid var(--line); border-radius: 6px; font-size: 13px; width: 250px; }
.table-wrapper { background: var(--white); border: 1px solid var(--line); border-radius: 0 0 8px 8px; overflow-x: auto; margin-bottom: 30px; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 16px 20px; text-align: left; font-size: 13px; border-bottom: 1px solid var(--line); }
th { background: #f8fafc; font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--muted); letter-spacing: 0.05em; }
td { color: var(--charcoal); font-weight: 500; }
tr:hover { background: #f9fafb; }
.pagination { display: flex; justify-content: space-between; padding: 15px 20px; background: var(--white); border-top: 1px solid var(--line); font-size: 13px; color: var(--muted); }
</style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header"><div class="brand"><span class="brand-mark"></span>BETA Admin</div></div>
    <nav class="nav-menu">
        <span class="nav-label">Core Modules</span>
        <a href="admin-dashboard.html" class="nav-item active">📊 Executive Dashboard</a>
        <a href="admin-leads.html" class="nav-item">📩 1. Quotation Leads</a>
        <a href="admin-catalog.html" class="nav-item">📦 2. Product Catalog</a>
        <a href="admin-portfolio.html" class="nav-item">🏗️ 3. Projects Portfolio</a>
        <span class="nav-label" style="margin-top: 30px;">Content & HR</span>
        <a href="admin-blog.html" class="nav-item">📝 4. Insights & Blog</a>
        <a href="admin-careers.html" class="nav-item">👥 5. Job Applicants</a>
        <a href="admin-resources.html" class="nav-item">📂 6. Resource Center</a>
    </nav>
</aside>

<!-- PAGE CONTENT INJECTED HERE -->

<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.fromTo('.gsap-ui', { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, stagger: 0.1 });
    });
</script>
</body>
</html>
<!-- Include Master Sidebar/Header Here -->
<main class="main-content">
    <header class="topbar"><div class="page-title">Executive Command Center</div><div class="user-profile"><span>Admin Manager</span><div class="avatar">AM</div></div></header>
    
    <div class="wrapper">
        <div class="flex-between gsap-ui">
            <div>
                <h2 style="font-size:24px; color:var(--ink);">Welcome back, Manager.</h2>
                <p style="color:var(--muted); font-size:14px;">Here is your Q3 enterprise performance summary.</p>
            </div>
            <button class="btn btn-outline">📅 Generate Q3 Report</button>
        </div>

        <div class="metric-grid gsap-ui">
            <div class="metric-card"><h4>Total Pipeline Value</h4><div class="value">$14.2M</div><span style="color:#38a169; font-size:12px; font-weight:700;">+ $2.1M this month</span></div>
            <div class="metric-card"><h4>Active RFQs</h4><div class="value">48</div><span style="color:#e53e3e; font-size:12px; font-weight:700;">12 require attention</span></div>
            <div class="metric-card"><h4>Current Active Projects</h4><div class="value">22</div><span style="color:#38a169; font-size:12px; font-weight:700;">On Schedule: 20</span></div>
            <div class="metric-card"><h4>Hiring Pipeline</h4><div class="value">84</div><span style="color:#38a169; font-size:12px; font-weight:700;">New applicants: 15</span></div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px;">
            <div class="metric-card gsap-ui">
                <h4 style="margin-bottom:20px;">Quotation Value & Volume (YTD)</h4>
                <div id="revenueChart"></div>
            </div>
            <div class="metric-card gsap-ui">
                <h4 style="margin-bottom:20px;">Lead Conversion Funnel</h4>
                <div id="funnelChart"></div>
            </div>
        </div>
    </div>
</main>
<script>
    // Include this script at the bottom of admin-dashboard.html
    var optionsRevenue = {
        series: [{ name: 'RFQ Value ($M)', data: [2.1, 3.4, 4.8, 5.1, 7.2, 9.5, 11.0, 14.2] }],
        chart: { height: 320, type: 'area', fontFamily: 'DM Sans', toolbar: { show: false } },
        colors: ['#003087'], fill: { type: 'gradient' }, dataLabels: { enabled: false }, stroke: { curve: 'smooth' },
        xaxis: { categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'] }
    };
    new ApexCharts(document.querySelector("#revenueChart"), optionsRevenue).render();

    var optionsFunnel = {
        series: [{ data: [124, 85, 48, 12] }],
        chart: { type: 'bar', height: 320, fontFamily: 'DM Sans', toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, horizontal: true, distributed: true } },
        colors: ['#003087', '#307FE2', '#F2A900', '#1a202c'], dataLabels: { enabled: true },
        xaxis: { categories: ['Total Leads', 'Qualified', 'Quoted', 'Won'] }
    };
    new ApexCharts(document.querySelector("#funnelChart"), optionsFunnel).render();
</script>