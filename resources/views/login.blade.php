<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Resume Website!</title>
    @vite(['resources/css/auth.css'])
    @vite(['resources/js/auto-dismiss.js','resources/js/theme-auto.js'])
</head>
<body class="login-page">
    <div class="login-container">
        <div class="brand-mark" aria-hidden="true" title="Resume Website">
            <svg viewBox="0 0 36 36" width="40" height="40" focusable="false" aria-hidden="true">
                <defs>
                    <linearGradient id="bm" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#2563eb"/>
                        <stop offset="100%" stop-color="#22c55e"/>
                    </linearGradient>
                </defs>
                <circle cx="18" cy="18" r="16" fill="url(#bm)" opacity="0.9" />
                <path d="M11 18h14" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                <path d="M18 11v14" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <h2>Welcome to Resume Website!</h2>
        @if(session('error'))
            <div class="error" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
        <form method="post" action="{{ route('login.post') }}">
            @csrf
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" required>
            <label for="password" class="field-label">Password</label>
            <div class="input-with-toggle" style="margin-top:6px;">
                <input id="password" type="password" name="password" placeholder="Password" required>
                <button type="button" class="toggle-password" id="toggle-password" aria-label="Show password" aria-pressed="false" title="Show password">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </button>
            </div>
            <div style="display:flex; justify-content: space-between; align-items:center; margin-top: 6px; margin-bottom: 6px; gap: 8px; flex-wrap:wrap;">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
                <a href="{{ route('verification.resend.form') }}">Resend verification email</a>
                @if(session('unverified_email'))
                    <a href="{{ route('verification.notice') }}">Check your email</a>
                @endif
            </div>
            <div id="caps-hint" class="caps-hint" hidden>Caps Lock is on</div>
            <div class="recaptcha-group">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                <button type="submit" id="login-btn">
                    <span id="login-btn-text">Login</span>
                    <span id="login-spinner" class="btn-spinner" style="display:none;"></span>
                </button>
            </div>
            @error('g-recaptcha-response')
                <div class="error" role="alert" style="text-align:center;">{{ $message }}</div>
            @enderror
        </form>
        <div style="margin-top: 18px; display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap;">
            <span>Don't have an account? <a href="{{ url('/register') }}">Register here</a></span>
        </div>

        <div style="margin-top: 8px; text-align:center;">
            <a href="{{ route('guest.login') }}" class="btn btn-secondary" style="display:inline-block; padding:10px 14px; border-radius:8px; text-decoration:none; border:1px solid #e5e7eb;">Continue as Guest</a>
        </div>

    @php($hasSocial = config('services.google.client_id') || config('services.microsoft.client_id'))
        @if($hasSocial)
        <div style="margin-top: 8px; text-align:center; color: var(--text-muted, #666); font-size: 0.95rem;">Or continue with</div>
        <div style="margin-top: 8px; display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap;">
            @if(config('services.google.client_id'))
            <a href="{{ url('/auth/google') }}" class="google-icon-btn" aria-label="Login with Google" title="Login with Google">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                    <circle cx="12" cy="12" r="11" fill="#fff" stroke="#ddd"/>
                    <path d="M13.2 17.6c-3 0-5.4-2.4-5.4-5.4s2.4-5.4 5.4-5.4c1.46 0 2.69.54 3.62 1.42l-1.47 1.41c-.63-.6-1.43-.94-2.15-.94-1.82 0-3.29 1.5-3.29 3.35s1.47 3.35 3.29 3.35c1.67 0 2.67-.96 2.88-2.3h-2.88v-1.83h4.88c.05.27.07.54.07.85 0 3.1-2.08 5.23-4.95 5.23z" fill="#4285F4"/>
                </svg>
            </a>
            @endif
            @if(config('services.microsoft.client_id'))
            <a href="{{ url('/auth/microsoft') }}" class="google-icon-btn" aria-label="Login with Microsoft" title="Login with Microsoft">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                    <circle cx="12" cy="12" r="11" fill="#fff" stroke="#ddd"/>
                    <g transform="translate(5,5)">
                        <rect width="6" height="6" fill="#F25022"/>
                        <rect x="7" width="6" height="6" fill="#7FBA00"/>
                        <rect y="7" width="6" height="6" fill="#00A4EF"/>
                        <rect x="7" y="7" width="6" height="6" fill="#FFB900"/>
                    </g>
                </svg>
            </a>
            @endif
        </div>
        @endif
    </div>
        <!-- Full-screen loading overlay -->
        <div id="loading-overlay" class="loading-overlay" aria-live="polite" aria-busy="true" hidden>
            <div class="loading-box" role="status" aria-label="Signing you in">
                <div class="overlay-spinner"></div>
                <div class="loading-text">Signing you in…</div>
            </div>
        </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Password show/hide toggle for login page
        (function(){
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('toggle-password');
            const overlay = document.getElementById('loading-overlay');
            const overlayText = overlay ? overlay.querySelector('.loading-text') : null;
            const overlayBox = overlay ? overlay.querySelector('.loading-box') : null;

            function showOverlay(message){
                if (!overlay) return;
                if (overlayText) overlayText.textContent = message || 'Please wait…';
                if (overlayBox) overlayBox.setAttribute('aria-label', message || 'Please wait');
                overlay.removeAttribute('hidden');
                overlay.classList.add('show');
            }

            if (pwd && toggle) {
                const eye = '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" stroke="currentColor" stroke-width="1.5"/></svg>';
                const eyeSlash = '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7 1.77 0 3.43-.38 4.92-1.07" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M21 12c-.6-1.36-1.57-2.62-2.79-3.67M3 3l18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>';
                function renderToggle() {
                    const isVisible = pwd.getAttribute('type') === 'text';
                    toggle.innerHTML = isVisible ? eyeSlash : eye;
                    toggle.title = isVisible ? 'Hide password' : 'Show password';
                    toggle.setAttribute('aria-label', toggle.title);
                    toggle.setAttribute('aria-pressed', String(isVisible));
                }
                // Initialize icon state
                renderToggle();
                toggle.addEventListener('click', () => {
                    const isHidden = pwd.getAttribute('type') === 'password';
                    pwd.setAttribute('type', isHidden ? 'text' : 'password');
                    renderToggle();
                });
            }
            // Reduce back navigation showing cached private pages
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, document.title, window.location.href);
            }

            // Show spinner on login submit
            const form = document.querySelector(`form[action="{{ route('login.post') }}"]`);
            const btn = document.getElementById('login-btn');
            const btnText = document.getElementById('login-btn-text');
            const spinner = document.getElementById('login-spinner');
            if (form && btn && btnText && spinner) {
                form.addEventListener('submit', function(e) {
                    btn.disabled = true;
                    btn.classList.add('is-loading');
                    btnText.style.display = 'none';
                    spinner.style.display = 'inline-block';
                    showOverlay('Signing you in…');
                });
            }

            // Caps Lock hint on password field
            const capsHint = document.getElementById('caps-hint');
            if (pwd && capsHint) {
                function handleKey(e){
                    const caps = e.getModifierState && e.getModifierState('CapsLock');
                    if (caps) { capsHint.hidden = false; }
                    else { capsHint.hidden = true; }
                }
                pwd.addEventListener('keydown', handleKey);
                pwd.addEventListener('keyup', handleKey);
                pwd.addEventListener('focus', handleKey);
                pwd.addEventListener('blur', () => { capsHint.hidden = true; });
            }

            // Overlay on Social login clicks
            const socialButtons = [
                { sel: `a[href="{{ url('/auth/google') }}"]`, msg: 'Redirecting to Google…' },
                { sel: `a[href="{{ url('/auth/microsoft') }}"]`, msg: 'Redirecting to Microsoft…' },
            ];
            socialButtons.forEach(({ sel, msg }) => {
                const el = document.querySelector(sel);
                if (el) el.addEventListener('click', () => showOverlay(msg));
            });

            // Overlay when navigating to Register
            const registerLink = document.querySelector(`a[href="{{ url('/register') }}"]`);
            if (registerLink) {
                registerLink.addEventListener('click', function(){
                    showOverlay('Opening registration…');
                });
            }

            // Overlay on Forgot Password link
            const forgotLink = document.querySelector(`a[href="{{ route('password.request') }}"]`);
            if (forgotLink) {
                forgotLink.addEventListener('click', function(){ showOverlay('Opening password reset…'); });
            }

            // Overlay on Resend verification email link
            const resendLink = document.querySelector(`a[href="{{ route('verification.resend.form') }}"]`);
            if (resendLink) {
                resendLink.addEventListener('click', function(){ showOverlay('Opening verification…'); });
            }

            // Overlay on Check your email link (if present)
            const noticeLink = document.querySelector(`a[href="{{ route('verification.notice') }}"]`);
            if (noticeLink) {
                noticeLink.addEventListener('click', function(){ showOverlay('Opening verification notice…'); });
            }

            // Overlay on Continue as Guest
            const guestLink = document.querySelector(`a[href="{{ route('guest.login') }}"]`);
            if (guestLink) {
                guestLink.addEventListener('click', function(){ showOverlay('Loading public view…'); });
            }
        })();
    </script>
    <!-- Time-based theme (respects user preference in localStorage) -->
    <script>
        (function(){
            try {
                const pref = localStorage.getItem('themePreference');
                let desiredDark;
                if (pref === 'dark' || pref === 'light') {
                    desiredDark = (pref === 'dark');
                } else {
                    const h = new Date().getHours();
                    desiredDark = (h < 6 || h >= 18);
                }
                document.body.classList.toggle('dark-mode', desiredDark);
            } catch(e) {}
        })();
    </script>
</body>
</html>