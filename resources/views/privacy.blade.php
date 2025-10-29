<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy</title>
    @vite(['resources/css/site.css'])
    @vite(['resources/js/close-tab.js','resources/js/theme-auto.js','resources/js/auto-dismiss.js'])
</head>
<body>
    <main style="padding:24px 16px;">
        <section class="section-card visible" style="max-width: 960px; margin: 0 auto;">
            <h2 class="section-label">Privacy Policy</h2>
            <div class="section-content">
                <p>This policy explains how we collect, use, and protect your personal information. We only collect data necessary to provide our services and communicate with you about your account.</p>
                <h3>Data Collection</h3>
                <p>We collect name, email, and information you provide through forms. We may also collect technical data like IP address for security (e.g., CAPTCHA).</p>
                <h3>Usage</h3>
                <p>Your email is used for verification, password resets, and account notifications.</p>
                <h3>Security</h3>
                <p>We use reasonable security measures to protect your data. No method is 100% secure.</p>

                <h3>Google reCAPTCHA</h3>
                <p>
                    We use Google reCAPTCHA to protect forms from abuse. reCAPTCHA may collect device and usage data and set cookies to perform risk analysis.
                    Use of reCAPTCHA is subject to the <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Google Privacy Policy</a>
                    and <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Terms of Service</a>.
                    By interacting with protected forms, you consent to this processing for security purposes.
                </p>

                <h3>Google Maps</h3>
                <p>
                    The map on the resume page is loaded on demand (after you click "Click to load map"). When loaded, Google may receive your IP address, device information, and usage data.
                    Embedding Google Maps is governed by the <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Google Privacy Policy</a>.
                    You can choose not to load the map; core site functionality is still available.
                </p>

                <h3>Analytics</h3>
                <p>
                    We currently do not use analytics that track individuals. If analytics are added in the future, we will prefer privacy-friendly solutions such as
                    <a href="https://plausible.io/privacy-focused-web-analytics" target="_blank" rel="noopener noreferrer">Plausible</a> or
                    <a href="https://umami.is/" target="_blank" rel="noopener noreferrer">Umami</a>, configured without cookies wherever possible.
                    If analytics require cookies or similar identifiers, a minimal cookie consent banner will be shown with a clear option to decline non-essential cookies.
                </p>
                <p style="margin-top:16px; text-align:center;">
                    <button id="close-tab" type="button" style="display:inline-block; padding:8px 14px; border-radius:8px; border:1px solid rgba(41,128,185,0.35); background: rgba(41,128,185,0.08); color:#2c3e50; cursor:pointer;">Go back</button>
                </p>
            </div>
        </section>
    </main>
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