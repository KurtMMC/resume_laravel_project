<form action="{{ route('contact.send') }}" method="POST" id="contact-form">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
    </div>
    <div>
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" pattern="[0-9\-\+\s]{7,15}" required>
    </div>
    <div style="margin: 12px 0; display:flex; justify-content:center;">
        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
    </div>
    @error('g-recaptcha-response')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit" id="contact-btn">
        <span id="contact-btn-text">Send Message</span>
        <span id="contact-spinner" class="btn-spinner" style="display:none;"></span>
    </button>
</form>

@if(session('contact_success'))
    <div class="alert alert-success">{{ session('contact_success') }}</div>
@endif

<!-- Auto theme script (light 6am–6pm, dark 6pm–6am) -->
@vite(['resources/js/theme-auto.js'])
<!-- Auto dismiss alerts -->
@vite(['resources/js/auto-dismiss.js'])

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

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
// AJAX contact submission (no page refresh)
(function(){
    const form = document.getElementById('contact-form');
    const btn = document.getElementById('contact-btn');
    const btnText = document.getElementById('contact-btn-text');
    const spinner = document.getElementById('contact-spinner');

    function showAlert(type, html) {
        const box = document.createElement('div');
        box.className = `alert alert-${type}`;
        box.innerHTML = html;
        form.insertAdjacentElement('afterend', box);
        setTimeout(() => box.remove(), 6000);
    }

    function setLoading(isLoading){
        btn.disabled = isLoading;
        btn.classList.toggle('is-loading', isLoading);
        btnText.style.display = isLoading ? 'none' : 'inline';
        spinner.style.display = isLoading ? 'inline-block' : 'none';
    }

    if (form) {
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            setLoading(true);

            // Clear previous alerts
            document.querySelectorAll('.alert').forEach(a => a.remove());

            const data = new FormData(form);

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
                    const errs = (payload && payload.errors) ? payload.errors : {};
                    const list = Object.values(errs).flat();
                    const html = `<ul style=\"margin:0; padding-left:18px; text-align:left;\">${list.map(e => `<li>${e}</li>`).join('')}</ul>`;
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
</script>
@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:18px; text-align:left;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
