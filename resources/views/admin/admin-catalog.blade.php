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

<main class="main-content">
    <header class="topbar"><div class="page-title">Product & Specifications Manager</div><div class="user-profile"><span>Admin Manager</span><div class="avatar">AM</div></div></header>
    
    <div class="wrapper">
        <div class="flex-between gsap-ui">
            <h2 style="font-size:24px;">Active Product Catalog</h2>
            <button class="btn btn-primary">+ Add New Product Specification</button>
        </div>

        <div class="toolbar gsap-ui">
            <input type="text" placeholder="🔍 Search products or material grades...">
            <select><option>Category: All</option><option>Structural Steel</option><option>Roofing Trusses</option></select>
            <button class="btn btn-outline" style="margin-left:auto;">Bulk Edit Specs</button>
        </div>

        <div class="table-wrapper gsap-ui">
            <table>
                <thead>
                    <tr><th>Spec ID</th><th>Product Name</th><th>Material Grade</th><th>Category</th><th>Datasheet</th><th>Last Updated</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <tr><td><strong>PRD-1044</strong></td><td>Heavy Structural Columns</td><td>ASTM A36 / A992</td><td>Structural</td><td><span style="color:#38a169;">✅ Attached (v2)</span></td><td>Sep 20, 2026</td><td><button class="btn btn-outline">Edit Spec</button></td></tr>
                    <tr><td><strong>PRD-1089</strong></td><td>Clear-Span Roof Trusses</td><td>S275 / S355</td><td>Roofing</td><td><span style="color:#38a169;">✅ Attached (v1)</span></td><td>Aug 14, 2026</td><td><button class="btn btn-outline">Edit Spec</button></td></tr>
                    <tr><td><strong>PRD-1102</strong></td><td>Built-Up Girders</td><td>High-Strength Plate</td><td>Infrastructure</td><td><span style="color:#e53e3e;">❌ Missing</span></td><td>Sep 24, 2026</td><td><button class="btn btn-outline">Upload PDF</button></td></tr>
                </tbody>
            </table>
            <div class="pagination"><span>Showing 1 to 15 of 82 products</span></div>
        </div>
    </div>
</main>