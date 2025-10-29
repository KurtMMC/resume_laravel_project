<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite(['resources/css/auth.css'])
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/auto-dismiss.js','resources/js/theme-auto.js'])
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>
        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <form method="post" action="{{ url('/register') }}">
            @csrf
        <label for="first_name" class="field-label">First Name</label>
        <input id="first_name" type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
        @error('first_name')
            <div class="error" role="alert" style="margin-top:6px;">{{ $message }}</div>
        @enderror

        <label for="middle_initial" class="field-label">Middle Initial (optional)</label>
        <input id="middle_initial" type="text" name="middle_initial" placeholder="M" value="{{ old('middle_initial') }}" maxlength="1" pattern="[A-Za-z]">
        @error('middle_initial')
            <div class="error" role="alert" style="margin-top:6px;">{{ $message }}</div>
        @enderror

        <label for="last_name" class="field-label">Last Name</label>
        <input id="last_name" type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
        @error('last_name')
            <div class="error" role="alert" style="margin-top:6px;">{{ $message }}</div>
        @enderror
        <label for="email" class="field-label">Email</label>
        <input id="email" type="email" name="email" placeholder="Email" required>
        <label for="password" class="field-label">Password</label>
        <input style="margin-top:6px;" id="password" type="password" name="password" placeholder="Password" required>
            <div class="password-feedback-box">
                <div class="strength-meter" id="strength-meter" aria-hidden="true" style="margin-top:8px;">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <ul class="pwd-hints" id="pwd-hints" aria-live="polite" style="margin-top:6px;">
                    <li data-check="length">At least 8 characters</li>
                    <li data-check="lower">Lowercase letter</li>
                    <li data-check="upper">Uppercase letter</li>
                    <li data-check="number">Number</li>
                    <li data-check="symbol">Symbol</li>
                </ul>
                <div class="match-indicator" id="match-indicator" aria-live="polite" style="margin-top:6px;"></div>
            </div>
            <label for="password_confirmation" class="field-label">Confirm Password</label>
            <input style="margin-top:6px;" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <div style="text-align:left; margin-top:10px;">
                <div style="display:flex; align-items:flex-start; gap:8px; font-size:0.95rem;">
                    <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <span>
                        I agree to the <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a> and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>
                        <em id="terms-hint" style="display:block; font-style:normal; font-size:0.85rem; color:#888; margin-top:4px;">Please open the Terms to enable the checkbox.</em>
                    </span>
                </div>
                @error('terms')
                    <div class="error" role="alert" style="margin-top:6px;">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" id="register-btn" disabled style="opacity:0.6; filter:grayscale(0.2); cursor:not-allowed;">
                <span id="register-btn-text">Register</span>
                <span id="register-spinner" class="btn-spinner" style="display:none;"></span>
            </button>
        </form>
        <div style="margin-top: 18px;">
            Already have an account? <a href="{{ url('/login') }}">Login now</a>
        </div>
    </div>
        <!-- Full-screen loading overlay for register -->
        <div id="loading-overlay" class="loading-overlay" aria-live="polite" aria-busy="true" hidden>
            <div class="loading-box" role="status" aria-label="Creating your account">
                <div class="overlay-spinner"></div>
                <div class="loading-text">Creating your account…</div>
            </div>
        </div>
    <script>
        // Strength meter and match indicator for register page (no toggle)
        (function(){
            const pwd = document.getElementById('password');
            const confirmPwd = document.getElementById('password_confirmation');
            const meter = document.getElementById('strength-meter');
            const bars = meter ? meter.querySelectorAll('.bar') : [];
            const hints = document.getElementById('pwd-hints');

            function setBars(activeCount) {
                if (!bars || !bars.length) return;
                bars.forEach((b, i) => {
                    if (i < activeCount) b.classList.add('active');
                    else b.classList.remove('active');
                });
            }

            function updateHints(state) {
                if (!hints) return;
                const map = { length: state.length, lower: state.lower, upper: state.upper, number: state.number, symbol: state.symbol };
                Array.from(hints.querySelectorAll('li')).forEach(li => {
                    const key = li.getAttribute('data-check');
                    li.classList.toggle('ok', !!map[key]);
                });
            }

            function evaluateStrength(value) {
                const state = {
                    length: value.length >= 8,
                    lower: /[a-z]/.test(value),
                    upper: /[A-Z]/.test(value),
                    number: /\d/.test(value),
                    symbol: /[^A-Za-z0-9]/.test(value)
                };
                let score = 0;
                if (state.length) score++;
                if (state.lower && state.upper) score++;
                if (state.number) score++;
                if (state.symbol) score++;
                return { state, score };
            }

            function updateStrengthUI() {
                const { state, score } = evaluateStrength(pwd.value || '');
                setBars(score);
                updateHints(state);
            }

            function updateMatchUI() {
                const indicator = document.getElementById('match-indicator');
                if (!indicator) return;
                const p1 = pwd.value || '';
                const p2 = confirmPwd.value || '';
                if (!p2) { indicator.textContent = ''; indicator.classList.remove('ok'); return; }
                const matches = p1 && p1 === p2;
                indicator.textContent = matches ? 'Passwords match' : 'Passwords do not match';
                indicator.classList.toggle('ok', matches);
            }

            if (pwd) {
                pwd.addEventListener('input', () => { updateStrengthUI(); updateMatchUI(); });
                updateStrengthUI();
            }
            if (confirmPwd) {
                confirmPwd.addEventListener('input', updateMatchUI);
            }

            // Button loading on submit
            const form = document.querySelector('form[action="{{ url("/register") }}"]');
            const btn = document.getElementById('register-btn');
            const btnText = document.getElementById('register-btn-text');
            const spinner = document.getElementById('register-spinner');
            const terms = document.getElementById('terms');
            const termsHint = document.getElementById('terms-hint');
            function toggleBtn() {
                const enabled = terms && terms.checked;
                btn.disabled = !enabled;
                btn.style.opacity = enabled ? '1' : '0.6';
                btn.style.filter = enabled ? 'none' : 'grayscale(0.2)';
                btn.style.cursor = enabled ? 'pointer' : 'not-allowed';
            }
            if (terms && btn){
                terms.addEventListener('change', toggleBtn);
                // initialize on load
                toggleBtn();
            }

            // Enable the checkbox as soon as the Terms link is opened (no scroll required)
            const termsLink = document.querySelector('a[href="{{ route("terms") }}"]');
            if (termsLink && terms) {
                // Only disable if not already checked (e.g., after validation error)
                if (!terms.checked) {
                    terms.disabled = true;
                    terms.style.cursor = 'not-allowed';
                }
                termsLink.addEventListener('click', function(){
                    terms.disabled = false;
                    terms.style.cursor = 'pointer';
                    if (termsHint) termsHint.style.display = 'none';
                });
            }
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
            if (form && btn && btnText && spinner) {
                form.addEventListener('submit', function() {
                    btn.disabled = true;
                    btn.classList.add('is-loading');
                    btnText.style.display = 'none';
                    spinner.style.display = 'inline-block';
                    showOverlay('Creating your account…');
                });
            }
            // Overlay when navigating back to Login
            const backToLogin = document.querySelector('a[href="{{ url("/login") }}"]');
            if (backToLogin) {
                backToLogin.addEventListener('click', function(){ showOverlay('Opening login…'); });
            }
            // Removed overlay on Terms/Privacy as requested
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
