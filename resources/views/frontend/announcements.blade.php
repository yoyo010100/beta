@extends('layouts.frontend')

@php
    // Locale-aware helper: use the existing ar.json translation when available,
    // otherwise fall back to the English default (no lang file changes required).
    $t = fn (string $key, string $fallback) => \Illuminate\Support\Facades\Lang::has($key) ? (string) __($key) : $fallback;
    $ar = app()->getLocale() === 'ar';
@endphp

@section('title', $t('announcements.seo_title', 'News & Announcements — BETA Industrial'))

@section('meta_description', $t('announcements.sub', 'Latest news, project announcements, and industry insights from BETA Industrial.'))

@section('content')
<!-- Inner Hero -->
<section class="inner-hero relative overflow-hidden" style="background: var(--sky); color: var(--ink); padding: 120px 0 100px;">
    <div class="container gsap-hero">
        <div class="label-tag" style="color: var(--ink)">{{ $t('announcements.label', 'Content Hub') }}</div>
        <h1 class="font-display text-[clamp(50px,6vw,80px)] leading-[1] mb-5">{!! $t('announcements.heading', 'Engineering <br>Insights.') !!}</h1>
    </div>
</section>

<!-- Announcements Grid -->
<section class="section-pad">
    <div class="container">
        <!-- Filter Tags -->
        <div class="filter-tags flex flex-wrap gap-2.5 mb-10 gsap-fade-up">
            <span class="px-4 py-2 text-[11px] font-bold uppercase bg-white border border-line rounded-full transition-all duration-300 hover:bg-ink hover:text-white hover:border-ink cursor-default"
                  data-filter="all">{{ $ar ? 'الكل' : 'All' }}</span>
            <span class="px-4 py-2 text-[11px] font-bold uppercase bg-white border border-line rounded-full transition-all duration-300 hover:bg-ink hover:text-white hover:border-ink cursor-default"
                  data-filter="projects">{{ $ar ? 'المشاريع' : 'Projects' }}</span>
            <span class="px-4 py-2 text-[11px] font-bold uppercase bg-white border border-line rounded-full transition-all duration-300 hover:bg-ink hover:text-white hover:border-ink cursor-default"
                  data-filter="insights">{{ $ar ? 'رؤى' : 'Insights' }}</span>
            <span class="px-4 py-2 text-[11px] font-bold uppercase bg-white border border-line rounded-full transition-all duration-300 hover:bg-ink hover:text-white hover:border-ink cursor-default"
                  data-filter="news">{{ $ar ? 'أخبار' : 'News' }}</span>
            <span class="px-4 py-2 text-[11px] font-bold uppercase bg-white border border-line rounded-full transition-all duration-300 hover:bg-ink hover:text-white hover:border-ink cursor-default"
                  data-filter="safety">{{ $ar ? 'السلامة' : 'Safety' }}</span>
        </div>

        <!-- Announcements Grid -->
        @if($announcements->count() > 0)
            <div class="blog-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10" id="announcements-grid">
                @foreach($announcements as $announcement)
                    <article class="blog-card flex flex-col bg-white border border-line overflow-hidden transition-all duration-400 hover:-translate-y-1.5 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] hover:border-ocean gsap-fade-up"
                             data-category="{{ $announcement->type ?? 'news' }}">
                        <div class="blog-img relative h-48 overflow-hidden">
                            @if($announcement->featured_image)
                                <img src="{{ asset('storage/' . $announcement->featured_image) }}" 
                                     alt="{{ $announcement->getTranslated('title') }}" 
                                     class="w-full h-full object-cover transition-transform duration-800 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-ocean/10 to-sky/20 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-ocean/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A2.25 2.25 0 016 7.5h12a2.25 2.25 0 012.25 2.25v.75M3.75 4.5v.75A2.25 2.25 0 016 7.5h12a2.25 2.25 0 012.25 2.25v.75m-12 0h12a2.25 2.25 0 002.25-2.25v-.75M3.75 4.5h12a2.25 2.25 0 012.25 2.25v.75M3.75 4.5v14.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V4.5z" />
                                    </svg>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[.1em] bg-white/95 backdrop-blur-sm rounded">
                                {{ ucfirst($announcement->type ?? 'news') }}
                            </span>
                        </div>
                        <div class="blog-info p-6 flex flex-col flex-grow">
                            <small class="text-[10px] font-bold text-sun uppercase tracking-[.1em] mb-2">
                                {{ ucfirst($announcement->type ?? 'news') }}
                            </small>
                            <h3 class="font-display text-[22px] text-ink mb-3 leading-snug">
                                <a href="{{ route('announcements.show', $announcement->public_id) }}" class="hover:text-ocean transition-colors">
                                    {{ $announcement->getTranslated('title') }}
                                </a>
                            </h3>
                            <p class="text-[14px] text-muted leading-relaxed mb-4 flex-grow">
                                {{ \Illuminate\Support\Str::limit(strip_tags($announcement->getTranslated('excerpt') ?: $announcement->getTranslated('content')), 120) }}
                            </p>
                            <div class="flex items-center justify-between pt-3 border-t border-line">
                                <time class="text-[11px] text-muted" datetime="{{ $announcement->published_at?->format('c') }}">
                                    {{ $announcement->published_at?->format('M d, Y') ?? '—' }}
                                </time>
                                <a href="{{ route('announcements.show', $announcement->public_id) }}" 
                                   class="text-[11px] font-bold text-ocean uppercase tracking-[.1em] hover:text-ink transition-colors">
                                    {{ $t('announcements.read_more', 'Read Article') }} →
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center gsap-fade-up">
                {{ $announcements->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20 gsap-fade-up">
                <div class="w-20 h-20 mx-auto mb-6 bg-ocean/10 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-ocean" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h2 class="font-display text-[28px] text-ink mb-3">{{ $ar ? 'لا توجد إعلانات بعد' : 'No Announcements Yet' }}</h2>
                <p class="text-muted max-w-md mx-auto">{{ $ar ? 'تابعوا قريباً آخر الأخبار وتحديثات المشاريع والرؤى الهندسية من فريقنا.' : 'Check back soon for the latest news, project updates, and engineering insights from our team.' }}</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('[data-filter]');
        const cards = document.querySelectorAll('#announcements-grid .blog-card');
        
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                filterButtons.forEach(b => b.classList.remove('bg-ink', 'text-white', 'border-ink'));
                filterButtons.forEach(b => b.classList.add('bg-white', 'text-ink', 'border-line'));
                this.classList.remove('bg-white', 'text-ink', 'border-line');
                this.classList.add('bg-ink', 'text-white', 'border-ink');
                
                const filter = this.dataset.filter;
                
                cards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'flex';
                        gsap.fromTo(card, { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: 'power2.out' });
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush