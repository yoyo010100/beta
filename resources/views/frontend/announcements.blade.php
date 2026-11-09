@extends('layouts.frontend')

@php
    $ar = app()->getLocale() === 'ar';

    $typeLabels = [
        'news' => $ar ? 'أخبار' : 'News',
        'press_release' => $ar ? 'بيانات صحفية' : 'Press Releases',
        'event' => $ar ? 'فعاليات' : 'Events',
    ];

    $pageTitle = $ar ? 'الأخبار والإعلانات' : 'News & Announcements';
    $pageIntro = $ar
        ? 'تابع أحدث مستجدات بيتا الصناعية، وإنجازات المشاريع، والرؤى الهندسية من فريقنا.'
        : 'Stay up to date with BETA Industrial projects, company news, and engineering insights from our team.';
@endphp

@section('title', $pageTitle . ' — BETA Industrial')
@section('meta_description', $pageIntro)

@section('content')
<!-- Page hero -->
<section class="ann-hero">
    <div class="ann-hero-grid" aria-hidden="true"></div>
    <div class="container">
        <div class="ann-hero-inner">
            <div class="ann-hero-label">{{ $ar ? 'مركز المحتوى' : 'Content Hub' }}</div>
            <h1>{{ $pageTitle }}</h1>
            <p>{{ $pageIntro }}</p>
        </div>
    </div>
</section>

<!-- Announcements list -->
<section class="ann-list-section">
    <div class="container">
        <div class="ann-toolbar">
            <nav class="ann-filters" aria-label="{{ $ar ? 'تصفية الإعلانات' : 'Filter announcements' }}">
                <a href="{{ route('announcements.index') }}"
                   @class(['ann-filter', 'is-active' => $activeType === null])>
                    {{ $ar ? 'الكل' : 'All' }}
                </a>
                @foreach($types as $type)
                    <a href="{{ route('announcements.index', ['type' => $type]) }}"
                       @class(['ann-filter', 'is-active' => $activeType === $type])>
                        {{ $typeLabels[$type] }}
                    </a>
                @endforeach
            </nav>

            <span class="ann-count">
                {{ $announcements->total() }} {{ $ar ? 'منشور' : ($announcements->total() === 1 ? 'update' : 'updates') }}
            </span>
        </div>

        @if($announcements->isNotEmpty())
            <div class="ann-grid">
                @foreach($announcements as $announcement)
                    @php
                        $title = $announcement->getTranslated('title');
                        $excerpt = $announcement->getTranslated('excerpt')
                            ?: $announcement->getTranslated('content');
                        $typeLabel = $typeLabels[$announcement->type]
                            ?? ucfirst(str_replace('_', ' ', $announcement->type ?? 'news'));
                    @endphp

                    <article class="ann-card">
                        <div class="ann-card-top">
                            <span class="ann-type">{{ $typeLabel }}</span>
                            <time datetime="{{ $announcement->published_at?->format('c') }}">
                                {{ $announcement->published_at?->format('M d, Y') ?? '—' }}
                            </time>
                        </div>

                        <h2>
                            <a href="{{ route('announcements.show', $announcement->public_id) }}">
                                {{ $title }}
                            </a>
                        </h2>

                        <p class="ann-excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags($excerpt), 155) }}
                        </p>

                        <a href="{{ route('announcements.show', $announcement->public_id) }}" class="ann-read">
                            {{ $ar ? 'اقرأ المزيد' : 'Read update' }} <span aria-hidden="true">→</span>
                        </a>
                    </article>
                @endforeach
            </div>

            @if($announcements->hasPages())
                <nav class="ann-pagination" aria-label="{{ $ar ? 'صفحات الإعلانات' : 'Announcements pagination' }}">
                    @if($announcements->onFirstPage())
                        <span class="ann-page is-disabled" aria-disabled="true">←</span>
                    @else
                        <a class="ann-page" href="{{ $announcements->previousPageUrl() }}" rel="prev">←</a>
                    @endif

                    @foreach($announcements->getUrlRange(max(1, $announcements->currentPage() - 2), min($announcements->lastPage(), $announcements->currentPage() + 2)) as $page => $url)
                        @if($page === $announcements->currentPage())
                            <span class="ann-page is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="ann-page" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($announcements->hasMorePages())
                        <a class="ann-page" href="{{ $announcements->nextPageUrl() }}" rel="next">→</a>
                    @else
                        <span class="ann-page is-disabled" aria-disabled="true">→</span>
                    @endif
                </nav>
            @endif
        @else
            <div class="ann-empty">
                <span class="ann-empty-mark" aria-hidden="true">BETA</span>
                <h2>{{ $ar ? 'لا توجد إعلانات منشورة بعد' : 'No published updates yet' }}</h2>
                <p>
                    {{ $ar
                        ? 'سيتم نشر آخر الأخبار وتحديثات المشاريع هنا بمجرد إضافتها من لوحة الإدارة.'
                        : 'New company news and project updates will appear here as soon as they are published.' }}
                </p>
                @if($activeType)
                    <a href="{{ route('announcements.index') }}" class="ann-clear-filter">
                        {{ $ar ? 'عرض كل الإعلانات' : 'View all updates' }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .ann-hero {
        position: relative;
        overflow: hidden;
        padding: 105px 0 90px;
        background: linear-gradient(135deg, var(--ink) 0%, #0b2545 55%, var(--ocean) 100%);
        color: var(--white);
    }
    .ann-hero-grid {
        position: absolute;
        inset: 0;
        opacity: .35;
        background-image:
            linear-gradient(rgba(255, 255, 255, .07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, .07) 1px, transparent 1px);
        background-size: 72px 72px;
        mask-image: linear-gradient(to bottom, rgba(0,0,0,.9), transparent 85%);
        -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,.9), transparent 85%);
    }
    .ann-hero-inner {
        position: relative;
        z-index: 1;
        max-width: 850px;
    }
    .ann-hero-label {
        color: var(--sun);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .24em;
        text-transform: uppercase;
        margin-bottom: 18px;
    }
    .ann-hero h1 {
        font-family: "Manrope", sans-serif;
        font-size: clamp(42px, 6vw, 72px);
        line-height: 1.02;
        letter-spacing: -.035em;
        margin-bottom: 22px;
    }
    html[dir="rtl"] .ann-hero h1 { font-family: "Cairo", sans-serif; letter-spacing: 0; }
    .ann-hero p {
        max-width: 620px;
        color: rgba(255, 255, 255, .68);
        font-size: 16px;
        line-height: 1.75;
    }

    .ann-list-section {
        padding: 72px 0 105px;
        background: var(--paper);
    }
    .ann-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 34px;
        padding-bottom: 22px;
        border-bottom: 1px solid var(--line);
    }
    .ann-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .ann-filter {
        display: inline-flex;
        align-items: center;
        padding: 9px 16px;
        border: 1px solid var(--line);
        background: var(--white);
        color: var(--ink);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        transition: .2s;
    }
    .ann-filter:hover {
        border-color: var(--ink);
        background: var(--ink);
        color: var(--white);
    }
    .ann-filter.is-active {
        border-color: var(--ink);
        background: var(--ink);
        color: var(--sun);
    }
    .ann-count {
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .ann-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 26px;
    }
    .ann-card {
        display: flex;
        flex-direction: column;
        min-height: 290px;
        padding: 30px 28px;
        background: var(--white);
        border: 1px solid var(--line);
        border-top: 3px solid transparent;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }
    .ann-card:hover {
        transform: translateY(-5px);
        border-color: var(--line);
        border-top-color: var(--sun);
        box-shadow: 0 20px 45px rgba(7, 19, 33, .09);
    }
    .ann-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }
    .ann-type {
        color: var(--ocean);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
    }
    .ann-card-top time {
        color: var(--muted);
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }
    .ann-card h2 {
        font-family: "Manrope", sans-serif;
        font-size: 23px;
        line-height: 1.28;
        letter-spacing: -.025em;
        margin-bottom: 14px;
    }
    html[dir="rtl"] .ann-card h2 { font-family: "Cairo", sans-serif; letter-spacing: 0; }
    .ann-card h2 a { transition: color .2s; }
    .ann-card h2 a:hover { color: var(--ocean); }
    .ann-excerpt {
        flex: 1;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 24px;
    }
    .ann-read {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        color: var(--ink);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .15em;
        text-transform: uppercase;
        transition: .2s;
    }
    .ann-read:hover {
        color: var(--sun);
        gap: 12px;
    }

    .ann-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        margin-top: 46px;
    }
    .ann-page {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        border: 1px solid var(--line);
        background: var(--white);
        color: var(--ink);
        font-size: 12px;
        font-weight: 800;
        transition: .2s;
    }
    a.ann-page:hover {
        border-color: var(--ink);
        background: var(--ink);
        color: var(--sun);
    }
    .ann-page.is-current {
        border-color: var(--ink);
        background: var(--ink);
        color: var(--sun);
    }
    .ann-page.is-disabled {
        opacity: .35;
        cursor: not-allowed;
    }

    .ann-empty {
        padding: 60px 30px;
        border: 1px dashed var(--line);
        background: var(--white);
        text-align: center;
    }
    .ann-empty-mark {
        display: block;
        color: var(--line);
        font-family: "Manrope", sans-serif;
        font-size: 60px;
        font-weight: 800;
        letter-spacing: .12em;
        line-height: 1;
        margin-bottom: 20px;
    }
    .ann-empty h2 {
        font-family: "Manrope", sans-serif;
        font-size: 26px;
        margin-bottom: 10px;
    }
    html[dir="rtl"] .ann-empty h2 { font-family: "Cairo", sans-serif; }
    .ann-empty p {
        max-width: 520px;
        margin: 0 auto;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
    }
    .ann-clear-filter {
        display: inline-block;
        margin-top: 22px;
        padding: 12px 18px;
        background: var(--sun);
        color: var(--ink);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    @media (max-width: 700px) {
        .ann-hero { padding: 80px 0 65px; }
        .ann-list-section { padding: 50px 0 80px; }
        .ann-toolbar { align-items: flex-start; flex-direction: column; }
        .ann-card { min-height: 0; padding: 26px 22px; }
    }
</style>
@endpush
