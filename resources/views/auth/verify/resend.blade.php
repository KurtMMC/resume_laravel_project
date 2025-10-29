<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resend Verification Email</title>
    @vite(['resources/css/auth.css'])
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/auto-dismiss.js','resources/js/theme-auto.js'])
</head>
<body>
    <div class="login-container">
        <h2>Resend Verification Email</h2>
        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif
        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <label for="email" class="field-label">Email</label>
            <input id="email" type="email" name="email" placeholder="Email address" required>
            <button type="submit">Send Verification Link</button>
        </form>
        <div style="margin-top: 18px;">
            <a href="{{ url('/login') }}">Back to Login</a>
        </div>
    </div>
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
