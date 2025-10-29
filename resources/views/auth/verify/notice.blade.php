<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check your email</title>
    @vite(['resources/css/auth.css'])
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/auto-dismiss.js','resources/js/theme-auto.js'])
</head>
<body>
    <div class="login-container">
        <h2>Check your email</h2>
        @if(session('success'))
            <div class="success" role="status">{{ session('success') }}</div>
        @else
            <div class="success" role="status">We sent a verification link to your email. Please click the link to verify your account.</div>
        @endif
        @if(session('error'))
            <div class="error" role="alert">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="error" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('unverified_email'))
            <form method="POST" action="{{ route('verification.resend') }}" style="margin-top:12px;">
                @csrf
                <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                <div style="margin: 6px 0 8px 0; color:#555;">
                    Resend to <strong>{{ session('unverified_email') }}</strong>
                </div>
                <button type="submit">Resend verification link</button>
            </form>
        @else
            <form method="POST" action="{{ route('verification.resend') }}" style="margin-top:12px;">
                @csrf
                <label for="email" class="field-label">Email</label>
                <input id="email" type="email" name="email" placeholder="Your email address" required>
                <button type="submit">Resend verification link</button>
            </form>
        @endif
        <div style="margin-top: 16px;">
            <a href="{{ url('/login') }}">Back to Login</a>
        </div>
    </div>
</body>
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
</html>
