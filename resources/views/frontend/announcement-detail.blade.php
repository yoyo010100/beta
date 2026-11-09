@extends('layouts.frontend')

@php
    $ar = app()->getLocale() === 'ar';

    // title/excerpt/content are JSON columns — always resolve them per locale.
    $articleTitle = $announcement->getTranslated('title');
    $articleLead = trim(strip_tags($announcement->getTranslated('excerpt')));
    $articleExcerpt = $articleLead !== ''
        ? $articleLead
        : \Illuminate\Support\Str::limit(strip_tags($announcement->getTranslated('content')), 180);

    $typeLabels = [
        'news' => $ar ? 'أخبار' : 'News',
        'press_release' => $ar ? 'بيانات صحفية' : 'Press Release',
        'event' => $ar ? 'فعاليات' : 'Event',
    ];
    $typeLabel = $typeLabels[$announcement->type]
        ?? ucfirst(str_replace('_', ' ', $announcement->type ?? 'news'));
@endphp

@section('title', $articleTitle . ' — BETA Industrial')
@section('meta_description', $articleExcerpt)

@section('content')
<header class="ann-article-header">
    <div class="container">
        <div class="ann-article-header-inner">
            <a href="{{ route('announcements.index', ['type' => $announcement->type]) }}" class="ann-article-type">
                {{ $typeLabel }}
            </a>
            <h1>{{ $articleTitle }}</h1>
            <div class="ann-article-meta">
                <time datetime="{{ $announcement->published_at?->format('c') }}">
                    {{ $announcement->published_at?->format('F d, Y') }}
                </time>
                <span aria-hidden="true">/</span>
                <span>BETA Industrial</span>
            </div>
        </div>
    </div>
</header>

<article class="ann-article">
    <div class="container">
        <div class="ann-article-container">
            @if($articleLead !== '')
                <p class="ann-article-lead">{{ $articleLead }}</p>
            @endif

            <div class="prose">
                {!! $announcement->getTranslated('content') !!}
            </div>

            <div class="ann-article-footer">
                <a href="{{ route('announcements.index') }}" class="ann-back-link">
                    <span aria-hidden="true">←</span>
                    {{ $ar ? 'العودة إلى الأخبار والإعلانات' : 'Back to News & Announcements' }}
                </a>
            </div>
        </div>
    </div>
</article>
@endsection

@push('styles')
<style>
    .ann-article-header {
        padding: 90px 0 60px;
        background:
            linear-gradient(135deg, rgba(7, 19, 33, .06), transparent 60%),
            var(--white);
        border-bottom: 1px solid var(--line);
    }
    .ann-article-header-inner {
        max-width: 900px;
    }
    .ann-article-type {
        display: inline-block;
        margin-bottom: 22px;
        color: var(--ocean);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .18em;
        text-transform: uppercase;
        border-bottom: 2px solid var(--sun);
        padding-bottom: 6px;
    }
    .ann-article-header h1 {
        font-family: "Manrope", sans-serif;
        font-size: clamp(34px, 5.2vw, 60px);
        line-height: 1.08;
        letter-spacing: -.035em;
        color: var(--ink);
        margin-bottom: 24px;
    }
    html[dir="rtl"] .ann-article-header h1 { font-family: "Cairo", sans-serif; letter-spacing: 0; }
    .ann-article-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .ann-article-meta span[aria-hidden] { color: var(--sun); }

    .ann-article {
        padding: 64px 0 110px;
        background: var(--paper);
    }
    .ann-article-container {
        max-width: 820px;
        margin-inline: auto;
    }
    .ann-article-lead {
        margin-bottom: 42px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--line);
        color: var(--ink);
        font-size: 20px;
        line-height: 1.65;
    }
    .ann-article-footer {
        margin-top: 64px;
        padding-top: 30px;
        border-top: 1px solid var(--line);
    }
    .ann-back-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--ocean);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .12em;
        text-transform: uppercase;
        transition: .2s;
    }
    .ann-back-link:hover {
        color: var(--ink);
        gap: 14px;
    }

    /* Prose styling for article content */
    .prose {
        font-size: 17px;
        line-height: 1.85;
        color: #333;
    }
    .prose h2 {
        font-family: "Manrope", sans-serif;
        font-size: 28px;
        color: var(--ink);
        margin: 48px 0 20px;
        line-height: 1.2;
    }
    .prose h3 {
        font-family: "Manrope", sans-serif;
        font-size: 22px;
        color: var(--ink);
        margin: 40px 0 16px;
        line-height: 1.3;
    }
    .prose p { margin-bottom: 24px; }
    .prose blockquote {
        border-inline-start: 4px solid var(--sun);
        padding: 24px;
        font-style: italic;
        font-size: 20px;
        margin: 40px 0;
        color: var(--ink);
        background: var(--white);
        border-radius: 0 8px 8px 0;
    }
    .prose ul, .prose ol {
        margin: 24px 0;
        padding-inline-start: 24px;
    }
    .prose li { margin-bottom: 10px; }
    .prose img { border-radius: 8px; margin: 24px 0; }
    .prose a {
        color: var(--ocean);
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .prose a:hover { color: var(--ink); }
    .prose strong { color: var(--ink); }
    .prose code {
        background: var(--white);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: .9em;
    }
    .prose pre {
        background: var(--ink);
        color: var(--paper);
        padding: 20px;
        border-radius: 8px;
        overflow-x: auto;
        margin: 24px 0;
    }
    .prose pre code {
        background: transparent;
        padding: 0;
        color: inherit;
    }

    @media (max-width: 700px) {
        .ann-article-header { padding: 70px 0 45px; }
        .ann-article { padding: 48px 0 80px; }
        .ann-article-lead { font-size: 17px; }
        .prose { font-size: 16px; }
    }
</style>
@endpush
