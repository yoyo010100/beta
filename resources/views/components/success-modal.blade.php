{{--
    Modern success modal — single source of truth for all post-redirect success flashes.

    Renders nothing unless session('success') is present. Self-contained:
    inline CSS + JS (no external dependencies), accessible (role=dialog, aria-modal,
    Escape / backdrop click to close, autofocus on the OK button), RTL-safe,
    with a brand-styled animated check and an auto-dismiss progress bar.
--}}
@if(session('success'))
@php
    $successMessage = session('success');
@endphp
<div class="success-modal-overlay" id="successModalOverlay" role="dialog" aria-modal="true" aria-labelledby="successModalTitle" aria-describedby="successModalMessage">
    <div class="success-modal-card" role="document">
        <div class="success-modal-glow" aria-hidden="true"></div>

        {{-- Animated check --}}
        <div class="success-modal-icon" aria-hidden="true">
            <svg viewBox="0 0 72 72" fill="none" class="success-modal-svg">
                <circle class="sm-circle" cx="36" cy="36" r="31" stroke="#16a34a" stroke-width="4" stroke-linecap="round"/>
                <path class="sm-check" d="M22 37.5l10 10L50 27" stroke="#16a34a" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <h2 class="success-modal-title" id="successModalTitle">{{ __('messages.success_title') }}</h2>
        <p class="success-modal-message" id="successModalMessage">{{ $successMessage }}</p>

        <button type="button" class="success-modal-btn" id="successModalOk">{{ __('messages.done') }}</button>
        <div class="success-modal-progress" aria-hidden="true"><span id="successModalBar"></span></div>
        <button type="button" class="success-modal-close" id="successModalClose" aria-label="{{ __('common.close') }}">&times;</button>
    </div>
</div>

<style>
    .success-modal-overlay {
        position: fixed; inset: 0; z-index: 2000;
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
        background: rgba(7, 19, 33, .58);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        opacity: 0;
        transition: opacity .28s ease;
    }
    .success-modal-overlay.is-visible { opacity: 1; }

    .success-modal-card {
        position: relative;
        width: min(440px, 100%);
        background: #ffffff;
        border-radius: 20px;
        padding: 36px 32px 26px;
        text-align: center;
        box-shadow: 0 30px 80px rgba(7, 19, 33, .45), 0 0 0 1px rgba(255, 255, 255, .6) inset;
        transform: translateY(26px) scale(.96);
        opacity: 0;
        transition: transform .38s cubic-bezier(.2, 1.4, .35, 1), opacity .3s ease;
        overflow: hidden;
        font-family: "DM Sans", "Cairo", sans-serif;
    }
    .success-modal-overlay.is-visible .success-modal-card { transform: translateY(0) scale(1); opacity: 1; }

    .success-modal-glow {
        position: absolute; top: -90px; left: 50%; transform: translateX(-50%);
        width: 320px; height: 190px; border-radius: 50%;
        background: radial-gradient(ellipse at center, rgba(22, 163, 74, .18) 0%, transparent 70%);
        pointer-events: none;
    }

    .success-modal-icon { position: relative; margin-bottom: 16px; }
    .success-modal-svg { width: 84px; height: 84px; }
    .sm-circle {
        stroke-dasharray: 195; stroke-dashoffset: 195;
        animation: sm-draw .7s .1s forwards cubic-bezier(.65, 0, .35, 1);
        opacity: .95;
    }
    .sm-check {
        stroke-dasharray: 48; stroke-dashoffset: 48;
        animation: sm-draw .45s .65s forwards cubic-bezier(.65, 0, .35, 1);
    }
    @keyframes sm-draw { to { stroke-dashoffset: 0; } }

    .success-modal-title {
        position: relative;
        font-family: "Manrope", "Cairo", sans-serif;
        font-size: 25px; font-weight: 800; letter-spacing: -.02em;
        color: #071321; margin-bottom: 8px;
    }
    .success-modal-message {
        position: relative;
        font-size: 14.5px; line-height: 1.65; color: #4a5568;
        max-width: 340px; margin: 0 auto 22px;
        word-wrap: break-word;
    }
    .success-modal-message strong { color: #071321; }

    .success-modal-btn {
        position: relative;
        width: 100%;
        background: #071321; color: #F2A900;
        font-family: "Manrope", "Cairo", sans-serif;
        font-size: 12px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase;
        padding: 16px 20px; border: none; border-radius: 10px; cursor: pointer;
        transition: background .25s, transform .25s, box-shadow .25s;
        min-height: 50px;
    }
    .success-modal-btn:hover { background: #003087; color: #fff; transform: translateY(-2px); box-shadow: 0 12px 26px rgba(0, 48, 135, .35); }
    .success-modal-btn:focus-visible { outline: 3px solid #003087; outline-offset: 2px; }

    .success-modal-progress {
        position: absolute; bottom: 0; left: 0; right: 0; height: 4px;
        background: rgba(7, 19, 33, .08);
    }
    .success-modal-progress span {
        display: block; height: 100%;
        background: linear-gradient(90deg, #F2A900, #16a34a);
        width: 100%;
        transform-origin: left;
        animation: sm-progress 6.5s linear forwards;
    }
    html[dir="rtl"] .success-modal-progress span { transform-origin: right; }
    @keyframes sm-progress { from { transform: scaleX(1); } to { transform: scaleX(0); } }

    .success-modal-close {
        position: absolute; top: 12px; inset-inline-end: 14px;
        width: 34px; height: 34px; border: none; background: rgba(7, 19, 33, .06);
        border-radius: 8px; font-size: 20px; line-height: 1; color: #4a5568; cursor: pointer;
        transition: background .2s, color .2s;
    }
    .success-modal-close:hover { background: rgba(7, 19, 33, .12); color: #071321; }

    @media (prefers-reduced-motion: reduce) {
        .success-modal-overlay, .success-modal-card, .sm-circle, .sm-check, .success-modal-progress span { transition: none; animation: none; }
        .sm-circle, .sm-check { stroke-dashoffset: 0; }
    }
</style>

<script>
    (function () {
        var overlay = document.getElementById('successModalOverlay');
        var okBtn = document.getElementById('successModalOk');
        var closeBtn = document.getElementById('successModalClose');
        if (!overlay || !okBtn) return;

        var AUTO_CLOSE_MS = 60000; // TEMP: extended for visual verification
        var autoTimer = null;
        var hidden = false;

        function show() {
            // Next frame so the enter transition plays.
            requestAnimationFrame(function () { overlay.classList.add('is-visible'); });
            document.body.style.overflow = 'hidden';
            okBtn.focus({ preventScroll: true });
            autoTimer = setTimeout(hide, AUTO_CLOSE_MS);
        }

        function hide() {
            if (hidden) return;
            hidden = true;
            clearTimeout(autoTimer);
            overlay.classList.remove('is-visible');
            document.body.style.overflow = '';
            window.setTimeout(function () { overlay.remove(); }, 320);
        }

        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) hide();
        });
        okBtn.addEventListener('click', hide);
        if (closeBtn) closeBtn.addEventListener('click', hide);
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' || event.key === 'Esc') hide();
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', show);
        } else {
            show();
        }
    })();
</script>
@endif
