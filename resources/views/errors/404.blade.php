<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page not found • Resume Website</title>
    @vite(['resources/css/site.css'])
    @vite(['resources/js/theme-auto.js'])
    <style>
        .error-hero{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px;background:var(--bg,linear-gradient(135deg,#f7f7f9,#eef2f7));}
        .error-card{max-width:720px;width:100%;background:var(--panel,#fff);box-shadow:0 10px 30px rgba(0,0,0,.07);border-radius:16px;padding:28px;text-align:center}
        .error-code{font-size:3.5rem;margin:0 0 4px;color:#e74c3c;font-weight:800;letter-spacing:1px}
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
    <meta http-equiv="refresh" content="15;url=/">
</head>
<body>
    <main class="error-hero">
        <section class="error-card" role="alert" aria-live="polite">
            <div aria-hidden="true" style="margin-bottom:10px;opacity:.9">
                <svg viewBox="0 0 120 60" width="80" height="40" focusable="false" aria-hidden="true">
                    <defs>
                        <linearGradient id="e404" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#2563eb"/>
                            <stop offset="100%" stop-color="#22c55e"/>
                        </linearGradient>
                    </defs>
                    <rect x="5" y="10" width="110" height="40" rx="8" fill="url(#e404)"/>
                    <circle cx="25" cy="30" r="6" fill="#fff"/>
                    <circle cx="60" cy="30" r="6" fill="#fff"/>
                    <circle cx="95" cy="30" r="6" fill="#fff"/>
                </svg>
            </div>
            <div class="error-code">404</div>
            <h1 class="error-title">We can’t find that page</h1>
            <p class="error-text">The page you’re looking for may have been moved or never existed. You can go back or head to the homepage.</p>
            <div class="error-actions">
                <a href="/" class="btn btn-primary" aria-label="Go to homepage">Home</a>
                <a href="javascript:history.back()" class="btn" aria-label="Go back">Go back</a>
                <a href="/contact" class="btn" aria-label="Contact us">Contact</a>
            </div>
        </section>
    </main>
</body>
</html>
