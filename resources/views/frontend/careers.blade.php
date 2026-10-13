@extends('layouts.frontend')

@php
    // Locale-aware helper: use the existing ar.json translation when available,
    // otherwise fall back to the English default (no lang file changes required).
    $t = fn (string $key, string $fallback) => \Illuminate\Support\Facades\Lang::has($key) ? (string) __($key) : $fallback;
    $ar = app()->getLocale() === 'ar';
@endphp

@section('title', $t('careers.seo_title', 'Careers — BETA Industrial'))

@section('meta_description', $t('careers.sub', 'Join the BETA Industrial team — explore our open positions and apply online.'))

@section('content')
<!-- Inner Hero (matches about/services inner-hero pattern) -->
<section class="relative overflow-hidden" style="background: var(--ocean); color: var(--white); padding: 120px 0 100px;">
    <div class="container gsap-hero">
        <div class="label-tag">{{ $t('careers.label', 'Careers') }}</div>
        <h1 class="font-display text-[clamp(50px,6vw,80px)] leading-[1] mb-5">{!! $t('careers.heading', 'Join <br>Our Team') !!}</h1>
        <p class="text-white/85 max-w-[620px] text-[15px] leading-relaxed">{{ $t('careers.sub', 'We look for exceptional talent to build the future of steel — across Egypt and Saudi Arabia.') }}</p>
    </div>
</section>

<!-- 01 / Open Positions -->
<section class="section-pad" style="background: var(--white);">
    <div class="container">
        <div class="label-tag label-tag-ocean">{{ $ar ? '01 / الوظائف الشاغرة' : '01 / Open Positions' }}</div>
        <h2 class="font-display text-[36px] text-ink mb-3">{{ $ar ? 'الوظائف الحالية' : 'Current Vacancies' }}</h2>
        <p class="text-muted text-[13px] leading-relaxed max-w-[520px] mb-10">
            {{ $ar ? 'استعرض وظائفنا الشاغرة في مصر والمملكة العربية السعودية. يتم مراجعة كل طلب مباشرة من فريق الموارد البشرية.' : 'Explore our active openings across Egypt and Saudi Arabia. Every application is reviewed directly by our HR team.' }}
        </p>

        @if($jobs->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[30px]">
                @foreach($jobs as $job)
                    @php
                        $jobTitle = $job->getTranslated('title');
                        $jobDescription = trim(strip_tags($job->getTranslated('description')));
                    @endphp
                    <article class="job-card gsap-fade-up">
                        <div class="flex items-start justify-between gap-3 mb-4 min-h-[16px]">
                            @if($job->country)
                                <span class="inline-block text-[10px] font-extrabold uppercase tracking-[.18em] text-ocean">{{ $job->country->name }}</span>
                            @endif
                            @if($job->closes_at)
                                <span class="text-[10px] font-bold uppercase tracking-[.1em] text-muted">
                                    Closes {{ $job->closes_at->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-display text-[22px] text-ink mb-3 leading-snug">{{ $jobTitle }}</h3>
                        <p class="text-[14px] text-muted leading-relaxed mb-6 flex-grow">{{ \Illuminate\Support\Str::limit($jobDescription, 130) }}</p>
                        <a href="#apply"
                           class="job-apply inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[.14em] text-ocean hover:text-ink transition-colors"
                           data-job-id="{{ $job->id }}">
                            {{ $t('careers.apply_now', 'Apply Now') }} →
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <!-- Empty state when there are no active vacancies -->
            <div class="bg-paper border border-line p-[50px] text-center max-w-[720px] mx-auto gsap-fade-up">
                <h3 class="font-display text-[24px] text-ink mb-3">{{ $t('careers.no_openings', 'No open positions at the moment.') }}</h3>
                <p class="text-muted text-[14px] leading-relaxed mb-7">
                    {{ $ar ? 'لا توجد وظائف شاغرة حالياً — لكننا نرحب دائماً بالطلبات الاختيارية عبر صفحة التواصل.' : 'There are no active vacancies right now — but we always welcome spontaneous applications through our contact page.' }}
                </p>
                <a href="{{ route('contact') }}" class="btn-primary">{{ __('app.nav.contact') }}</a>
            </div>
        @endif
    </div>
</section>

<!-- 02 / Application Form (only when there is at least one posting to apply for) -->
@if($jobs->count())
<section class="section-pad" id="apply">
    <div class="container">
        <div class="label-tag label-tag-ocean">{{ $ar ? '02 / التقديم' : '02 / Application' }}</div>
        <h2 class="font-display text-[36px] text-ink mb-3">{{ $t('careers.form_heading', 'Job Application') }}</h2>
        <p class="text-muted text-[13px] leading-relaxed max-w-[520px] mb-10">
            {{ $ar ? 'أكمل النموذج أدناه وأرفق سيرتك الذاتية. يتم التواصل مع المرشحين المؤهلين خلال أسبوع.' : 'Complete the form below and attach your CV. Qualified candidates are contacted within one week.' }}
        </p>

        <div class="form-box">
            <form action="{{ route('application.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Anti-spam: honeypot (hidden) + math captcha -->
                <div class="hp-field" aria-hidden="true">
                    <label for="apply-hp-check">Do not fill this field</label>
                    <input type="text" id="apply-hp-check" name="hp_check" tabindex="-1" autocomplete="off">
                </div>
                <div class="hp-field" aria-hidden="true">
                    <label for="apply-website-url">Do not fill this field</label>
                    <input type="text" id="apply-website-url" name="website_url_hp" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" id="apply-hp-time" name="hp_time" value="">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="apply-name">{{ __('app.contact.field_name') }} *</label>
                        <input type="text" id="apply-name" name="applicant_name" value="{{ old('applicant_name') }}" required maxlength="160">
                    </div>
                    <div class="form-group">
                        <label for="apply-email">{{ __('app.contact.field_email') }} *</label>
                        <input type="email" id="apply-email" name="email" value="{{ old('email') }}" required maxlength="254">
                    </div>
                    <div class="form-group">
                        <label for="apply-phone">{{ __('app.contact.field_phone') }} *</label>
                        <input type="tel" id="apply-phone" name="phone" value="{{ old('phone') }}" required maxlength="40">
                    </div>
                    <div class="form-group">
                        <label for="job_posting_id">{{ $t('careers.field_job', 'Position') }} *</label>
                        <select id="job_posting_id" name="job_posting_id" required>
                            <option value="">{{ $t('careers.select_job', 'Select Position') }}</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" @selected(old('job_posting_id') == $job->id)>
                                    {{ $job->getTranslated('title') }} ({{ $job->country?->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group full">
                        <label for="apply-cv">{{ $t('careers.field_cv', 'Upload CV / Resume') }} *</label>
                        <input type="file" id="apply-cv" name="cv" accept=".pdf,application/pdf" required>
                        {{-- Validation only accepts PDF up to 10 MB (JobApplicationRequest) — ar.json's careers.upload_cv_label is outdated, so use accurate text. --}}
                        <small class="text-[11px] text-muted">{{ $ar ? 'ملف PDF فقط — حد أقصى 10 ميجابايت' : 'PDF only — max 10 MB' }}</small>
                    </div>
                    <div class="form-group full">
                        <label for="apply-captcha">{{ $ar ? 'التحقق الأمني *' : 'Security Check *' }}</label>
                        <div class="captcha-row">
                            <span class="captcha-question" aria-hidden="true">
                                {{ session('math_captcha_a', '?') }} + {{ session('math_captcha_b', '?') }} =
                            </span>
                            <input type="number" id="apply-captcha" name="captcha_answer" required autocomplete="off" inputmode="numeric" aria-label="Captcha answer" placeholder="{{ __('app.contact.captcha_placeholder') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">{{ $t('careers.submit_button', 'Submit Application') }}</button>
            </form>
        </div>
    </div>
</section>
@endif

<!-- Stats band (matches about page stats-banner) -->
<section class="gsap-fade-up" style="background: var(--ink); color: var(--white); padding: 60px 0;">
    <div class="container grid grid-cols-2 md:grid-cols-4 gap-[30px] text-center">
        <div>
            <h4 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">16</h4>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'سنوات الخبرة' : 'Years of Experience' }}</p>
        </div>
        <div>
            <h4 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">850+</h4>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'مشروع منجز' : 'Projects Completed' }}</p>
        </div>
        <div>
            <h4 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">50k</h4>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'طن تم تصنيعه' : 'Tons Fabricated' }}</p>
        </div>
        <div>
            <h4 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">12</h4>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'قطاعات نخدمها' : 'Industries Served' }}</p>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Job cards — matches the project card system (q-card / custom-card) */
    .job-card {
        background: var(--white);
        border: 1px solid var(--line);
        padding: 35px;
        display: flex;
        flex-direction: column;
        transition: 0.3s;
    }
    .job-card:hover {
        border-color: var(--sun);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    /* Application form — mirrors the contact page form system */
    .form-box { background: var(--white); padding: 50px; border: 1px solid var(--line); max-width: 900px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .form-group input,
    .form-group select { padding: 15px; border: 1px solid var(--line); background: var(--paper); font-family: inherit; font-size: 14px; color: var(--ink); transition: border-color 0.2s; }
    .form-group input:focus,
    .form-group select:focus { outline: none; border-color: var(--ocean); }
    .captcha-row { display: flex; align-items: center; gap: 12px; }
    .captcha-row .captcha-question { font-weight: 800; letter-spacing: .04em; background: var(--paper); border: 1px solid var(--line); padding: 15px 18px; white-space: nowrap; }
    .captcha-row input { flex: 1; padding: 15px; border: 1px solid var(--line); background: var(--paper); font-family: inherit; font-size: 14px; }
    .captcha-row input:focus { outline: none; border-color: var(--ocean); }
    /* Honeypot: invisible to humans, present for bots */
    .hp-field { position: absolute !important; left: -9999px !important; top: -9999px !important; width: 1px !important; height: 1px !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important; }
    .submit-btn { background: var(--ink); color: var(--sun); padding: 18px; width: 100%; border: none; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; font-family: inherit; font-size: 12px; cursor: pointer; transition: 0.3s; }
    .submit-btn:hover { background: var(--ocean); color: var(--white); }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-box { padding: 30px 20px; }
        .captcha-row { flex-direction: column; align-items: stretch; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Timestamp trap: filled at page load, must be at least 3 seconds old on submit.
        var hpTime = document.getElementById('apply-hp-time');
        if (hpTime) hpTime.value = Date.now();

        // "Apply Now" on a job card preselects that position in the form.
        document.querySelectorAll('.job-apply').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var select = document.getElementById('job_posting_id');
                if (select) select.value = btn.dataset.jobId;
            });
        });
    });
</script>
@endpush
