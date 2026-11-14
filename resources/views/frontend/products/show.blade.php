<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->getTitle() }} — {{ __('products.seo_title') }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($product->getDescription()), 160) }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{
    --ink:#071321;
    --midnight:#00205B;
    --ocean:#003087;
    --sun:#F2A900;
    --paper:#F4F2EB;
    --white:#FFFFFF;
    --muted:#606f7b;
    --muted-light:#94a3b8;
    --line:rgba(7,19,33,0.12);
    --line-subtle:rgba(7,19,33,0.06);
    --max:1340px;
    --header-height:92px;
    --radius-lg:16px;
    --radius-md:12px;
    --radius-sm:8px;
    --ease:cubic-bezier(0.16,1,0.3,1);
    --shadow-card:0 4px 20px -2px rgba(7,19,33,0.06), 0 2px 6px -1px rgba(7,19,33,0.04);
    --shadow-elevated:0 16px 36px -6px rgba(7,19,33,0.12), 0 6px 12px -2px rgba(7,19,33,0.06);
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
    font-family:"DM Sans",sans-serif;
    background:var(--paper);
    color:var(--ink);
    line-height:1.65;
    overflow-x:hidden;
    -webkit-font-smoothing:antialiased;
}
html[dir="rtl"] body,
html[dir="rtl"] button,
html[dir="rtl"] input,
html[dir="rtl"] select,
html[dir="rtl"] textarea{
    font-family:"Cairo",sans-serif !important;
}
html[dir="rtl"] *{
    letter-spacing:0 !important;
}
html[dir="rtl"] h1,
html[dir="rtl"] h2,
html[dir="rtl"] h3{
    line-height:1.38;
}
html[dir="rtl"] p,
html[dir="rtl"] .engineering-body,
html[dir="rtl"] .spec-table td{
    line-height:1.95;
}
a{text-decoration:none;color:inherit}
button{font:inherit;cursor:pointer;border:none;background:none}
img{display:block;max-width:100%;height:auto}

main{
    min-height:calc(100vh - var(--header-height));
    padding-top:var(--header-height);
}
.site-container{
    width:min(var(--max),calc(100% - 48px));
    margin:0 auto;
}
@media (max-width:640px){
    .site-container{width:calc(100% - 32px)}
}

/* ─── ARCHITECTURAL HERO STRIP ─── */
.hero-strip{
    background:var(--midnight);
    color:var(--white);
    position:relative;
    overflow:hidden;
    padding:36px 0 42px;
    border-bottom:3px solid var(--sun);
}
.hero-strip::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:
        linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size:32px 32px;
    pointer-events:none;
}
.hero-inner{
    position:relative;
    z-index:1;
    display:flex;
    flex-direction:column;
    gap:14px;
}
.breadcrumb-nav{
    display:flex;
    align-items:center;
    flex-wrap:wrap;
    gap:8px;
    font-size:12px;
    color:rgba(255,255,255,0.65);
    font-weight:500;
}
.breadcrumb-nav a{
    color:rgba(255,255,255,0.85);
    transition:color 0.2s;
}
.breadcrumb-nav a:hover{
    color:var(--sun);
}
.breadcrumb-sep{
    opacity:0.4;
    font-size:11px;
}
.breadcrumb-current{
    color:var(--sun);
    font-weight:700;
}
.hero-meta-row{
    display:flex;
    align-items:center;
    flex-wrap:wrap;
    gap:12px;
    margin-top:4px;
}
.badge-sku{
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:rgba(242,169,0,0.15);
    border:1px solid rgba(242,169,0,0.4);
    color:var(--sun);
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    font-weight:800;
    letter-spacing:0.1em;
    text-transform:uppercase;
}
.badge-status{
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.18);
    color:rgba(255,255,255,0.9);
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    font-weight:600;
}
.badge-status::before{
    content:"";
    width:7px;
    height:7px;
    border-radius:50%;
    background:#10b981;
}
.hero-title{
    font-family:"Manrope",sans-serif;
    font-size:clamp(28px, 4.2vw, 46px);
    font-weight:800;
    line-height:1.15;
    color:var(--white);
    letter-spacing:-0.02em;
    max-width:960px;
}

/* ─── 2-COLUMN SHOWCASE ─── */
.showcase-section{
    padding:44px 0 54px;
}
.showcase-grid{
    display:grid;
    grid-template-columns:1.15fr 0.85fr;
    gap:40px;
    align-items:start;
}
@media (max-width:992px){
    .showcase-grid{
        grid-template-columns:1fr;
        gap:32px;
    }
    .sticky-action-col{
        position:static;
        top:auto;
    }
}
@media (max-width: 768px){
    .spec-table td{ word-break: break-word; }
}

/* ─── LEFT: GALLERY & HOVER ZOOM ─── */
.gallery-wrap{
    display:flex;
    flex-direction:column;
    gap:14px;
    position:relative;
}
.main-stage-container{
    position:relative;
    width:100%;
    aspect-ratio:4/3;
    background:var(--white);
    border:1px solid var(--line);
    border-radius:var(--radius-lg);
    overflow:hidden;
    box-shadow:var(--shadow-card);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:crosshair;
    user-select:none;
}
.stage-image-element{
    width:100%;
    height:100%;
    object-fit:contain;
    padding:24px;
    transition:transform 0.12s ease-out, opacity 0.25s ease;
    transform-origin:center center;
    will-change:transform, transform-origin;
}
.stage-image-element.loading-swap{
    opacity:0.25;
}
.stage-hint{
    position:absolute;
    bottom:14px;
    right:14px;
    background:rgba(7,19,33,0.78);
    backdrop-filter:blur(6px);
    color:var(--white);
    font-size:11px;
    font-weight:600;
    padding:6px 12px;
    border-radius:20px;
    pointer-events:none;
    display:flex;
    align-items:center;
    gap:6px;
    transition:opacity 0.2s;
}
html[dir="rtl"] .stage-hint{
    right:auto;
    left:14px;
}
.stage-counter-pill{
    position:absolute;
    top:14px;
    left:14px;
    background:rgba(7,19,33,0.78);
    backdrop-filter:blur(6px);
    color:var(--white);
    font-size:11px;
    font-weight:700;
    padding:6px 12px;
    border-radius:20px;
    letter-spacing:0.04em;
    z-index:2;
}
html[dir="rtl"] .stage-counter-pill{
    left:auto;
    right:14px;
}
.stage-btn-expand{
    position:absolute;
    top:14px;
    right:14px;
    width:36px;
    height:36px;
    border-radius:50%;
    background:rgba(255,255,255,0.92);
    border:1px solid var(--line);
    color:var(--ink);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 2px 8px rgba(7,19,33,0.12);
    cursor:pointer;
    z-index:2;
    transition:background 0.2s, transform 0.2s;
}
html[dir="rtl"] .stage-btn-expand{
    right:auto;
    left:14px;
}
.stage-btn-expand:hover{
    background:var(--sun);
    color:var(--ink);
    transform:scale(1.08);
}

/* Thumbnail Carousel Strip */
.thumb-carousel-row{
    display:flex;
    align-items:center;
    gap:8px;
    position:relative;
}
.thumb-nav-btn{
    width:34px;
    height:34px;
    flex-shrink:0;
    border-radius:50%;
    background:var(--white);
    border:1px solid var(--line);
    color:var(--ink);
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 2px 6px rgba(7,19,33,0.08);
    cursor:pointer;
    transition:background 0.2s, transform 0.2s, border-color 0.2s;
}
.thumb-nav-btn:hover{
    background:var(--sun);
    border-color:var(--sun);
    transform:scale(1.05);
}
.thumb-nav-btn:disabled{
    opacity:0.35;
    cursor:not-allowed;
    transform:none;
}
.thumb-track{
    display:flex;
    gap:10px;
    overflow-x:auto;
    scroll-behavior:smooth;
    padding:4px 2px;
    flex:1;
    -ms-overflow-style:none;
    scrollbar-width:none;
}
.thumb-track::-webkit-scrollbar{
    display:none;
}
.thumb-card{
    width:84px;
    height:64px;
    flex-shrink:0;
    background:var(--white);
    border:2px solid transparent;
    border-radius:var(--radius-sm);
    overflow:hidden;
    padding:4px;
    cursor:pointer;
    box-shadow:0 1px 4px rgba(7,19,33,0.06);
    transition:border-color 0.2s, transform 0.2s, box-shadow 0.2s;
}
.thumb-card img{
    width:100%;
    height:100%;
    object-fit:contain;
}
.thumb-card:hover{
    border-color:rgba(242,169,0,0.5);
    transform:translateY(-2px);
}
.thumb-card.active{
    border-color:var(--sun);
    box-shadow:0 0 0 3px rgba(242,169,0,0.22);
    transform:translateY(-2px);
}

/* ─── RIGHT: STICKY ACTION & QUICK-SPEC CARD ─── */
.sticky-action-col{
    position:sticky;
    top:calc(var(--header-height) + 24px);
}
.action-card{
    background:var(--white);
    border:1px solid var(--line);
    border-radius:var(--radius-lg);
    padding:32px 30px;
    box-shadow:var(--shadow-card);
    display:flex;
    flex-direction:column;
    gap:22px;
}
@media (max-width:640px){
    .action-card{padding:24px 20px}
}
.summary-block{
    display:flex;
    flex-direction:column;
    gap:12px;
}
.summary-tagline{
    font-size:12px;
    font-weight:800;
    letter-spacing:0.14em;
    text-transform:uppercase;
    color:var(--ocean);
}
.summary-text{
    font-size:15px;
    line-height:1.7;
    color:#475569;
}
html[dir="rtl"] .summary-text{
    font-size:15px;
    line-height:1.9;
}

/* Top Attribute Pills */
.quick-specs-grid{
    display:grid;
    grid-template-columns:repeat(2, 1fr);
    gap:10px;
    padding:16px 0;
    border-top:1px solid var(--line-subtle);
    border-bottom:1px solid var(--line-subtle);
}
@media (max-width:480px){
    .quick-specs-grid{grid-template-columns:1fr}
}
.spec-pill{
    background:#f8fafc;
    border:1px solid rgba(7,19,33,0.06);
    border-radius:var(--radius-sm);
    padding:10px 14px;
    display:flex;
    flex-direction:column;
    gap:3px;
}
.pill-key{
    font-size:11px;
    font-weight:700;
    color:var(--muted-light);
    text-transform:uppercase;
    letter-spacing:0.04em;
}
.pill-val{
    font-size:13px;
    font-weight:700;
    color:var(--ink);
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* Primary CTA: Request a Quote (Option A Session Flash + Redirect Flow) */
.cta-form-container{
    display:flex;
    flex-direction:column;
    gap:8px;
}
.btn-primary-quote{
    width:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:12px;
    background:var(--sun);
    color:var(--ink);
    font-size:14px;
    font-weight:800;
    letter-spacing:0.08em;
    text-transform:uppercase;
    padding:18px 24px;
    border-radius:10px;
    cursor:pointer;
    box-shadow:0 8px 20px rgba(242,169,0,0.3);
    transition:background 0.25s var(--ease), transform 0.25s var(--ease), box-shadow 0.25s var(--ease);
}
.btn-primary-quote:hover{
    background:#e09a00;
    transform:translateY(-2px);
    box-shadow:0 12px 28px rgba(242,169,0,0.42);
}
.btn-primary-quote svg{
    transition:transform 0.25s var(--ease);
}
.btn-primary-quote:hover svg{
    transform:translateX(4px);
}
html[dir="rtl"] .btn-primary-quote:hover svg{
    transform:translateX(-4px);
}
.cta-subtext{
    font-size:11px;
    color:var(--muted);
    text-align:center;
}

/* Technical File Download Box */
.tech-file-box{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    background:#fafbfe;
    border:1px solid rgba(0,48,135,0.18);
    border-left:4px solid var(--ocean);
    border-radius:var(--radius-sm);
    padding:14px 16px;
    transition:background 0.2s, border-color 0.2s;
}
html[dir="rtl"] .tech-file-box{
    border-left:1px solid rgba(0,48,135,0.18);
    border-right:4px solid var(--ocean);
}
.tech-file-box:hover{
    background:#f0f5ff;
    border-color:var(--ocean);
}
.tech-file-left{
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
}
.tech-file-icon{
    width:38px;
    height:38px;
    border-radius:8px;
    background:rgba(0,48,135,0.08);
    color:var(--ocean);
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}
.tech-file-text{
    display:flex;
    flex-direction:column;
    min-width:0;
}
.tech-file-label{
    font-size:12px;
    font-weight:800;
    color:var(--ink);
    text-transform:uppercase;
    letter-spacing:0.04em;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.tech-file-meta{
    font-size:11px;
    color:var(--muted);
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.btn-tech-download{
    display:inline-flex;
    align-items:center;
    gap:6px;
    background:var(--ocean);
    color:var(--white);
    padding:9px 14px;
    border-radius:6px;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:0.06em;
    flex-shrink:0;
    transition:background 0.2s, transform 0.2s;
}
.btn-tech-download:hover{
    background:var(--midnight);
    color:var(--sun);
    transform:translateY(-1px);
}

/* Secondary Actions (Copy Link & WhatsApp) */
.secondary-actions-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    margin-top:2px;
}
.btn-outline-action{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    background:var(--white);
    border:1px solid var(--line);
    color:var(--ink);
    padding:11px 16px;
    border-radius:8px;
    font-size:12px;
    font-weight:700;
    cursor:pointer;
    transition:border-color 0.2s, background 0.2s, color 0.2s;
}
.btn-outline-action:hover{
    border-color:var(--ink);
    background:#f8fafc;
}
.btn-outline-action.btn-whatsapp{
    border-color:rgba(37,211,102,0.3);
    color:#128c7e;
    background:rgba(37,211,102,0.06);
}
.btn-outline-action.btn-whatsapp:hover{
    background:#25d366;
    border-color:#25d366;
    color:var(--white);
}

/* ─── TOAST NOTIFICATION ─── */
.toast-notice{
    position:fixed;
    bottom:28px;
    right:28px;
    background:var(--ink);
    color:var(--white);
    padding:14px 22px;
    border-radius:10px;
    font-size:13px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
    box-shadow:var(--shadow-elevated);
    z-index:9999;
    transform:translateY(100px);
    opacity:0;
    pointer-events:none;
    transition:transform 0.3s var(--ease), opacity 0.3s var(--ease);
}
html[dir="rtl"] .toast-notice{
    right:auto;
    left:28px;
}
.toast-notice.show{
    transform:translateY(0);
    opacity:1;
}
.toast-notice svg{
    color:var(--sun);
    flex-shrink:0;
}

/* ─── TECHNICAL SPECIFICATIONS TABLE ─── */
.content-section{
    padding:20px 0 36px;
}
.card-section{
    background:var(--white);
    border:1px solid var(--line);
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow-card);
    overflow:hidden;
    margin-bottom:34px;
}
.card-section-header{
    padding:20px 28px;
    background:#fafbfd;
    border-bottom:1px solid var(--line-subtle);
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.card-section-title{
    font-size:13px;
    font-weight:800;
    letter-spacing:0.16em;
    text-transform:uppercase;
    color:var(--ocean);
}
.spec-table{
    width:100%;
    border-collapse:collapse;
}
.spec-table th{
    background:#f8fafc;
    color:var(--ink);
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:0.06em;
    padding:14px 28px;
    text-align:left;
    border-bottom:1px solid var(--line-subtle);
}
html[dir="rtl"] .spec-table th{
    text-align:right;
}
.spec-table td{
    font-size:14px;
    padding:16px 28px;
    border-bottom:1px solid var(--line-subtle);
    vertical-align:middle;
}
.spec-table tr:last-child td{
    border-bottom:none;
}
.spec-table tr:nth-child(even) td{
    background:#fafbfe;
}
.spec-attr-name{
    font-weight:700;
    color:var(--ink);
    width:35%;
}
.spec-attr-val{
    color:#334155;
    font-weight:500;
}

/* ─── ENGINEERING DESCRIPTION BLOCK ─── */
.engineering-block{
    background:var(--white);
    border:1px solid var(--line);
    border-radius:var(--radius-lg);
    padding:36px 36px;
    box-shadow:var(--shadow-card);
    margin-bottom:44px;
    border-inline-start:5px solid var(--sun);
}
.engineering-eyebrow{
    font-size:12px;
    font-weight:800;
    letter-spacing:0.16em;
    text-transform:uppercase;
    color:var(--sun);
    margin-bottom:16px;
    display:block;
}
.engineering-body{
    font-size:16px;
    line-height:1.8;
    color:#334155;
}
.engineering-body p{margin-bottom:14px}
.engineering-body p:last-child{margin-bottom:0}

/* ─── RELATED PRODUCTS STRIP ─── */
.related-section{
    padding:20px 0 60px;
}
.section-header-row{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    margin-bottom:26px;
}
.section-heading-col{
    display:flex;
    flex-direction:column;
    gap:6px;
}
.section-eyebrow{
    font-size:11px;
    font-weight:800;
    letter-spacing:0.16em;
    text-transform:uppercase;
    color:var(--ocean);
}
.section-title{
    font-family:"Manrope",sans-serif;
    font-size:clamp(22px, 3vw, 30px);
    font-weight:800;
    color:var(--ink);
    letter-spacing:-0.02em;
}
.related-grid{
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:22px;
}
@media (max-width:1100px){
    .related-grid{grid-template-columns:repeat(2, 1fr)}
}
@media (max-width:640px){
    .related-grid{grid-template-columns:1fr}
}
.related-card{
    background:var(--white);
    border:1px solid var(--line);
    border-radius:var(--radius-md);
    overflow:hidden;
    display:flex;
    flex-direction:column;
    box-shadow:var(--shadow-card);
    transition:transform 0.25s var(--ease), border-color 0.25s var(--ease), box-shadow 0.25s var(--ease);
}
.related-card:hover{
    transform:translateY(-4px);
    border-color:var(--sun);
    box-shadow:var(--shadow-elevated);
}
.related-card-img{
    width:100%;
    aspect-ratio:4/3;
    background:#fafbfe;
    overflow:hidden;
    padding:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-bottom:1px solid var(--line-subtle);
}
.related-card-img img{
    width:100%;
    height:100%;
    object-fit:contain;
    transition:transform 0.3s var(--ease);
}
.related-card:hover .related-card-img img{
    transform:scale(1.06);
}
.related-card-body{
    padding:20px;
    display:flex;
    flex-direction:column;
    gap:10px;
    flex:1;
}
.related-sku{
    font-size:10px;
    font-weight:800;
    letter-spacing:0.12em;
    text-transform:uppercase;
    color:var(--ocean);
}
.related-title{
    font-size:16px;
    font-weight:700;
    line-height:1.35;
    color:var(--ink);
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}
.related-footer{
    margin-top:auto;
    padding-top:12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size:12px;
    font-weight:700;
    color:var(--ocean);
}
.related-card:hover .related-footer{
    color:var(--sun);
}

/* ─── FULLSCREEN LIGHTBOX MODAL ─── */
.lightbox-modal{
    position:fixed;
    inset:0;
    z-index:99999;
    background:rgba(7,19,33,0.95);
    backdrop-filter:blur(10px);
    display:none;
    align-items:center;
    justify-content:center;
    padding:30px;
}
.lightbox-modal.active{
    display:flex;
}
.lightbox-content{
    position:relative;
    max-width:92vw;
    max-height:88vh;
    display:flex;
    align-items:center;
    justify-content:center;
}
.lightbox-content img{
    max-width:100%;
    max-height:86vh;
    object-fit:contain;
    border-radius:var(--radius-md);
    box-shadow:0 20px 50px rgba(0,0,0,0.5);
}
.lightbox-close-btn{
    position:absolute;
    top:-42px;
    right:0;
    width:36px;
    height:36px;
    border-radius:50%;
    background:rgba(255,255,255,0.15);
    color:var(--white);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    font-size:20px;
    transition:background 0.2s;
}
html[dir="rtl"] .lightbox-close-btn{
    right:auto;
    left:0;
}
.lightbox-close-btn:hover{
    background:var(--sun);
    color:var(--ink);
}
.lightbox-arrow{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:48px;
    height:48px;
    border-radius:50%;
    background:rgba(255,255,255,0.15);
    color:var(--white);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:background 0.2s, transform 0.2s;
    z-index:2;
}
.lightbox-arrow:hover{
    background:var(--sun);
    color:var(--ink);
}
.lightbox-arrow.prev{left:-64px}
.lightbox-arrow.next{right:-64px}
@media (max-width:768px){
    .lightbox-arrow.prev{left:10px}
    .lightbox-arrow.next{right:10px}
}
.lightbox-counter{
    position:absolute;
    bottom:-38px;
    left:50%;
    transform:translateX(-50%);
    color:rgba(255,255,255,0.75);
    font-size:13px;
    font-weight:600;
}

/* ─── FOOTER ─── */
.page-footer{
    background:var(--ink);
    color:var(--white);
    padding:40px 0;
    border-top:1px solid rgba(255,255,255,0.1);
}
.footer-inner{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:16px;
    font-size:12px;
    color:rgba(255,255,255,0.65);
}
.footer-brand{
    font-weight:800;
    color:var(--white);
    font-size:16px;
    letter-spacing:0.08em;
}
.footer-brand span{
    color:var(--sun);
}
</style>
</head>
<body>
<x-header />

<main>
    {{-- 1. COMPACT ARCHITECTURAL HERO STRIP --}}
    <section class="hero-strip">
        <div class="site-container">
            <div class="hero-inner">
                <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">{{ __('app.nav.home') }}</a>
                    <span class="breadcrumb-sep">/</span>
                    <a href="{{ route('products') }}">{{ __('app.nav.products') }}</a>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-current">{{ $product->getTitle() }}</span>
                </nav>

                <div class="hero-meta-row">
                    <span class="badge-sku">{{ strtoupper($product->slug) }}</span>
                    <span class="badge-status">{{ $product->is_active ? __('products.active') : __('products.inactive') }}</span>
                </div>

                <h1 class="hero-title">{!! $product->getTitle() !!}</h1>
            </div>
        </div>
    </section>

    {{-- 2. 2-COLUMN SHOWCASE --}}
    <section class="showcase-section">
        <div class="site-container">
            <div class="showcase-grid">

                {{-- Left: Interactive Media Gallery with Amazon-Style Zoom --}}
                <div class="gallery-wrap">
                    @php
                        $galleryImages = collect();
                        if ($product->images->isNotEmpty()) {
                            foreach ($product->images as $img) {
                                $galleryImages->push(asset($img->image_path));
                            }
                        }
                        if ($galleryImages->isEmpty() && $product->attachments->where('mime_type', 'like', 'image/%')->isNotEmpty()) {
                            foreach ($product->attachments->where('mime_type', 'like', 'image/%') as $att) {
                                $galleryImages->push(asset($att->file_path));
                            }
                        }
                        if ($galleryImages->isEmpty()) {
                            $galleryImages->push(asset('images/placeholder-product.svg'));
                        }
                        $firstImage = $galleryImages->first();
                        $totalImages = $galleryImages->count();
                    @endphp

                    <div class="main-stage-container" id="mainStage" data-index="0" aria-label="Product Showcase Image">
                        <span class="stage-counter-pill"><span id="stageCounter">1</span> / {{ $totalImages }}</span>
                        
                        <button type="button" class="stage-btn-expand" id="expandBtn" aria-label="Fullscreen Lightbox" title="{{ app()->getLocale() === 'ar' ? 'عرض بالحجم الكامل' : 'Expand Fullscreen' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                            </svg>
                        </button>

                        <img id="stageImg" class="stage-image-element" src="{{ $firstImage }}" alt="{{ $product->getTitle() }}">

                        <div class="stage-hint">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                            <span>{{ app()->getLocale() === 'ar' ? 'مرر للتكبير • انقر للتكبير الكامل' : 'Hover to zoom • Click to expand' }}</span>
                        </div>
                    </div>

                    {{-- Clickable Thumbnail Strip with Arrow Navigation --}}
                    @if($totalImages > 1)
                    <div class="thumb-carousel-row">
                        <button type="button" class="thumb-nav-btn" id="thumbPrev" aria-label="Previous image">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="{{ app()->getLocale() === 'ar' ? '9 18 15 12 9 6' : '15 18 9 12 15 6' }}"></polyline>
                            </svg>
                        </button>

                        <div class="thumb-track" id="thumbTrack">
                            @foreach($galleryImages as $idx => $imgUrl)
                            <div class="thumb-card {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" data-src="{{ $imgUrl }}" onclick="selectGalleryIndex({{ $idx }})">
                                <img src="{{ $imgUrl }}" alt="Thumb {{ $idx + 1 }}" loading="lazy">
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="thumb-nav-btn" id="thumbNext" aria-label="Next image">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="{{ app()->getLocale() === 'ar' ? '15 18 9 12 15 6' : '9 18 15 12 9 6' }}"></polyline>
                            </svg>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Right: Sticky Action & Quick-Spec Card --}}
                <div class="sticky-action-col">
                    <div class="action-card">
                        
                        <div class="summary-block">
                            <span class="summary-tagline">{{ __('products.heading') }}</span>
                            <p class="summary-text">
                                {!! \Illuminate\Support\Str::limit(strip_tags($product->getDescription()), 240) !!}
                            </p>
                        </div>

                        {{-- Top 3-4 Attribute Pills --}}
                        @if($product->attributes->isNotEmpty())
                        <div class="quick-specs-grid">
                            @foreach($product->attributes->take(4) as $attr)
                            <div class="spec-pill">
                                <span class="pill-key">{{ $attr->getName() }}</span>
                                <span class="pill-val" title="{{ $attr->getValue() }}">{{ $attr->getValue() }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Primary CTA: Request a Quote (Option A Session Flash + Redirect Flow, Zero Numeric ID in URL) --}}
                        <div class="cta-form-container">
                            <form action="{{ route('quote.select-product', $product->slug) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-primary-quote">
                                    <span>{{ __('products.quote_cta') }}</span>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="{{ app()->getLocale() === 'ar' ? '12 19 5 12 12 5' : '12 5 19 12 12 19' }}"></polyline>
                                    </svg>
                                </button>
                            </form>
                            <span class="cta-subtext">{{ __('products.quote_sub') }}</span>
                        </div>

                        {{-- Technical File Download Box --}}
                        @php
                            $hasTechFile = !empty($product->file_path);
                        @endphp
                        @if($hasTechFile)
                        <div class="tech-file-box">
                            <div class="tech-file-left">
                                <div class="tech-file-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <div class="tech-file-text">
                                    <span class="tech-file-label">{{ __('products.technical_file') }}</span>
                                    <span class="tech-file-meta">
                                        {{ basename($product->file_path) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('products.download-file', $product->slug) }}" class="btn-tech-download" download title="{{ __('products.download_datasheet') }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <span>{{ __('products.download_datasheet') }}</span>
                            </a>
                        </div>
                        @endif

                        {{-- Secondary Actions: Copy Link & WhatsApp --}}
                        <div class="secondary-actions-row">
                            <button type="button" class="btn-outline-action" onclick="copyProductLink()" id="btnCopyLink">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <span id="copyBtnLabel">{{ app()->getLocale() === 'ar' ? 'نسخ الرابط' : 'Copy Link' }}</span>
                            </button>

                            @php
                                $waMsg = (app()->getLocale() === 'ar' ? 'مرحبًا شركة بيتا الصناعية، أود الاستفسار عن عرض سعر لمنتج: ' : 'Hello BETA Industrial, I would like to request a quote for product: ')
                                    . $product->getTitle() . ' (' . url()->current() . ')';
                            @endphp
                            <a href="https://wa.me/201000000000?text={{ rawurlencode($waMsg) }}" target="_blank" rel="noopener noreferrer" class="btn-outline-action btn-whatsapp">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>
                                <span>{{ app()->getLocale() === 'ar' ? 'واتساب' : 'WhatsApp' }}</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 3. STRUCTURED SPECIFICATIONS TABLE --}}
    @if($product->attributes->isNotEmpty())
    <section class="content-section">
        <div class="site-container">
            <div class="card-section">
                <div class="card-section-header">
                    <span class="card-section-title">{{ __('products.specifications') }}</span>
                    <span style="font-size:12px;color:var(--muted);font-weight:600">{{ $product->attributes->count() }} {{ app()->getLocale() === 'ar' ? 'سمات فنية' : 'Attributes' }}</span>
                </div>
                <table class="spec-table">
                    <thead>
                        <tr>
                            <th style="width:38%;">{{ __('products.attribute_name') }}</th>
                            <th>{{ __('products.attribute_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->attributes as $attribute)
                        <tr>
                            <td class="spec-attr-name">{{ $attribute->getName() }}</td>
                            <td class="spec-attr-val">{{ $attribute->getValue() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- 4. FULL READABLE ENGINEERING DESCRIPTION BLOCK --}}
    @if(!empty($product->getDescription()))
    <section class="content-section">
        <div class="site-container">
            <div class="engineering-block">
                <span class="engineering-eyebrow">{{ __('products.spec_sheet_title') }}</span>
                <div class="engineering-body">
                    {!! $product->getDescription() !!}
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- 5. RELATED PRODUCTS STRIP --}}
    @php
        $relatedItems = $relatedProducts ?? \App\Models\Product::where('id', '!=', $product->id)->where('is_active', true)->with(['images', 'attachments'])->take(4)->get();
    @endphp
    @if($relatedItems->isNotEmpty())
    <section class="related-section">
        <div class="site-container">
            <div class="section-header-row">
                <div class="section-heading-col">
                    <span class="section-eyebrow">BETA INDUSTRIAL CATALOG</span>
                    <h2 class="section-title">{{ app()->getLocale() === 'ar' ? 'منتجات صناعية ذات صلة' : 'Related Industrial Solutions' }}</h2>
                </div>
                <a href="{{ route('products') }}" style="font-size:13px;font-weight:800;color:var(--ocean);text-transform:uppercase;letter-spacing:0.06em">
                    {{ __('products.back_to_catalog') }}
                </a>
            </div>

            <div class="related-grid">
                @foreach($relatedItems as $rel)
                    @php
                        $relCover = $rel->images->first()?->image_path
                            ?? $rel->attachments->where('mime_type', 'like', 'image/%')->first()?->file_path
                            ?? 'images/placeholder-product.svg';
                    @endphp
                    <a href="{{ route('products.show', $rel->slug) }}" class="related-card">
                        <div class="related-card-img">
                            <img src="{{ asset($relCover) }}" alt="{{ $rel->getTitle() }}" loading="lazy">
                        </div>
                        <div class="related-card-body">
                            <span class="related-sku">{{ strtoupper($rel->slug) }}</span>
                            <h3 class="related-title">{{ $rel->getTitle() }}</h3>
                            <div class="related-footer">
                                <span>{{ app()->getLocale() === 'ar' ? 'عرض المواصفات' : 'View Specs' }}</span>
                                <span style="font-size:16px;">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</main>

{{-- Toast Notification --}}
<div id="copyToast" class="toast-notice" role="alert" aria-live="polite">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    <span>{{ app()->getLocale() === 'ar' ? 'تم نسخ رابط المنتج بنجاح!' : 'Product link copied to clipboard!' }}</span>
</div>

{{-- Fullscreen Lightbox Modal --}}
<div id="lightboxModal" class="lightbox-modal" role="dialog" aria-modal="true" aria-label="Fullscreen Gallery Viewer">
    <div class="lightbox-content">
        <button type="button" class="lightbox-close-btn" id="lbClose" aria-label="Close Lightbox">✕</button>
        <button type="button" class="lightbox-arrow prev" id="lbPrev" aria-label="Previous Image">❮</button>
        <img id="lbImg" src="{{ $firstImage }}" alt="Fullscreen Product Image">
        <button type="button" class="lightbox-arrow next" id="lbNext" aria-label="Next Image">❯</button>
        <div class="lightbox-counter" id="lbCounter">1 / {{ $totalImages }}</div>
    </div>
</div>

<footer class="page-footer">
    <div class="site-container">
        <div class="footer-inner">
            <div class="footer-brand"><span>BETA</span> INDUSTRIAL</div>
            <div>© {{ date('Y') }} BETA Industrial. {{ __('app.footer.tagline') }}</div>
        </div>
    </div>
</footer>

<x-success-modal />

<script>
(function(){
    var galleryImages = @json($galleryImages->values());
    var currentIndex = 0;
    var totalImages = galleryImages.length;

    var stage = document.getElementById('mainStage');
    var stageImg = document.getElementById('stageImg');
    var stageCounter = document.getElementById('stageCounter');
    var thumbTrack = document.getElementById('thumbTrack');
    var thumbPrev = document.getElementById('thumbPrev');
    var thumbNext = document.getElementById('thumbNext');
    var expandBtn = document.getElementById('expandBtn');

    var lbModal = document.getElementById('lightboxModal');
    var lbImg = document.getElementById('lbImg');
    var lbCounter = document.getElementById('lbCounter');
    var lbClose = document.getElementById('lbClose');
    var lbPrev = document.getElementById('lbPrev');
    var lbNext = document.getElementById('lbNext');

    // ── 1. SELECT GALLERY IMAGE ──
    window.selectGalleryIndex = function(idx) {
        if (idx < 0) idx = totalImages - 1;
        if (idx >= totalImages) idx = 0;
        currentIndex = idx;

        if (stageImg) {
            stageImg.classList.add('loading-swap');
            setTimeout(function(){
                stageImg.src = galleryImages[currentIndex];
                stageImg.classList.remove('loading-swap');
            }, 120);
        }

        if (stageCounter) stageCounter.textContent = (currentIndex + 1);
        if (lbCounter) lbCounter.textContent = (currentIndex + 1) + ' / ' + totalImages;
        if (lbImg) lbImg.src = galleryImages[currentIndex];

        // Update active thumb
        document.querySelectorAll('.thumb-card').forEach(function(card){
            var cIdx = parseInt(card.getAttribute('data-index'), 10);
            if (cIdx === currentIndex) {
                card.classList.add('active');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                card.classList.remove('active');
            }
        });
    };

    // Thumbnail navigation buttons
    if (thumbPrev) thumbPrev.addEventListener('click', function(){ selectGalleryIndex(currentIndex - 1); });
    if (thumbNext) thumbNext.addEventListener('click', function(){ selectGalleryIndex(currentIndex + 1); });

    // ── 2. AMAZON-STYLE DESKTOP HOVER ZOOM ──
    var isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

    if (stage && stageImg && !isTouch) {
        stage.addEventListener('mousemove', function(e){
            var rect = stage.getBoundingClientRect();
            var x = ((e.clientX - rect.left) / rect.width) * 100;
            var y = ((e.clientY - rect.top) / rect.height) * 100;
            stageImg.style.transformOrigin = x + '% ' + y + '%';
            stageImg.style.transform = 'scale(2.2)';
        });

        stage.addEventListener('mouseleave', function(){
            stageImg.style.transform = 'scale(1)';
            stageImg.style.transformOrigin = 'center center';
        });
    }

    // Click stage or expand button to open lightbox
    if (stage) {
        stage.addEventListener('click', function(e){
            if (e.target.closest('#expandBtn')) return;
            openLightbox(currentIndex);
        });
    }
    if (expandBtn) {
        expandBtn.addEventListener('click', function(e){
            e.stopPropagation();
            openLightbox(currentIndex);
        });
    }

    // ── 3. FULLSCREEN LIGHTBOX MODAL ──
    function openLightbox(idx) {
        selectGalleryIndex(idx);
        if (lbModal) {
            lbModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLightbox() {
        if (lbModal) {
            lbModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lbPrev) lbPrev.addEventListener('click', function(e){ e.stopPropagation(); selectGalleryIndex(currentIndex - 1); });
    if (lbNext) lbNext.addEventListener('click', function(e){ e.stopPropagation(); selectGalleryIndex(currentIndex + 1); });

    if (lbModal) {
        lbModal.addEventListener('click', function(e){
            if (e.target === lbModal) closeLightbox();
        });
    }

    // ── 4. KEYBOARD NAVIGATION (Arrows & Escape) ──
    document.addEventListener('keydown', function(e){
        if (lbModal && lbModal.classList.contains('active')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') selectGalleryIndex(currentIndex - 1);
            if (e.key === 'ArrowRight') selectGalleryIndex(currentIndex + 1);
        } else {
            if (e.key === 'ArrowLeft') selectGalleryIndex(currentIndex - 1);
            if (e.key === 'ArrowRight') selectGalleryIndex(currentIndex + 1);
        }
    });

    // ── 5. COPY PRODUCT LINK TO CLIPBOARD ──
    window.copyProductLink = function() {
        var url = window.location.href;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(showCopyToast).catch(fallbackCopy);
        } else {
            fallbackCopy(url);
        }
    };

    function fallbackCopy(url) {
        var tempInput = document.createElement('input');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand('copy');
            showCopyToast();
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(tempInput);
    }

    function showCopyToast() {
        var toast = document.getElementById('copyToast');
        if (!toast) return;
        toast.classList.add('show');
        setTimeout(function(){
            toast.classList.remove('show');
        }, 2800);
    }
})();
</script>
</body>
</html>
