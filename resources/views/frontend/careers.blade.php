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
<section class="section-pad" style="background: var(--paper); border-bottom: 1px solid var(--line);">
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
                                <span class="job-chip">{{ $job->country->name }}</span>
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
                           class="job-apply inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[.14em] text-ocean hover:text-ink transition-colors rounded-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ocean"
                           data-job-id="{{ $job->id }}">
                            {{ $t('careers.apply_now', 'Apply Now') }} →
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <!-- Empty state when there are no active vacancies -->
            <div class="bg-white border border-line p-[50px] text-center max-w-[720px] mx-auto gsap-fade-up">
                <h3 class="font-display text-[24px] text-ink mb-3">{{ $t('careers.no_openings', 'No open positions at the moment.') }}</h3>
                <p class="text-muted text-[14px] leading-relaxed mb-7">
                    {{ $ar ? 'لا توجد وظائف شاغرة حالياً — لكننا نرحب دائماً بالطلبات الاختيارية عبر صفحة التواصل.' : 'There are no active vacancies right now — but we always welcome spontaneous applications through our contact page.' }}
                </p>
                <a href="{{ route('contact') }}" class="btn-primary">{{ __('app.nav.contact') }}</a>
            </div>
        @endif
    </div>
</section>

<!-- 02 / Application — dark band, light card (strong dark/light contrast) -->
<section class="section-pad apply-section" id="apply">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">

            {{-- Left rail: section intro + what happens next --}}
            <div class="lg:col-span-4">
                <div class="label-tag">{{ $ar ? '02 / التقديم' : '02 / Application' }}</div>
                <h2 class="font-display text-[clamp(28px,3vw,38px)] text-white leading-[1.15] mb-4">{{ $t('careers.form_heading', 'Job Application') }}</h2>
                <p class="text-white/65 text-[14px] leading-relaxed mb-9 max-w-[420px]">
                    {{ $ar ? 'أكمل النموذج أدناه وأرفق سيرتك الذاتية. يتم التواصل مع المرشحين المؤهلين خلال أسبوع.' : 'Complete the form below and attach your CV. Qualified candidates are contacted within one week.' }}
                </p>

                <ol class="apply-steps">
                    <li class="apply-step">
                        <span class="apply-step-num">1</span>
                        <span>
                            <strong class="apply-step-title">{{ $ar ? 'أرسلت الطلب' : 'You apply' }}</strong>
                            <span class="apply-step-text">{{ $ar ? 'يصل طلبك مباشرة إلى فريق التوظيف مع سيرتك الذاتية.' : 'Your application lands directly with our hiring team, CV attached.' }}</span>
                        </span>
                    </li>
                    <li class="apply-step">
                        <span class="apply-step-num">2</span>
                        <span>
                            <strong class="apply-step-title">{{ $ar ? 'نراجع الطلب' : 'We review' }}</strong>
                            <span class="apply-step-text">{{ $ar ? 'يتم فرز الطلبات بواسطة الموارد البشرية خلال أسبوع.' : 'HR screens every application — typically within one week.' }}</span>
                        </span>
                    </li>
                    <li class="apply-step">
                        <span class="apply-step-num">3</span>
                        <span>
                            <strong class="apply-step-title">{{ $ar ? 'نتواصل معك' : 'We reach out' }}</strong>
                            <span class="apply-step-text">{{ $ar ? 'المرشحون المؤهلون يتلقون دعوة لمقابلة أو زيارة فنية.' : 'Shortlisted candidates are invited to an interview or technical visit.' }}</span>
                        </span>
                    </li>
                </ol>

                <div class="apply-mail">
                    <span class="apply-mail-label">{{ $ar ? 'تفضّل البريد؟' : 'Prefer email?' }}</span>
                    <a href="mailto:careers@beta-industrial.com" class="apply-mail-link">careers@beta-industrial.com</a>
                </div>
            </div>

            {{-- Right: light form card --}}
            <div class="lg:col-span-8">
                <div class="apply-card">
                    @if($errors->any())
                        <div class="fc-alert fc-alert-error" role="alert">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="fc-alert-icon" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9zm1-4a1.1 1.1 0 100 2.2A1.1 1.1 0 0010 5z" clip-rule="evenodd"/></svg>
                            <ul class="list-disc ps-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('application.submit') }}" method="POST" enctype="multipart/form-data" id="careerForm">
                        @csrf
                        <!-- Anti-spam: honeypot (hidden) -->
                        <div class="hp-field" aria-hidden="true">
                            <label for="apply-hp-check">Do not fill this field</label>
                            <input type="text" id="apply-hp-check" name="hp_check" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="hp-field" aria-hidden="true">
                            <label for="apply-website-url">Do not fill this field</label>
                            <input type="text" id="apply-website-url" name="website_url_hp" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" id="apply-hp-time" name="hp_time" value="">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-5">
                            <div class="fc-group">
                                <label class="fc-label" for="apply-name">{{ __('app.contact.field_name') }} <span class="fc-req" aria-hidden="true">*</span></label>
                                <input class="fc-control @error('applicant_name') fc-control-invalid @enderror" type="text" id="apply-name" name="applicant_name" value="{{ old('applicant_name') }}" required maxlength="160" autocomplete="name" placeholder="{{ $ar ? 'الاسم الكامل' : 'Full name' }}">
                                @error('applicant_name') <p class="fc-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="fc-group">
                                <label class="fc-label" for="apply-email">{{ __('app.contact.field_email') }} <span class="fc-req" aria-hidden="true">*</span></label>
                                <input class="fc-control @error('email') fc-control-invalid @enderror" type="email" id="apply-email" name="email" value="{{ old('email') }}" required maxlength="254" autocomplete="email" placeholder="name@company.com">
                                @error('email') <p class="fc-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="fc-group">
                                <label class="fc-label" for="apply-phone">{{ __('app.contact.field_phone') }} <span class="fc-req" aria-hidden="true">*</span></label>
                                <input class="fc-control @error('phone') fc-control-invalid @enderror" type="tel" id="apply-phone" name="phone" value="{{ old('phone') }}" required maxlength="40" autocomplete="tel" placeholder="+20 100 000 0000">
                                @error('phone') <p class="fc-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="fc-group">
                                <label class="fc-label" for="job_posting_id">{{ $t('careers.field_job', 'Position') }}</label>
                                <div class="fc-select-wrap">
                                    <select class="fc-control fc-select @error('job_posting_id') fc-control-invalid @enderror" id="job_posting_id" name="job_posting_id">
                                        <option value="">{{ $t('careers.select_job', 'Select Position / General Application') }}</option>
                                        @foreach($jobs as $job)
                                            <option value="{{ $job->id }}" @selected(old('job_posting_id') == $job->id)>
                                                {{ $job->getTranslated('title') }} ({{ $job->country?->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="fc-select-caret" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                        <path d="M5 7.5l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                @error('job_posting_id') <p class="fc-error">{{ $message }}</p> @enderror
                            </div>

                            {{-- Custom file-upload card (replaces the raw browser file input) --}}
                            <div class="fc-group md:col-span-2">
                                <label class="fc-label" id="apply-cv-label" for="apply-cv">{{ $ar ? 'رفع السيرة الذاتية (PDF, DOC, DOCX)' : 'Upload CV / Résumé (PDF, DOC, DOCX)' }} <span class="fc-req" aria-hidden="true">*</span></label>

                                <div class="cv-dropzone" id="cvDropzone" aria-describedby="cvHelpText">
                                    <input type="file" id="apply-cv" name="cv" class="sr-only" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" aria-describedby="cvHelpText">

                                    {{-- Idle state --}}
                                    <div class="cv-idle" id="cvIdleState">
                                        <span class="cv-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" width="30" height="30">
                                                <path d="M12 16V4m0 0L8 8m4-4l4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M4 15v3a2 2 0 002 2h12a2 2 0 002-2v-3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <p class="cv-title">{{ $ar ? 'اسحب وأفلت سيرتك الذاتية هنا' : 'Drag & drop your CV here' }}</p>
                                        <p class="cv-or">{{ $ar ? 'أو' : 'or' }}</p>
                                        <span class="cv-browse">{{ $ar ? 'تصفح الملفات' : 'Browse files' }}</span>
                                        <p class="cv-hint" id="cvHelpText">{{ $ar ? 'ملفات PDF أو DOC أو DOCX — حد أقصى 10 ميجابايت' : 'PDF, DOC, or DOCX only — max 10 MB' }}</p>
                                    </div>

                                    {{-- Selected-file state --}}
                                    <div class="cv-selected" id="cvSelectedState" hidden>
                                        <span class="cv-file-badge" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                                                <path d="M7 3h7l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                                <path d="M14 3v5h5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <span class="cv-file-meta">
                                            <span class="cv-file-name" id="cvFileName">file.pdf</span>
                                            <span class="cv-file-size" id="cvFileSize">0 KB</span>
                                        </span>
                                        <button type="button" class="cv-remove" id="cvRemoveBtn" aria-label="{{ $ar ? 'إزالة الملف' : 'Remove file' }}">
                                            <svg viewBox="0 0 20 20" fill="none" width="16" height="16" aria-hidden="true"><path d="M6 6l8 8M14 6l-8 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="fc-error" id="cvClientError" role="alert" hidden></p>
                                <p class="sr-only" id="cvLiveRegion" aria-live="polite"></p>
                                @error('cv') <p class="fc-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <button type="submit" class="submit-btn" id="careerSubmitBtn">{{ $t('careers.submit_button', 'Submit Application') }}</button>
                                <p class="fc-fineprint">{{ $ar ? 'بإرسال هذا النموذج فإنك توافق على معالجة بياناتك لأغراض التوظيف.' : 'By submitting, you agree to your data being processed for recruitment purposes.' }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats band (matches about page stats-banner) -->
<section class="gsap-fade-up" style="background: var(--ink); color: var(--white); padding: 60px 0; border-top: 3px solid var(--sun);">
    <div class="container grid grid-cols-2 md:grid-cols-4 gap-[30px] text-center">
        <div>
            <h3 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">16</h3>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'سنوات الخبرة' : 'Years of Experience' }}</p>
        </div>
        <div>
            <h3 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">850+</h3>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'مشروع منجز' : 'Projects Completed' }}</p>
        </div>
        <div>
            <h3 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">50k</h3>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'طن تم تصنيعه' : 'Tons Fabricated' }}</p>
        </div>
        <div>
            <h3 class="font-display text-[48px] leading-none mb-2.5" style="color: var(--sun)">12</h3>
            <p class="text-[11px] uppercase tracking-[.1em] text-[#8e9ba7]">{{ $ar ? 'قطاعات نخدمها' : 'Industries Served' }}</p>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ── Vacancy cards — white cards on paper, sun hover ─────────────────── */
    .job-card {
        background: var(--white);
        border: 1px solid var(--line);
        padding: 35px;
        display: flex;
        flex-direction: column;
        transition: border-color .3s, transform .3s, box-shadow .3s;
    }
    .job-card:hover {
        border-color: var(--sun);
        transform: translateY(-5px);
        box-shadow: 0 12px 34px rgba(7, 19, 33, .08);
    }
    .job-chip {
        display: inline-block;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .18em;
        color: var(--ocean);
        border: 1px solid rgba(0, 48, 135, .25);
        background: rgba(0, 48, 135, .06);
        padding: 4px 10px;
    }

    /* ── Application section — dark ink band ─────────────────────────────── */
    .apply-section {
        background:
            radial-gradient(1100px 520px at 85% -10%, rgba(242, 169, 0, .10), transparent 60%),
            linear-gradient(160deg, var(--ink) 0%, var(--midnight) 100%);
        border-bottom: 1px solid rgba(255, 255, 255, .08);
    }
    .apply-steps { display: flex; flex-direction: column; gap: 18px; }
    .apply-step { display: flex; gap: 14px; align-items: flex-start; }
    .apply-step-num {
        flex: 0 0 auto;
        width: 30px; height: 30px;
        display: inline-flex; align-items: center; justify-content: center;
        font-family: "Manrope", sans-serif; font-weight: 800; font-size: 13px;
        color: var(--ink); background: var(--sun);
    }
    .apply-step-title { display: block; color: #fff; font-size: 13px; font-weight: 800; letter-spacing: .02em; }
    .apply-step-text { display: block; color: rgba(255, 255, 255, .55); font-size: 12.5px; line-height: 1.6; margin-top: 2px; }
    .apply-mail { margin-top: 34px; padding-top: 22px; border-top: 1px solid rgba(255, 255, 255, .14); }
    .apply-mail-label { display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: rgba(255, 255, 255, .45); margin-bottom: 6px; }
    .apply-mail-link { color: var(--sun); font-size: 14px; font-weight: 700; transition: color .2s; }
    .apply-mail-link:hover { color: #fff; }

    /* Light form card floating on the dark band */
    .apply-card {
        background: var(--white);
        border: 1px solid rgba(255, 255, 255, .55);
        box-shadow: 0 30px 60px rgba(0, 0, 0, .35);
        padding: 40px;
    }

    /* ── Form controls ─────────────────────────────────────────────────── */
    .fc-group { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
    .fc-label {
        font-size: 10.5px; font-weight: 800;
        text-transform: uppercase; letter-spacing: .14em;
        color: var(--ink); opacity: .82;
    }
    .fc-req { color: #c53030; }
    .fc-control {
        width: 100%;
        background: var(--paper);
        border: 1px solid var(--line);
        padding: 13px 15px;
        font: inherit; font-size: 14px; color: var(--ink);
        border-radius: 6px;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .fc-control::placeholder { color: #9aa3ab; }
    .fc-control:hover { border-color: rgba(7, 19, 33, .35); }
    .fc-control:focus {
        outline: none;
        background: #fff;
        border-color: var(--ocean);
        box-shadow: 0 0 0 3px rgba(0, 48, 135, .14);
    }
    .fc-control-invalid { border-color: #e53e3e; background: #fff5f5; }
    .fc-error { font-size: 12px; color: #c53030; font-weight: 600; }
    .fc-fineprint { margin-top: 10px; font-size: 11px; color: var(--muted); text-align: center; }

    /* Custom select — caret replaces the native arrow */
    .fc-select-wrap { position: relative; }
    .fc-select {
        appearance: none;
        -webkit-appearance: none;
        padding-inline-end: 42px;
        cursor: pointer;
    }
    .fc-select-caret {
        position: absolute;
        inset-inline-end: 14px;
        top: 50%;
        width: 18px; height: 18px;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    /* Alerts */
    .fc-alert {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 13px 16px; margin-bottom: 18px;
        border-radius: 6px; font-size: 13px; line-height: 1.5;
        border: 1px solid transparent;
    }
    .fc-alert-icon { width: 19px; height: 19px; flex: 0 0 auto; margin-top: 1px; }
    .fc-alert-error { background: #fff5f5; border-color: #feb2b2; color: #742a2a; }

    /* ── CV dropzone card ─────────────────────────────────────────────── */
    .cv-dropzone {
        position: relative;
        border: 1.5px dashed rgba(0, 48, 135, .35);
        background: linear-gradient(180deg, #fbfaf6, var(--paper));
        border-radius: 10px;
        padding: 26px 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .cv-dropzone:hover { border-color: var(--ocean); background: #f6f8fd; }
    .cv-dropzone:focus-within { border-color: var(--ocean); box-shadow: 0 0 0 3px rgba(0, 48, 135, .18); }
    .cv-dropzone.is-dragover {
        border-color: var(--ocean);
        border-style: solid;
        background: #eef4ff;
        box-shadow: 0 0 0 3px rgba(0, 48, 135, .15);
    }
    .cv-dropzone.has-file { border-style: solid; border-color: rgba(56, 161, 105, .55); background: #f6fefa; }
    .cv-dropzone.has-error { border-style: solid; border-color: #e53e3e; background: #fff5f5; }

    .cv-idle { display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .cv-icon {
        width: 58px; height: 58px; border-radius: 12px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(0, 48, 135, .08); color: var(--ocean);
        margin-bottom: 2px;
    }
    .cv-title { font-family: "Manrope", sans-serif; font-weight: 800; font-size: 14.5px; color: var(--ink); }
    .cv-or { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .18em; color: var(--muted); }
    .cv-browse {
        display: inline-block;
        background: var(--ink); color: var(--sun);
        font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em;
        padding: 10px 22px; border-radius: 4px;
        transition: background .2s, color .2s;
    }
    .cv-dropzone:hover .cv-browse { background: var(--ocean); color: #fff; }
    .cv-hint { margin-top: 4px; font-size: 11px; color: var(--muted); }

    .cv-selected { display: flex; align-items: center; gap: 14px; text-align: start; }
    .cv-file-badge {
        flex: 0 0 auto; width: 46px; height: 46px; border-radius: 8px;
        background: rgba(56, 161, 105, .12); color: #2f855a;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .cv-file-meta { flex: 1 1 auto; min-width: 0; }
    .cv-file-name { display: block; font-size: 13.5px; font-weight: 800; color: var(--ink); word-break: break-all; }
    .cv-file-size { display: block; margin-top: 2px; font-size: 11px; color: var(--muted); }
    .cv-remove {
        flex: 0 0 auto; width: 34px; height: 34px; border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
        color: #c53030; background: rgba(229, 62, 62, .08);
        transition: background .2s;
    }
    .cv-remove:hover { background: rgba(229, 62, 62, .18); }

    /* Honeypot: invisible to humans, present for bots */
    .hp-field { position: absolute !important; left: -9999px !important; top: -9999px !important; width: 1px !important; height: 1px !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important; }

    /* Submit button */
    .submit-btn {
        background: var(--ink); color: var(--sun);
        padding: 17px 18px; width: 100%;
        font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
        font-size: 12px; border-radius: 6px;
        transition: background .3s, color .3s, transform .3s;
    }
    .submit-btn:hover:not(:disabled) { background: var(--ocean); color: #fff; transform: translateY(-2px); }
    .submit-btn:disabled { opacity: .65; cursor: not-allowed; }

    @media (max-width: 1024px) {
        .apply-card { padding: 30px 22px; }
        .apply-steps { gap: 12px; }
    }
    @media (max-width: 768px) {
        .apply-card { padding: 24px 16px; }
        .cv-dropzone { padding: 20px 14px; }
        .cv-selected { flex-wrap: wrap; }
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

        // ── CV dropzone ───────────────────────────────────────────────────────
        var dropzone   = document.getElementById('cvDropzone');
        var fileInput  = document.getElementById('apply-cv');
        var idleState  = document.getElementById('cvIdleState');
        var pickedState = document.getElementById('cvSelectedState');
        var fileNameEl = document.getElementById('cvFileName');
        var fileSizeEl = document.getElementById('cvFileSize');
        var removeBtn  = document.getElementById('cvRemoveBtn');
        var clientError = document.getElementById('cvClientError');
        var liveRegion = document.getElementById('cvLiveRegion');

        var MAX_BYTES = 10 * 1024 * 1024; // 10 MB
        var ALLOWED  = ['pdf', 'doc', 'docx'];

        function formatBytes(bytes) {
            if (!bytes && bytes !== 0) return '';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function showError(message) {
            if (clientError) { clientError.textContent = message; clientError.hidden = false; }
            if (liveRegion) liveRegion.textContent = message;
            if (dropzone) dropzone.classList.add('has-error');
        }

        function clearError() {
            if (clientError) { clientError.textContent = ''; clientError.hidden = true; }
            if (dropzone) dropzone.classList.remove('has-error');
        }

        function renderSelection(file) {
            clearError();
            if (fileNameEl) fileNameEl.textContent = file.name;
            if (fileSizeEl) fileSizeEl.textContent = formatBytes(file.size);
            if (idleState) idleState.hidden = true;
            if (pickedState) pickedState.hidden = false;
            if (dropzone) dropzone.classList.add('has-file');
            if (liveRegion) liveRegion.textContent = 'Selected file: ' + file.name;
        }

        function resetSelection(announce) {
            if (fileInput) fileInput.value = '';
            if (idleState) idleState.hidden = false;
            if (pickedState) pickedState.hidden = true;
            if (dropzone) dropzone.classList.remove('has-file');
            clearError();
            if (announce !== false && liveRegion) liveRegion.textContent = 'File removed.';
        }

        function validateAndSet(file) {
            if (!file) return;
            var ext = (file.name.split('.').pop() || '').toLowerCase();
            if (ALLOWED.indexOf(ext) === -1) {
                resetSelection(false);
                showError('Unsupported file type. Please upload a PDF, DOC, or DOCX file.');
                return;
            }
            if (file.size > MAX_BYTES) {
                resetSelection(false);
                showError('File is too large. Maximum size is 10 MB.');
                return;
            }
            renderSelection(file);
        }

        if (dropzone && fileInput) {
            // Click / keyboard activation opens the picker (but not when clicking Remove).
            dropzone.addEventListener('click', function (event) {
                if (event.target === fileInput) return; // native activation already happened
                if (removeBtn && (event.target === removeBtn || removeBtn.contains(event.target))) return;
                fileInput.click();
            });

            fileInput.addEventListener('change', function () {
                validateAndSet(fileInput.files && fileInput.files[0]);
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    resetSelection();
                    fileInput.focus();
                });
            }

            // Drag & drop
            ['dragenter', 'dragover'].forEach(function (type) {
                dropzone.addEventListener(type, function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    dropzone.classList.add('is-dragover');
                });
            });
            ['dragleave', 'dragend', 'drop'].forEach(function (type) {
                dropzone.addEventListener(type, function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (type !== 'dragleave' || event.target === dropzone) {
                        dropzone.classList.remove('is-dragover');
                    }
                });
            });
            dropzone.addEventListener('drop', function (event) {
                var files = event.dataTransfer && event.dataTransfer.files;
                if (!files || !files.length) return;
                var dt = new DataTransfer();
                dt.items.add(files[0]);
                fileInput.files = dt.files;
                validateAndSet(files[0]);
            });
        }

        // Submit handler for Careers form
        var form = document.getElementById('careerForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                // Honeypot check
                var hpCheck = form.elements['hp_check'];
                if (hpCheck && hpCheck.value.trim() !== "") {
                    event.preventDefault();
                    return false;
                }
                // Time trap check
                var hpTimeVal = parseInt(form.elements['hp_time'] ? form.elements['hp_time'].value : 0, 10) || 0;
                if (hpTimeVal && Date.now() - hpTimeVal < 2000) {
                    event.preventDefault();
                    alert('Please wait a moment before submitting.');
                    return false;
                }
                // CV presence check (custom control, so validated here instead of natively)
                var cv = document.getElementById('apply-cv');
                if (cv && (!cv.files || cv.files.length === 0)) {
                    event.preventDefault();
                    showError('Please attach your CV before submitting.');
                    cv.focus();
                    return false;
                }
                // Submit animation
                var btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Submitting...';
                }
                return true;
            });
        }
    });
</script>
@endpush
