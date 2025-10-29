/* Deprecated: moved to resources/js/close-tab.js and bundled by Vite.
   Use @vite(['resources/js/close-tab.js']) in Blade. */
(function(){
  function closeOrHint() {
    // Request close
    window.close();
    // If blocked, show a small hint to manually close
    setTimeout(function(){
      try {
        if (!window.closed) {
          var msg = document.getElementById('close-hint');
          if (!msg) {
            msg = document.createElement('div');
            msg.id = 'close-hint';
            msg.style.textAlign = 'center';
            msg.style.margin = '24px auto 8px';
            msg.style.fontSize = '0.95rem';
            msg.innerHTML = '<p>You can now close this tab.</p>';
            document.body.appendChild(msg);
          }
        }
      } catch(_) {}
    }, 150);
  }

  function attach() {
    var btn = document.getElementById('close-tab');
    if (!btn) return;
    btn.addEventListener('click', function(e){
      e.preventDefault();
      closeOrHint();
    });
  }

  // DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attach);
  } else {
    attach();
  }
})();
