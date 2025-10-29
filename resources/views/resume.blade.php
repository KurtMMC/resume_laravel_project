<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $name }} - Resume</title>
    @vite(['resources/css/site.css'])
    @vite(['resources/js/theme-auto.js'])
    <style>
        /* Prevent fading/transition for sticky toasts on this page */
        .alert.no-auto-dismiss { transition: none !important; }
        .alert.no-auto-dismiss.fade-out { opacity: 1 !important; transform: none !important; }
        /* Ensure Edit Profile matches Light/Dark Mode button */
        .side-nav .nav-btn {
            display: block;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
            padding: 8px 12px;
            text-align: left;
            text-decoration: none;
        }
    </style>
</head>
<body>


<!-- Live Clock (top-right) -->
<div id="live-clock" class="live-clock" aria-live="polite" title="Current date & time"></div>

<header id="about" style="position:relative; padding-top:90px;">
    <div class="avatar-overlap" style="position:relative; margin-top:-50px; z-index:2;">
        <div class="avatar-frame" aria-label="Profile image" style="width:150px; height:150px; border-radius:50%; background:#e2e8f0; color:#64748b; display:flex; align-items:center; justify-content:center; overflow:hidden; margin:0 auto 10px auto;">
            @if(!empty($profile_picture))
                <img src="{{ asset($profile_picture) }}" alt="Profile picture" style="width:100%; height:100%; object-fit:cover; border-radius:50%; display:block;" />
            @else
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="width:100%; height:100%; padding:14px; box-sizing:border-box;">
                    <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                </svg>
            @endif
        </div>
    </div>
    <h1>{{ $name }}</h1>
    <p><strong>{{ $title }}</strong></p>

    <p>
        <a href="https://www.google.com/maps/search/{{ urlencode($address) }}" target="_blank">{{ $address }}</a><br>
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v2a2 2 0 0 1-2.2 2c-9.2-1-16-7.8-17-17A2 2 0 0 1 4.8 2h2a2 2 0 0 1 2 1.7c.1.9.3 1.8.7 2.7.2.5.1 1.1-.3 1.5L8 9.8a15 15 0 0 0 6.2 6.2l1.9-1.2c.5-.3 1-.4 1.5-.3 1 .3 1.9.6 2.8.7a2 2 0 0 1 1.6 1.6z"></path></svg>
        {{ $phone }}
        |
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v16H4z"></path><path d="M22 6l-10 7L2 6"/></svg>
        <a href="mailto:{{ $email }}">{{ $email }}</a>
    </p>
    <div class="hero-cta">
        <button type="button" id="download-pdf-btn" class="btn btn-primary btn-download">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12"></path><path d="M7 10l5 5 5-5"></path><path d="M5 21h14"></path></svg>
            Download PDF
        </button>
        <a href="{{ asset('resume.pdf') }}" download id="download-pdf-link" aria-hidden="true" tabindex="-1" style="display:none"></a>
    </div>
</header>

<div class="side-nav" aria-label="Page navigation">
    <div class="side-nav-greeting" id="side-nav-greeting" data-first-name="{{ session('user_name') ?? (explode(' ', trim($name))[0] ?? 'Guest') }}">
        Hello, <strong>{{ session('user_name') ?? (explode(' ', trim($name))[0] ?? 'Guest') }}</strong>
    </div>
    <div class="side-nav-title">Menu</div>
    <a href="#about">About</a>
    <a href="#experience-education">Experience &amp; Education</a>
    <a href="#skills">Skills</a>
    <a href="#user-socials">Socials</a>
    <hr style="margin:10px 0; border:0; border-top:1px solid #e2e8f0;">
    @if(empty($public_view) && (session('logged_in') || auth()->check()))
    <a href="{{ route('resume.edit') }}" class="nav-btn">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
        Edit Resume
    </a>
    @endif
    <button type="button" class="nav-btn dark-toggle" onclick="toggleDarkMode()">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path></svg>
        Dark Mode
    </button>
    @if(empty($public_view))
        <form action="/logout" method="get">
            @csrf
            <button type="submit" class="nav-btn logout-btn" title="Logout">
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="M16 17l5-5-5-5"></path><path d="M21 12H9"></path></svg>
                Log out
            </button>
        </form>
    @endif
</div>

<div class="two-col" id="experience-education">
<section id="experience">
    <h2>Experience</h2>
    <div class="timeline">
        @foreach ($experiences as $exp)
            @php
                $isArr = is_array($exp);
                $title = $isArr ? ($exp['title'] ?? ($exp['role'] ?? null)) : null;
                $company = $isArr ? ($exp['company'] ?? null) : null;
                $period = $isArr ? ($exp['period'] ?? ($exp['year'] ?? null)) : null;
                $addr = $isArr ? ($exp['address'] ?? null) : null;
                $det = $isArr ? ($exp['details'] ?? null) : null;
            @endphp
            <div class="timeline-item">
                @if($isArr)
                    @if($title)
                        <strong>{{ $title }}</strong>@if($company) <span> — {{ $company }}</span>@endif
                    @elseif($company)
                        <strong>{{ $company }}</strong>
                    @endif
                    @if($det)
                        <div>{!! $det !!}</div>
                    @endif
                    @if($addr || $period)
                        <div style="margin-top:4px; color:#6b7280; font-size:0.95rem;">
                            @if($addr) <span>{{ $addr }}</span>@endif
                            @if($addr && $period) <span> • </span>@endif
                            @if($period) <span>{{ $period }}</span>@endif
                        </div>
                    @endif
                @else
                    {{ $exp }}
                @endif
            </div>
        @endforeach
    </div>
</section>

<section id="education">
    <h2>Educational Attainment</h2>
    <div class="timeline">
        @foreach ($education as $edu)
            @php
                // Normalize per-item for rendering
                $lvl = is_array($edu) ? ($edu['level'] ?? 'Education') : 'Education';
                $det = is_array($edu) ? ($edu['details'] ?? e((string)$edu)) : e((string)$edu);
                $addr = is_array($edu) ? ($edu['address'] ?? '') : '';
                $yr = is_array($edu) ? ($edu['year'] ?? '') : '';
            @endphp
            <div class="timeline-item">
                <strong>{{ $lvl }}:</strong><br>{!! $det !!}
                @if($addr || $yr)
                    <div style="margin-top:4px; color:#6b7280; font-size:0.95rem;">
                        @if($addr) <span>{{ $addr }}</span>@endif
                        @if($addr && $yr) <span> • </span>@endif
                        @if($yr) <span>{{ $yr }}</span>@endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</section>
</div>

<section id="skills" class="skills">
    <h2>Skills</h2>
    @php
        // Normalize $skills for display as a simple list
        $skillList = [];
        if (is_array($skills)) {
            // If associative map (legacy), use keys; if sequential list, use values
            $isAssoc = array_keys($skills) !== range(0, count($skills) - 1);
            if ($isAssoc) {
                $skillList = array_keys($skills);
            } else {
                foreach ($skills as $s) { if (is_string($s) && trim($s) !== '') $skillList[] = $s; }
            }
        }
    @endphp
    <div class="skill-grid">
        @foreach ($skillList as $s)
            <div class="skill-chip">
                <div class="label">{{ $s }}</div>
            </div>
        @endforeach
    </div>
</section>



<section id="user-socials">
    <h2>Socials</h2>
    @if(is_array($socials) && count($socials))
        <div class="socials">
            @foreach ($socials as $platform => $link)
                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $platform }}</a>
            @endforeach
        </div>
    @else
        <p>No socials added.</p>
    @endif
    <hr style="margin:16px 0; border:0; border-top:1px solid #e2e8f0;">
</section>

<footer>
    <div id="contact" style="margin-bottom: 16px;">
        <h2>Contact Me</h2>
        <form class="contact-form" method="post" action="{{ route('contact.send') }}" id="resume-contact-form">
            @csrf
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
            <input type="tel" name="phone" placeholder="Your Phone (e.g. +63 912 345 6789)" pattern="[0-9\-\+\s]{7,15}" required>
            <div style="margin: 12px 0; display:flex; justify-content:center;">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>
            @error('g-recaptcha-response')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <button type="submit" id="resume-contact-btn">
                <span id="resume-contact-btn-text">Send Message</span>
                <span id="resume-contact-spinner" class="btn-spinner" style="display:none;"></span>
            </button>
        </form>

        @if(session('contact_success'))
            <div class="alert alert-success" style="max-width: 900px; margin: 12px auto 0;">{{ session('contact_success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="max-width: 900px; margin: 12px auto 0;">
                <ul style="margin:0; padding-left:18px; text-align:left;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <hr style="margin:16px 0; border:0; border-top:1px solid rgba(255,255,255,0.25);">
    </div>
    @php($siteSocials = config('site.socials') ?? [])
    @if(!empty($siteSocials))
        <p>Follow our website:</p>
        <div class="socials">
            @foreach ($siteSocials as $platform => $link)
                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $platform }}</a>
            @endforeach
        </div>
    @endif
    <p>&copy; {{ date('Y') }} {{ $name }} | All Rights Reserved</p>
</footer>


<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
// Dark mode toggle
function toggleDarkMode() {
    const btn = document.querySelector('.dark-toggle');
    const isDark = document.body.classList.toggle('dark-mode');
    const sun = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
    const moon = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path></svg>';
    btn.innerHTML = (isDark ? sun + ' Light Mode' : moon + ' Dark Mode');
    try { localStorage.setItem('themePreference', isDark ? 'dark' : 'light'); } catch(e) {}
}

// Smooth scroll from left nav links
(function(){
    // Apply time-based theme on load, unless user has a saved preference
    (function(){
        try {
            const pref = localStorage.getItem('themePreference');
            let desiredDark;
            if (pref === 'dark' || pref === 'light') {
                desiredDark = (pref === 'dark');
            } else {
                const h = new Date().getHours();
                desiredDark = (h < 6 || h >= 18); // Night: 6pm-6am
            }
            document.body.classList.toggle('dark-mode', desiredDark);
            // Sync button label with current state
            const btn = document.querySelector('.dark-toggle');
            if (btn) {
                const isDark = document.body.classList.contains('dark-mode');
                const sun = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
                const moon = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path></svg>';
                btn.innerHTML = (isDark ? sun + ' Light Mode' : moon + ' Dark Mode');
            }
        } catch(e) {}
    })();

    // Only intercept in-page anchors; let normal links (like Edit Profile) navigate
    document.querySelectorAll('.side-nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e){
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
    // Scrollspy: highlight current section in side nav
    const links = Array.from(document.querySelectorAll('.side-nav a[href^="#"]'));
    const map = new Map(links.map(a => [a.getAttribute('href'), a]));
    const opts = { root: null, rootMargin: '0px 0px -60% 0px', threshold: 0.25 };
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const id = '#' + (entry.target.getAttribute('id') || '');
            const link = map.get(id);
            if (!link) return;
            if (entry.isIntersecting) {
                links.forEach(l => { l.classList.remove('active'); l.removeAttribute('aria-current'); });
                link.classList.add('active');
                link.setAttribute('aria-current', 'true');
                // Section arrived: fade-in heading
                const h = entry.target.querySelector('h2');
                if (h && !h.dataset.arrived) {
                    h.dataset.arrived = '1';
                    h.classList.add('arrived');
                }
            }
        });
    }, opts);
    document.querySelectorAll('header[id], section[id], #experience-education').forEach(sec => io.observe(sec));
})();

// Reveal on scroll (first-view), lighter motion
(function(){
    const sections = document.querySelectorAll('section');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { root: null, rootMargin: '0px 0px -15% 0px', threshold: 0.15 });
    sections.forEach(sec => io.observe(sec));
})();

// Live clock: update every second using locale time
(function(){
    const el = document.getElementById('live-clock');
    if (!el) return;
    function fmt() {
        const now = new Date();
        // Use user's locale; include short weekday, date, and time with seconds
        return now.toLocaleString(undefined, {
            weekday: 'short', year: 'numeric', month: 'short', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }
    function tick(){ el.textContent = fmt(); }
    tick();
    setInterval(tick, 1000);
})();

// Time-based greeting for side nav
(function(){
    const el = document.getElementById('side-nav-greeting');
    if (!el) return;
    const first = el.getAttribute('data-first-name') || 'Guest';
    function greetingByHour(h){
        if (h < 12) return 'Good morning';
        if (h < 18) return 'Good afternoon';
        return 'Good evening';
    }
    function updateGreeting(){
        const h = new Date().getHours();
        el.innerHTML = greetingByHour(h) + ', <strong>' + first + '</strong>';
    }
    updateGreeting();
    // Refresh hourly in case the page stays open across boundaries
    setInterval(updateGreeting, 60 * 60 * 1000);
})();

// Contact form button loading (resume page)
// AJAX contact submission (no page refresh)
(function(){
    const form = document.getElementById('resume-contact-form');
    const btn = document.getElementById('resume-contact-btn');
    const btnText = document.getElementById('resume-contact-btn-text');
    const spinner = document.getElementById('resume-contact-spinner');
    const phone = form ? form.querySelector('input[name="phone"]') : null;

    function showAlert(type, html) {
        const container = document.getElementById('toast-region');
        const box = document.createElement('div');
        box.className = `alert alert-${type} no-auto-dismiss`;
        box.setAttribute('role', 'status');
        box.setAttribute('data-sticky','true');
        box.innerHTML = `${html}`;
        // Minimal close button
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'alert-close';
        btn.setAttribute('aria-label', 'Dismiss');
        btn.textContent = '×';
        // Remove immediately without fade
        btn.addEventListener('click', () => { box.remove(); });
        box.appendChild(btn);
        if (container) container.appendChild(box); else form.insertAdjacentElement('afterend', box);
        // No auto-dismiss; stays until user closes
    }

    function setLoading(isLoading){
        btn.disabled = isLoading;
        btn.classList.toggle('is-loading', isLoading);
        btnText.style.display = isLoading ? 'none' : 'inline';
        spinner.style.display = isLoading ? 'inline-block' : 'none';
    }

    if (form) {
        // Basic phone input mask and hints (keeps digits, formats as +XX XXXX XXXX or similar progressive)
        if (phone) {
            const hintId = 'phone-hint';
            let hint = document.getElementById(hintId);
            if (!hint) {
                hint = document.createElement('div');
                hint.id = hintId;
                hint.style.fontSize = '0.9rem';
                hint.style.marginTop = '4px';
                hint.style.color = '#666';
                phone.insertAdjacentElement('afterend', hint);
            }
            function digits(v){ return (v||'').replace(/[^\d+]/g,''); }
            function format(v){
                // Preserve leading + if present, then group digits for readability
                const plus = v.startsWith('+');
                const d = v.replace(/\D/g,'');
                let out = plus ? '+' : '';
                // simple grouping: country(1-3) then groups of 3-4
                if (d.length <= 3) out += d;
                else if (d.length <= 7) out += d.slice(0,3) + ' ' + d.slice(3);
                else if (d.length <= 11) out += d.slice(0,3) + ' ' + d.slice(3,7) + ' ' + d.slice(7);
                else out += d.slice(0,3) + ' ' + d.slice(3,7) + ' ' + d.slice(7,11) + ' ' + d.slice(11,15);
                return out.trim();
            }
            function valid(v){
                const d = v.replace(/\D/g,'');
                return d.length >= 10 && d.length <= 15; // basic range
            }
            phone.addEventListener('input', (e) => {
                const start = phone.selectionStart;
                const before = phone.value;
                const raw = digits(before);
                phone.value = format(raw);
                // heuristic caret fix
                const delta = phone.value.length - before.length;
                try { phone.setSelectionRange(start + delta, start + delta); } catch(_){}
                hint.textContent = valid(phone.value) ? 'Looks good.' : 'Enter 10–15 digits (you can start with + country code).';
                hint.style.color = valid(phone.value) ? '#0a7f2e' : '#666';
            });
            // Initialize hint
            phone.dispatchEvent(new Event('input'));
        }
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            setLoading(true);

            // Clear previous alerts
            document.querySelectorAll('.alert').forEach(a => a.remove());

            // Gather fields
            const data = new FormData(form);

            // Ensure we include reCAPTCHA token if the user solved the widget
            // grecaptcha will attach g-recaptcha-response automatically upon submit

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': data.get('_token') },
                    body: data
                });

                const ct = res.headers.get('content-type') || '';
                const isJson = ct.includes('application/json');
                const payload = isJson ? await res.json() : {};

                if (res.ok) {
                    showAlert('success', payload.message || 'Your message has been sent!');
                    form.reset();
                    if (window.grecaptcha) { try { grecaptcha.reset(); } catch(e){} }
                } else if (res.status === 422) {
                    // Validation errors
                    const errs = (payload && payload.errors) ? payload.errors : {};
                    const list = Object.values(errs).flat();
                    const html = `<ul style="margin:0; padding-left:18px; text-align:left;">${list.map(e => `<li>${e}</li>`).join('')}</ul>`;
                    showAlert('danger', html);
                } else {
                    showAlert('danger', payload.message || 'Something went wrong. Please try again.');
                }
            } catch (err) {
                showAlert('danger', 'Network error. Please check your connection and try again.');
            } finally {
                setLoading(false);
            }
        });
    }
})();

// Download PDF button triggers hidden download link
(function(){
    const btn = document.getElementById('download-pdf-btn');
    const a = document.getElementById('download-pdf-link');
    if (btn && a) {
        btn.addEventListener('click', () => {
            try { a.click(); } catch(e) { window.location.href = a.getAttribute('href'); }
        });
    }
})();
</script>

<!-- Full-screen loading overlay for logout and global transitions -->
<div id="global-loading-overlay" class="loading-overlay" aria-live="polite" aria-busy="true" hidden>
    <div class="loading-box" role="status" aria-label="Processing">
        <div class="overlay-spinner"></div>
        <div class="loading-text">Please wait…</div>
    </div>
</div>

<!-- Logout confirmation modal -->
<div id="logout-confirm-overlay" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="logout-confirm-title" hidden>
    <div class="modal-box">
        <h3 id="logout-confirm-title" style="margin-top:0;">Confirm Logout</h3>
        <p style="margin: 8px 0 16px 0;">Are you sure you want to log out?</p>
        <div class="modal-actions">
            <button type="button" id="logout-confirm-yes" class="btn-danger">Yes, log out</button>
            <button type="button" id="logout-confirm-cancel" class="btn-secondary">Cancel</button>
        </div>
    </div>
    <div class="modal-backdrop" aria-hidden="true"></div>
    
</div>

<script>
// Logout overlay
(function(){
    const form = document.querySelector('form[action="/logout"]');
    const loadingOverlay = document.getElementById('global-loading-overlay');
    const loadingText = loadingOverlay ? loadingOverlay.querySelector('.loading-text') : null;
    const loadingBox = loadingOverlay ? loadingOverlay.querySelector('.loading-box') : null;
    const confirmOverlay = document.getElementById('logout-confirm-overlay');
    const btnYes = document.getElementById('logout-confirm-yes');
    const btnCancel = document.getElementById('logout-confirm-cancel');
    let lastFocused = null;
    function getFocusable(container){
        return Array.from(container.querySelectorAll('button,[href],input,select,textarea,[tabindex]:not([tabindex="-1"])'))
            .filter(el => !el.hasAttribute('disabled') && !el.getAttribute('aria-hidden'));
    }

    function showLoading(msg){
        if (!loadingOverlay) return;
        if (loadingText) loadingText.textContent = msg || 'Logging you out…';
        if (loadingBox) loadingBox.setAttribute('aria-label', msg || 'Logging you out');
        loadingOverlay.removeAttribute('hidden');
        loadingOverlay.classList.add('show');
    }
    function showConfirm(){
        if (!confirmOverlay) return;
        lastFocused = document.activeElement;
        confirmOverlay.removeAttribute('hidden');
        confirmOverlay.classList.add('show');
        // Move focus into modal and trap
        const focusables = getFocusable(confirmOverlay);
        if (focusables.length) focusables[0].focus();
        function onKey(e){
            if (e.key === 'Escape') { e.preventDefault(); hideConfirm(); return; }
            if (e.key === 'Tab') {
                const list = getFocusable(confirmOverlay);
                if (!list.length) return;
                const first = list[0], last = list[list.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        }
        confirmOverlay.__keyHandler = onKey;
        document.addEventListener('keydown', onKey);
    }
    function hideConfirm(){
        if (!confirmOverlay) return;
        confirmOverlay.classList.remove('show');
        confirmOverlay.setAttribute('hidden', '');
        // remove trap and restore focus
        if (confirmOverlay.__keyHandler) {
            document.removeEventListener('keydown', confirmOverlay.__keyHandler);
            confirmOverlay.__keyHandler = null;
        }
        if (lastFocused && typeof lastFocused.focus === 'function') {
            try { lastFocused.focus(); } catch(e){}
        }
    }

    if (form && confirmOverlay) {
        form.addEventListener('submit', function(e){
            // Intercept to show confirm first
            e.preventDefault();
            showConfirm();
        });
    }
    if (btnCancel) {
        btnCancel.addEventListener('click', function(){ hideConfirm(); });
    }
    if (btnYes) {
        btnYes.addEventListener('click', function(){
            hideConfirm();
            showLoading('Logging you out…');
            // Submit without triggering the submit handler again
            try { form.submit(); } catch(e) { /* fallback if form missing */ }
        });
    }
})();
</script>

<script>
// Lazy-load Google Map after user interaction
(function(){
    const ph = document.getElementById('map-placeholder');
    if (!ph) return;
    function loadMap(){
        if (ph.__loaded) return; ph.__loaded = true;
        const iframe = document.createElement('iframe');
        iframe.width = '100%';
        iframe.height = '300';
        iframe.style.border = '0';
        iframe.loading = 'lazy';
        iframe.allowFullscreen = true;
        iframe.referrerPolicy = 'no-referrer-when-downgrade';
        iframe.src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3877.562313864176!2d121.0525!3d13.9025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd6e1c00000001%3A0x123456789!2sCuenca%2C%20Batangas!5e0!3m2!1sen!2sph!4v1234567890';
        ph.replaceWith(iframe);
    }
    ph.addEventListener('click', loadMap);
    ph.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); loadMap(); } });
})();
</script>

<!-- Floating scroll-to-top button -->
<button id="scroll-top-btn" class="scroll-top-btn" aria-label="Scroll to top" title="Back to top" hidden>
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M6 14l6-6 6 6" />
    </svg>
    <span class="sr-only">Back to top</span>
    <style>.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;}</style>
    </button>

<script>
// Scroll-to-top button behavior
(function(){
    const btn = document.getElementById('scroll-top-btn');
    const header = document.getElementById('about');
    if (!btn || !header) return;
    let hideTimer = null;
    function onScroll(){
        const y = window.scrollY || window.pageYOffset || 0;
        if (y > 300) {
            if (hideTimer) { clearTimeout(hideTimer); hideTimer = null; }
            btn.removeAttribute('hidden');
            // allow next frame to apply transitions cleanly
            requestAnimationFrame(() => btn.classList.add('show'));
        } else {
            btn.classList.remove('show');
            // wait for CSS transition before hiding from a11y tree/tab order
            hideTimer = setTimeout(() => {
                btn.setAttribute('hidden','');
            }, 220);
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    btn.addEventListener('click', function(){
        // Custom smooth scroll to top of header for consistent easing across browsers
        const startY = window.scrollY || window.pageYOffset || 0;
        const rect = header.getBoundingClientRect();
        const targetY = startY + rect.top; // absolute Y position of header
        const duration = 500; // ms
        const startTime = performance.now();
        const ease = t => t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t+2, 3)/2; // cubic easeInOut
        // disable CSS smooth temporarily to avoid double-smoothing
        document.documentElement.classList.add('no-smooth');
        function frame(now){
            const elapsed = now - startTime;
            const t = Math.min(1, elapsed / duration);
            const y = startY + (targetY - startY) * ease(t);
            window.scrollTo(0, y);
            if (t < 1) requestAnimationFrame(frame);
            else setTimeout(() => document.documentElement.classList.remove('no-smooth'), 0);
        }
        requestAnimationFrame(frame);
    });
})();
</script>

<style>
/* Heading arrival micro-interaction */
h2 { position: relative; }
h2.arrived { animation: heading-pop 160ms ease-out 1; }
@keyframes heading-pop { 0% { transform: translateY(4px); opacity: 0.6; } 100% { transform: none; opacity: 1; } }

/* Scroll-to-top: rounded pill with chevron already styled in CSS file */
</style>

<!-- Toast/alert container for consistent placement (bottom-left) -->
<div id="toast-region" aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 16px; left: 16px; z-index: 1100; display: flex; flex-direction: column; align-items: flex-start; gap: 8px;"></div>

<script>
// Show a one-time success toast if user just saved profile from the editor
(function(){
    try {
        const flag = sessionStorage.getItem('profileUpdated');
        if (flag === '1') {
            sessionStorage.removeItem('profileUpdated');
            const container = document.getElementById('toast-region');
            if (container) {
                const box = document.createElement('div');
                box.className = 'alert alert-success';
                box.style.minWidth = '260px';
                box.style.maxWidth = '420px';
                box.textContent = 'Profile Updated';
                container.appendChild(box);
                // Auto dismiss with fade (matches dashboard behavior)
                setTimeout(() => {
                    box.classList.add('fade-out');
                    setTimeout(() => box.remove(), 650);
                }, 2600);
            }
        }
    } catch(e) {}
})();
</script>

@if(!empty($public_view) && session('guest'))
<script>
(function(){
    // Show a floating guest toast in bottom-left (persistent, no fade)
    const container = document.getElementById('toast-region');
    if (!container) return;
    const box = document.createElement('div');
    box.className = 'alert no-auto-dismiss';
    box.setAttribute('data-sticky','true');
    box.setAttribute('role','status');
    box.style.background = 'rgba(41,128,185,0.10)';
    box.style.border = '1px solid rgba(41,128,185,0.35)';
    box.style.borderRadius = '8px';
    box.style.boxShadow = '0 8px 18px rgba(0,0,0,0.12)';
    box.style.minWidth = '260px';
    box.style.maxWidth = '420px';
    box.style.paddingRight = '44px';
    box.innerHTML = 'You\'re viewing this resume as a guest. <a href="/login" style="font-weight:600; margin-left:4px;">Sign in</a>';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'alert-close';
    btn.setAttribute('aria-label','Dismiss');
    btn.textContent = '×';
    // Remove immediately without fade
    btn.addEventListener('click', () => { box.remove(); });
    box.appendChild(btn);
    container.appendChild(box);
    // No auto-dismiss; toast stays until user closes it (also excluded by auto-dismiss.js via data-sticky)
})();
</script>
@endif

</body>
</html>
