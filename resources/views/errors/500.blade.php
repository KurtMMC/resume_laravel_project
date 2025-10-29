<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something went wrong • Resume Website</title>
    @vite(['resources/css/site.css'])
    @vite(['resources/js/theme-auto.js'])
    <style>
        .error-hero{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px;background:var(--bg,linear-gradient(135deg,#f7f7f9,#eef2f7));}
        .error-card{max-width:720px;width:100%;background:var(--panel,#fff);box-shadow:0 10px 30px rgba(0,0,0,.07);border-radius:16px;padding:28px;text-align:center}
        .error-code{font-size:3.5rem;margin:0 0 4px;color:#ef4444;font-weight:800;letter-spacing:1px}
        .error-title{font-size:1.5rem;margin:0 0 8px}
        .error-text{color:#555;margin:0 0 18px}
        .error-actions{display:flex;flex-wrap:wrap;gap:10px;justify-content:center}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;border:1px solid #ddd;background:#fff;color:#333;text-decoration:none}
        .btn-primary{background:#2563eb;color:#fff;border-color:#1d4ed8}
        body.dark-mode .error-hero{background:linear-gradient(135deg,#111827,#0b1220)}
        body.dark-mode .error-card{background:#0f172a;color:#e5e7eb;border:1px solid #1f2937}
        body.dark-mode .error-text{color:#9ca3af}
        body.dark-mode .btn{background:#111827;color:#e5e7eb;border-color:#1f2937}
        body.dark-mode .btn-primary{background:#3b82f6;border-color:#2563eb}
    </style>
    <script>
        (function(){
            try{const pref=localStorage.getItem('themePreference');if(pref==='dark'||pref==='light'){document.body.classList.toggle('dark-mode',pref==='dark');}}
            catch(e){}
        })();
    </script>
    <meta http-equiv="refresh" content="20;url=/">
</head>
<body>
    <main class="error-hero">
        <section class="error-card" role="alert" aria-live="polite">
            <div aria-hidden="true" style="margin-bottom:10px;opacity:.9">
                <svg viewBox="0 0 120 60" width="80" height="40" focusable="false" aria-hidden="true">
                    <defs>
                        <linearGradient id="e500" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#2563eb"/>
                            <stop offset="100%" stop-color="#22c55e"/>
                        </linearGradient>
                    </defs>
                    <rect x="5" y="10" width="110" height="40" rx="8" fill="url(#e500)"/>
                    <path d="M15 35h20M31 25l4 5-4 5" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="error-code">500</div>
            <h1 class="error-title">Something went wrong</h1>
            <p class="error-text">An unexpected error occurred. Try again in a moment. If the problem persists, please contact us.</p>
            <div style="text-align:left; max-width: 560px; margin: 0 auto 14px auto; color: #555;">
                <strong>What you can try:</strong>
                <ul style="margin: 6px 0 0 18px;">
                    <li>Reload this page</li>
                    <li>Go back to the homepage</li>
                    <li>Contact us if the issue continues</li>
                </ul>
            </div>
            <div class="error-actions">
                <a href="/" class="btn btn-primary" aria-label="Go to homepage">Home</a>
                <a href="/contact" class="btn" aria-label="Contact us">Contact</a>
                <a href="javascript:location.reload()" class="btn" aria-label="Reload page">Reload</a>
            </div>
        </section>
    </main>
</body>
</html>
