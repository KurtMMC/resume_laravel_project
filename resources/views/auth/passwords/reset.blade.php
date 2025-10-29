<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <script defer src="{{ asset('js/auto-dismiss.js') }}"></script>
    <script defer src="{{ asset('js/theme-auto.js') }}"></script>
</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="reset-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? request('token') }}">
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" name="email" placeholder="Email address" value="{{ old('email', $email ?? request('email')) }}" required>
            <label for="password" class="field-label">New password</label>
            <div class="input-with-toggle" style="margin-top:6px;">
                <input id="password" type="password" name="password" placeholder="New password" required>
                <button type="button" class="toggle-password" id="toggle-password" aria-label="Show password" aria-pressed="false" title="Show password">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </button>
            </div>
            <!-- Strength meter and password hints -->
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
            <label for="password_confirmation" class="field-label">Confirm password</label>
            <input style="margin-top:6px;" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" required>
            <div class="match-indicator" id="match-indicator" aria-live="polite" style="margin-top:6px;"></div>
            <button type="submit" id="reset-btn" style="margin-top:12px;">
                <span id="reset-btn-text">Reset Password</span>
                <span id="reset-spinner" class="btn-spinner" style="display:none;"></span>
            </button>
        </form>
        <div style="margin-top: 18px;">
            <a href="{{ url('/login') }}">Back to Login</a>
        </div>
    </div>

    <script>
        // Password toggle, strength meter, and match indicator
        (function(){
            const pwd = document.getElementById('password');
            const confirmPwd = document.getElementById('password_confirmation');
            const toggle = document.getElementById('toggle-password');
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
                const map = {
                    length: state.length,
                    lower: state.lower,
                    upper: state.upper,
                    number: state.number,
                    symbol: state.symbol,
                };
                Array.from(hints.querySelectorAll('li')).forEach(li => {
                    const key = li.getAttribute('data-check');
                    if (map[key]) li.classList.add('ok');
                    else li.classList.remove('ok');
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
                // Score to 0..4: length + (lower&upper as 1) + number + symbol
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
                if (!p2) {
                    indicator.textContent = '';
                    indicator.classList.remove('ok');
                    return;
                }
                const matches = p1 && p1 === p2;
                indicator.textContent = matches ? 'Passwords match' : 'Passwords do not match';
                indicator.classList.toggle('ok', matches);
            }

            // Toggle password visibility with eye/eye-slash icon
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
                renderToggle();
                toggle.addEventListener('click', () => {
                    const isHidden = pwd.getAttribute('type') === 'password';
                    pwd.setAttribute('type', isHidden ? 'text' : 'password');
                    renderToggle();
                });
            }

            // Live updates
            if (pwd) {
                pwd.addEventListener('input', () => {
                    updateStrengthUI();
                    updateMatchUI();
                });
                // Initialize on load
                updateStrengthUI();
            }
            if (confirmPwd) {
                confirmPwd.addEventListener('input', updateMatchUI);
            }

            // Button loading on submit
            const form = document.getElementById('reset-form');
            const btn = document.getElementById('reset-btn');
            const btnText = document.getElementById('reset-btn-text');
            const spinner = document.getElementById('reset-spinner');
            if (form && btn && btnText && spinner) {
                form.addEventListener('submit', function(){
                    btn.disabled = true;
                    btn.classList.add('is-loading');
                    btnText.style.display = 'none';
                    spinner.style.display = 'inline-block';
                });
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
