<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms & Conditions</title>
    @vite(['resources/css/site.css'])
    @vite(['resources/js/close-tab.js','resources/js/theme-auto.js','resources/js/auto-dismiss.js'])
</head>
<body>
    <main style="padding:24px 16px;">
        <section class="section-card visible" style="max-width: 960px; margin: 0 auto;">
            <h2 class="section-label">Terms & Conditions</h2>
            <div class="section-content">
                <p>By creating an account, you agree to the following terms and conditions. These include, but are not limited to, acceptable use, privacy and data usage, and limitations of liability.</p>
                <h3>Acceptable Use</h3>
                <p>You agree not to misuse the service, attempt unauthorized access, or engage in activity that disrupts the service or other users.</p>
                <h3>Privacy</h3>
                <p>We collect and process your information in accordance with our privacy practices. Your email is used for account-related communication such as verification and password resets.</p>
                <h3>Changes</h3>
                <p>We may update these terms from time to time. Continued use of the service constitutes acceptance of the updated terms.</p>
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