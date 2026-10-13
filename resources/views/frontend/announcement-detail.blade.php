@extends('layouts.frontend')

@php
    // Locale-aware helper: use the existing ar.json translation when available,
    // otherwise fall back to the English default (no lang file changes required).
    $t = fn (string $key, string $fallback) => \Illuminate\Support\Facades\Lang::has($key) ? (string) __($key) : $fallback;

    // title/excerpt/content are JSON columns — always resolve them per locale.
    $articleTitle = $announcement->getTranslated('title');
    $articleExcerpt = $announcement->getTranslated('excerpt')
        ?: \Illuminate\Support\Str::limit(strip_tags($announcement->getTranslated('content')), 160);
@endphp

@section('title', $articleTitle . ' — ' . ($announcement->type === 'insight' ? 'Insights' : 'News') . ' — BETA Industrial')

@section('meta_description', $articleExcerpt)

@section('content')
<!-- Article Header -->
<section class="article-header pt-20 pb-16 px-4 text-center gsap-fade-up">
    <div class="container max-w-4xl">
        <div class="label-tag-ocean mb-5">{{ ucfirst($announcement->type ?? 'news') }}</div>
        <h1 class="font-display text-[clamp(35px,5vw,55px)] leading-[1.1] text-ink mb-5">{{ $articleTitle }}</h1>
        <div class="flex items-center justify-center gap-4 text-muted text-sm">
            <time datetime="{{ $announcement->published_at?->format('c') }}">
                {{ $announcement->published_at?->format('F d, Y') }}
            </time>
        </div>
    </div>
</section>

<!-- Featured Image -->
@if($announcement->featured_image)
    <div class="article-img max-w-[1000px] mx-auto px-4 mb-16 gsap-fade-up">
        <img src="{{ asset('storage/' . $announcement->featured_image) }}" 
             alt="{{ $articleTitle }}" 
             class="w-full h-[450px] object-cover rounded-none">
    </div>
@endif

<!-- Article Body -->
<article class="article-body max-w-[760px] mx-auto px-4 pb-24 gsap-fade-up">
    <div class="prose prose-ink max-w-none">
        {!! $announcement->getTranslated('content') !!}
    </div>

    <!-- Back to List -->
    <div class="mt-16 pt-8 border-t border-line">
        <a href="{{ route('announcements') }}" class="inline-flex items-center gap-2 text-ocean font-bold text-sm uppercase tracking-[.1em] hover:text-ink transition-colors">
            ← {{ $t('announcements.back_to_list', 'Back to News & Insights') }}
        </a>
    </div>
</article>
@endsection

@push('styles')
<style>
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
    .prose p {
        margin-bottom: 24px;
    }
    .prose blockquote {
        border-inline-start: 4px solid var(--sun);
        padding-inline-start: 24px;
        font-style: italic;
        font-size: 20px;
        margin: 40px 0;
        color: var(--ink);
        background: var(--paper);
        padding: 24px;
        border-radius: 0 8px 8px 0;
    }
    .prose ul, .prose ol {
        margin: 24px 0;
        padding-inline-start: 24px;
    }
    .prose li {
        margin-bottom: 10px;
    }
    .prose img {
        border-radius: 8px;
        margin: 24px 0;
    }
    .prose a {
        color: var(--ocean);
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .prose a:hover {
        color: var(--ink);
    }
    .prose strong {
        color: var(--ink);
    }
    .prose code {
        background: var(--paper);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
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
</style>
@endpush