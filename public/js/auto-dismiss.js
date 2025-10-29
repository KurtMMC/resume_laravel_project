/* Deprecated: moved to resources/js/auto-dismiss.js and bundled by Vite.
   Use @vite(['resources/js/auto-dismiss.js']) in Blade. */
(function(){
  function isErrorAlert(el){
    if (!el || !el.classList) return false;
    return el.classList.contains('alert-danger') || el.classList.contains('error');
  }
  function isSuccessAlert(el){
    if (!el || !el.classList) return false;
    return el.classList.contains('alert-success') || el.classList.contains('success');
  }
  function addStatusIcon(el){
    if (!el || el.querySelector('.alert-icon')) return;
    try {
      // Ensure positioning context
      el.style.position = el.style.position || 'relative';
      var span = document.createElement('span');
      span.className = 'alert-icon';
      span.setAttribute('aria-hidden', 'true');
      // Minimalist inline SVG icons (stroke = currentColor)
      var svg;
      if (isSuccessAlert(el)) {
        svg = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      } else if (isErrorAlert(el)) {
        svg = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="16.5" r="1" fill="currentColor"/></svg>';
      } else {
        // Info/default
        svg = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 10v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="7.5" r="1" fill="currentColor"/></svg>';
      }
      span.innerHTML = svg;
      el.appendChild(span);
    } catch(e){}
  }
  function addCloseButton(el){
    if (!el || el.querySelector('.alert-close')) return;
    try {
      el.style.position = el.style.position || 'relative';
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'alert-close';
      btn.setAttribute('aria-label', 'Dismiss notification');
      btn.innerHTML = '&times;';
      btn.addEventListener('click', function(){
        try { el.classList.add('fade-out'); } catch(e){}
        setTimeout(function(){ try { el.remove(); } catch(e){} }, 700);
      });
      el.appendChild(btn);
    } catch(e){}
  }
  function scheduleFade(el){
    if (!el || el.__autoDismissBound) return;
    // Do not auto-dismiss error alerts
    if (isErrorAlert(el)) return;
    el.__autoDismissBound = true;
    var delay = parseInt(el.getAttribute('data-dismiss-delay') || '6000', 10);
    delay = isNaN(delay) ? 6000 : delay;
    setTimeout(function(){
      try { el.classList.add('fade-out'); } catch(e){}
      setTimeout(function(){ try { el.remove(); } catch(e){} }, 700);
    }, Math.max(1000, delay));
  }

  function init(){
    // Target common success/info containers; errors are filtered out in scheduleFade()
    var selectors = ['.alert', '.login-container .success'];
    document.querySelectorAll(selectors.join(',')).forEach(function(el){
      scheduleFade(el);
      addCloseButton(el);
      addStatusIcon(el);
    });
    // Also add close buttons to error-type messages which are not auto-dismissed
    var errorSelectors = ['.alert.alert-danger', '.login-container .error'];
    document.querySelectorAll(errorSelectors.join(',')).forEach(function(el){
      addCloseButton(el);
      addStatusIcon(el);
    });
    try {
      var obs = new MutationObserver(function(muts){
        muts.forEach(function(m){
          m.addedNodes.forEach(function(node){
            if (!(node instanceof HTMLElement)) return;
            if (node.matches && selectors.some(function(s){ return node.matches(s); })) { scheduleFade(node); addCloseButton(node); addStatusIcon(node); }
            selectors.forEach(function(s){ node.querySelectorAll && node.querySelectorAll(s).forEach(function(n){ scheduleFade(n); addCloseButton(n); addStatusIcon(n); }); });
            // Errors: add close button and status icon only
            if (node.matches && errorSelectors.some(function(s){ return node.matches(s); })) { addCloseButton(node); addStatusIcon(node); }
            errorSelectors.forEach(function(s){ node.querySelectorAll && node.querySelectorAll(s).forEach(function(n){ addCloseButton(n); addStatusIcon(n); }); });
          });
        });
      });
      obs.observe(document.body, { childList: true, subtree: true });
    } catch(e){}
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
