<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BETA — Command Center</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<!-- GSAP & ApexCharts -->
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
button { font: inherit; cursor: pointer; border: none; background: none; }

/* SIDEBAR */
.sidebar { width: var(--sidebar-width); background: var(--ink); color: var(--white); display: flex; flex-direction: column; z-index: 100; flex-shrink: 0; }
.sidebar-header { padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.brand { display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 800; letter-spacing: .08em; }
.brand-mark { width: 24px; height: 24px; position: relative; transform: skew(-14deg) rotate(45deg); border: 2px solid var(--sun); }
.brand-mark:before { content: ""; position: absolute; left: 4px; top: 1px; width: 2px; height: 14px; background: var(--sun); }

.nav-menu { padding: 20px 0; flex-grow: 1; overflow-y: auto; }
.nav-label { font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.15em; padding: 0 30px; margin-bottom: 10px; display: block; }
.nav-item { display: flex; align-items: center; gap: 12px; padding: 15px 30px; font-size: 13px; font-weight: 600; color: #a0aec0; transition: 0.2s; border-left: 3px solid transparent; cursor: pointer; }
.nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
.nav-item.active { background: rgba(242, 169, 0, 0.1); color: var(--sun); border-left-color: var(--sun); }

/* MAIN CONTENT AREA */
.main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; background: var(--paper); }
.topbar { height: 80px; background: var(--white); border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; padding: 0 40px; flex-shrink: 0; position: sticky; top: 0; z-index: 50; }
.page-title { font-size: 20px; font-weight: 700; color: var(--ink); }
.user-profile { display: flex; align-items: center; gap: 15px; font-size: 13px; font-weight: 600; }
.avatar { width: 36px; height: 36px; background: var(--ocean); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; }

.module-view { display: none; padding: 40px; max-width: 1400px; margin: 0 auto; width: 100%; }
.module-view.active { display: block; }

/* SHARED UI ELEMENTS */
.action-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.btn-primary { background: var(--ocean); color: var(--white); padding: 12px 24px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase; transition: 0.3s; }
.btn-primary:hover { background: var(--ink); color: var(--sun); }
.btn-action { padding: 6px 12px; background: var(--ink); color: var(--white); border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; border: none; transition: 0.2s; }
.btn-action:hover { background: var(--ocean); }

/* KPI CARDS (Dashboard) */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 30px; }
.kpi-card { background: var(--white); padding: 25px; border-radius: 8px; border: 1px solid var(--line); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
.kpi-title { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
.kpi-value { font-size: 32px; font-weight: 800; color: var(--ink); margin-bottom: 5px; }
.kpi-trend { font-size: 12px; font-weight: 600; color: #38a169; display: flex; align-items: center; gap: 5px; }

/* CHARTS SECTION */
.charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px; }
.chart-card { background: var(--white); padding: 25px; border-radius: 8px; border: 1px solid var(--line); }
.chart-header { margin-bottom: 20px; font-size: 16px; font-weight: 700; color: var(--ink); }

/* TABLES */
.table-card { background: var(--white); border-radius: 8px; border: 1px solid var(--line); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 16px 25px; text-align: left; font-size: 13px; }
.data-table th { background: #f8fafc; font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--muted); letter-spacing: 0.05em; border-bottom: 1px solid var(--line); }
.data-table td { border-bottom: 1px solid var(--line); color: var(--charcoal); font-weight: 500; }
.data-table tr:hover { background: #f8fafc; }
.status-badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.bg-green { background: #c6f6d5; color: #22543d; }
.bg-yellow { background: #fefcbf; color: #744210; }
.bg-blue { background: #ebf8ff; color: #2b6cb0; }
.bg-red { background: #fed7d7; color: #822727; }

/* GRID CARDS (Products & Projects) */
.grid-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
.item-card { background: var(--white); border: 1px solid var(--line); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; }
.item-img { height: 160px; background: #e2e8f0; position: relative; }
.item-img img { width: 100%; height: 100%; object-fit: cover; }
.item-tag { position: absolute; top: 10px; left: 10px; background: var(--sun); color: var(--ink); font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; }
.item-body { padding: 20px; flex-grow: 1; }
.item-body h3 { font-size: 16px; margin-bottom: 8px; }
.item-body p { font-size: 13px; color: var(--muted); margin-bottom: 15px; }
.item-actions { display: flex; gap: 10px; border-top: 1px solid var(--line); padding: 15px 20px; background: #f8fafc; }

</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="brand"><span class="brand-mark"></span>BETA Panel</div>
    </div>
    <nav class="nav-menu">
        <span class="nav-label">Core Modules</span>
        <div class="nav-item active" onclick="switchTab('dashboard')">📊 Overview</div>
        <div class="nav-item" onclick="switchTab('leads')">📩 1. Quotation Leads</div>
        <div class="nav-item" onclick="switchTab('catalog')">📦 2. Product Catalog</div>
        <div class="nav-item" onclick="switchTab('portfolio')">🏗️ 3. Projects Portfolio</div>
        
        <span class="nav-label" style="margin-top: 30px;">Content & HR</span>
        <div class="nav-item" onclick="switchTab('blog')">📝 4. Insights & Blog</div>
        <div class="nav-item" onclick="switchTab('careers')">👥 5. Job Applicants</div>
        <div class="nav-item" onclick="switchTab('resources')">📂 6. Resource Center</div>
    </nav>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="page-title" id="header-title">Executive Overview</div>
        <div class="user-profile">
            <span>Admin Manager</span>
            <div class="avatar">AM</div>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- 0. DASHBOARD OVERVIEW -->
    <!-- ========================================== -->
    <div id="dashboard" class="module-view active">
        <div class="kpi-grid">
            <div class="kpi-card gsap-ui"><div class="kpi-title">Monthly RFQs</div><div class="kpi-value">124</div><div class="kpi-trend">↑ 14% vs last month</div></div>
            <div class="kpi-card gsap-ui"><div class="kpi-title">Active Projects</div><div class="kpi-value">18</div><div class="kpi-trend">↑ 2 new this week</div></div>
            <div class="kpi-card gsap-ui"><div class="kpi-title">Pending Resumes</div><div class="kpi-value">42</div><div class="kpi-trend" style="color:#e53e3e">↓ 5% vs last month</div></div>
            <div class="kpi-card gsap-ui"><div class="kpi-title">Lead Conversion</div><div class="kpi-value">28.5%</div><div class="kpi-trend">↑ 3.2% vs last quarter</div></div>
        </div>
        <div class="charts-grid">
            <div class="chart-card gsap-ui"><div class="chart-header">Quotation Requests (YTD)</div><div id="revenueChart"></div></div>
            <div class="chart-card gsap-ui"><div class="chart-header">Demand by Sector</div><div id="sectorChart"></div></div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 1. QUOTATION LEADS -->
    <!-- ========================================== -->
    <div id="leads" class="module-view">
        <div class="action-header gsap-ui">
            <h2 style="font-size:24px;">Inbound Quotations (RFQs)</h2>
            <button class="btn-primary">Export to CSV</button>
        </div>
        <div class="table-card gsap-ui">
            <table class="data-table">
                <thead><tr><th>Client / Company</th><th>Sector Request</th><th>Date Submitted</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <tr><td>Zamil Industrial</td><td>Logistics Warehouse</td><td>Today, 10:45 AM</td><td><span class="status-badge bg-blue">New RFQ</span></td><td><button class="btn-action">Download BOQ</button></td></tr>
                    <tr><td>Orascom Construction</td><td>Infrastructure Flyover</td><td>Yesterday, 2:15 PM</td><td><span class="status-badge bg-yellow">Under Review</span></td><td><button class="btn-action">Download BOQ</button></td></tr>
                    <tr><td>Hassan Allam Properties</td><td>Commercial Tower</td><td>Sept 23, 2026</td><td><span class="status-badge bg-green">Quoted</span></td><td><button class="btn-action">View Details</button></td></tr>
                    <tr><td>El Sewedy Electric</td><td>Energy Platform</td><td>Sept 21, 2026</td><td><span class="status-badge bg-blue">New RFQ</span></td><td><button class="btn-action">Download BOQ</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. PRODUCT CATALOG -->
    <!-- ========================================== -->
    <div id="catalog" class="module-view">
        <div class="action-header gsap-ui">
            <h2 style="font-size:24px;">Product Catalog Manager</h2>
            <button class="btn-primary">+ Add New Product</button>
        </div>
        <div class="grid-cards gsap-ui">
            <div class="item-card">
                <div class="item-img"><img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=80" alt="Product"></div>
                <div class="item-body">
                    <h3>Heavy Structural Columns</h3>
                    <p>ASTM A36 / A992 Steel</p>
                    <span style="font-size:11px; font-weight:bold; color:var(--ocean);">📄 Datasheet Attached</span>
                </div>
                <div class="item-actions"><button class="btn-action">Edit Spec</button><button class="btn-action" style="background:#e53e3e;">Delete</button></div>
            </div>
            <div class="item-card">
                <div class="item-img"><img src="https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=600&q=80" alt="Product"></div>
                <div class="item-body">
                    <h3>Clear-Span Roof Trusses</h3>
                    <p>S275 / S355 Structural Steel</p>
                    <span style="font-size:11px; font-weight:bold; color:var(--ocean);">📄 Datasheet Attached</span>
                </div>
                <div class="item-actions"><button class="btn-action">Edit Spec</button><button class="btn-action" style="background:#e53e3e;">Delete</button></div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 3. PROJECT PORTFOLIO -->
    <!-- ========================================== -->
    <div id="portfolio" class="module-view">
        <div class="action-header gsap-ui">
            <h2 style="font-size:24px;">Project Portfolio Manager</h2>
            <button class="btn-primary">+ Upload Project</button>
        </div>
        <div class="grid-cards gsap-ui">
            <div class="item-card">
                <div class="item-img">
                    <span class="item-tag">Industrial</span>
                    <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=600&q=80" alt="Project">
                </div>
                <div class="item-body">
                    <h3>Al-Zamil Logistics Hub</h3>
                    <p>Jeddah, KSA — 10,000 sqm heavy steel</p>
                </div>
                <div class="item-actions"><button class="btn-action">Edit Case Study</button><button class="btn-action" style="background:#e53e3e;">Hide</button></div>
            </div>
            <div class="item-card">
                <div class="item-img">
                    <span class="item-tag">Commercial</span>
                    <img src="https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=600&q=80" alt="Project">
                </div>
                <div class="item-body">
                    <h3>Cairo Executive Tower</h3>
                    <p>Cairo, Egypt — Structural Mezzanine</p>
                </div>
                <div class="item-actions"><button class="btn-action">Edit Case Study</button><button class="btn-action" style="background:#e53e3e;">Hide</button></div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 4. INSIGHTS & BLOG -->
    <!-- ========================================== -->
    <div id="blog" class="module-view">
        <div class="action-header gsap-ui">
            <h2 style="font-size:24px;">Insights & Engineering Blog</h2>
            <button class="btn-primary">✍️ Write New Article</button>
        </div>
        <div class="table-card gsap-ui">
            <table class="data-table">
                <thead><tr><th>Article Title</th><th>Category</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <tr><td>Advancements in Load-Bearing Steel</td><td>Steel Structures</td><td>Sept 15, 2026</td><td><span class="status-badge bg-green">Published</span></td><td><button class="btn-action">Edit Post</button></td></tr>
                    <tr><td>Implementing HSE Standards in Fab</td><td>Safety</td><td>Sept 02, 2026</td><td><span class="status-badge bg-green">Published</span></td><td><button class="btn-action">Edit Post</button></td></tr>
                    <tr><td>The Future of Automated Welding</td><td>Technology</td><td>-</td><td><span class="status-badge bg-yellow">Draft</span></td><td><button class="btn-action">Continue Editing</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 5. JOB APPLICANTS -->
    <!-- ========================================== -->
    <div id="careers" class="module-view">
        <div class="action-header gsap-ui">
            <h2 style="font-size:24px;">Career Applicant Tracking</h2>
            <button class="btn-primary">Manage Open Positions</button>
        </div>
        <div class="table-card gsap-ui">
            <table class="data-table">
                <thead><tr><th>Applicant Name</th><th>Target Position</th><th>Applied On</th><th>Status</th><th>Resume</th></tr></thead>
                <tbody>
                    <tr><td>Ahmed Youssef</td><td>Structural Engineer</td><td>Today</td><td><span class="status-badge bg-blue">New</span></td><td><button class="btn-action">Download CV (PDF)</button></td></tr>
                    <tr><td>Omar Tarek</td><td>Certified Welder</td><td>Yesterday</td><td><span class="status-badge bg-yellow">Interviewing</span></td><td><button class="btn-action">Download CV (PDF)</button></td></tr>
                    <tr><td>Sara Mahmoud</td><td>Project Manager</td><td>Sept 20, 2026</td><td><span class="status-badge bg-red">Rejected</span></td><td><button class="btn-action">Download CV (PDF)</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 6. RESOURCE CENTER -->
    <!-- ========================================== -->
    <div id="resources" class="module-view">
        <div class="action-header gsap-ui">
            <h2 style="font-size:24px;">Resource Document Center</h2>
            <button class="btn-primary">☁️ Upload New Document</button>
        </div>
        <div class="table-card gsap-ui">
            <table class="data-table">
                <thead><tr><th>Document Name</th><th>File Type</th><th>Last Updated</th><th>Downloads</th><th>Action</th></tr></thead>
                <tbody>
                    <tr><td>BETA_Company_Profile_2026.pdf</td><td>PDF (4.2 MB)</td><td>Jan 10, 2026</td><td>1,240</td><td><button class="btn-action">Replace File</button></td></tr>
                    <tr><td>ISO_9001_Certification.pdf</td><td>PDF (1.1 MB)</td><td>Mar 15, 2026</td><td>310</td><td><button class="btn-action">Replace File</button></td></tr>
                    <tr><td>AWS_Welding_Standards_Compliance.pdf</td><td>PDF (2.4 MB)</td><td>Feb 22, 2026</td><td>450</td><td><button class="btn-action">Replace File</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>

</main>

<script>
    // Module Titles Mapping
    const titles = {
        'dashboard': 'Executive Overview',
        'leads': '1. Quotation Lead Manager',
        'catalog': '2. Product Catalog Manager',
        'portfolio': '3. Project Portfolio Manager',
        'blog': '4. Insights & Blog Manager',
        'careers': '5. Job Applicants Manager',
        'resources': '6. Resource Center Manager'
    };

    // SPA Tab Switching Logic
    function switchTab(tabId) {
        // Update Sidebar Active State
        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');

        // Update Header Title
        document.getElementById('header-title').innerText = titles[tabId];

        // Hide all views, show target view
        document.querySelectorAll('.module-view').forEach(el => {
            el.classList.remove('active');
        });
        const targetView = document.getElementById(tabId);
        targetView.classList.add('active');

        // Re-trigger GSAP animations for the active view to make it look smooth
        gsap.fromTo(targetView.querySelectorAll('.gsap-ui'), 
            { y: 20, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.5, stagger: 0.05, ease: "power2.out" }
        );
    }

    // Initialize Dashboard Charts
    document.addEventListener("DOMContentLoaded", () => {
        // Initial Entrance Animation
        gsap.fromTo('#dashboard .gsap-ui', 
            { y: 30, opacity: 0 }, 
            { y: 0, opacity: 1, duration: 0.6, stagger: 0.1, ease: "power2.out" }
        );

        // Revenue Area Chart
        var optionsRevenue = {
            series: [{ name: 'Inbound RFQs', data: [31, 40, 28, 51, 42, 85, 100, 124] }],
            chart: { height: 300, type: 'area', fontFamily: 'DM Sans, sans-serif', toolbar: { show: false } },
            colors: ['#003087'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: { categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'], axisBorder: { show: false }, axisTicks: { show: false } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 }
        };
        new ApexCharts(document.querySelector("#revenueChart"), optionsRevenue).render();

        // Sector Donut Chart
        var optionsSector = {
            series: [44, 30, 15, 11],
            labels: ['Industrial', 'Commercial', 'Infrastructure', 'Energy'],
            chart: { type: 'donut', height: 300, fontFamily: 'DM Sans, sans-serif' },
            colors: ['#003087', '#F2A900', '#1a202c', '#307FE2'],
            plotOptions: { pie: { donut: { size: '70%' } } },
            dataLabels: { enabled: false },
            stroke: { width: 0 },
            legend: { position: 'bottom', fontSize: '13px', fontWeight: 600 }
        };
        new ApexCharts(document.querySelector("#sectorChart"), optionsSector).render();
    });
</script>
</body>
</html>