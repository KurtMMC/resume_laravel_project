<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    @vite(['resources/css/auth.css'])
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @vite(['resources/js/auto-dismiss.js','resources/js/theme-auto.js'])
</head>
<body>
    <div class="login-container">
        <h2>Forgot Password</h2>
        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="forgot-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required autofocus>
            <button type="submit" id="forgot-btn">
                <span id="forgot-btn-text">Send Reset Link</span>
                <span id="forgot-spinner" class="btn-spinner" style="display:none;"></span>
            </button>
        </form>
        <div style="margin-top: 18px;">
            <a href="{{ url('/login') }}">Back to Login</a>
            <span> | </span>
            <a href="{{ url('/register') }}">Register</a>
        </div>
    </div>
    <script>
        (function(){
            const form = document.getElementById('forgot-form');
            const btn = document.getElementById('forgot-btn');
            const btnText = document.getElementById('forgot-btn-text');
            const spinner = document.getElementById('forgot-spinner');
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