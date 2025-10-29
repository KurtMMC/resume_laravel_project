(function(){
  function isDarkByTime(date){
    var h = (date || new Date()).getHours();
    return (h < 6 || h >= 18); // Dark from 6pm–6am
  }
  function getPreference(){
    try { return localStorage.getItem('themePreference'); } catch(e) { return null; }
  }
  function applyTheme(){
    try {
      var pref = getPreference();
      var desiredDark;
      if (pref === 'dark' || pref === 'light') {
        desiredDark = (pref === 'dark');
      } else {
        desiredDark = isDarkByTime();
      }
      document.body.classList.toggle('dark-mode', desiredDark);
    } catch(e) {}
  }
  function msUntilNextBoundary(){
    var now = new Date();
    var h = now.getHours();
    var next = new Date(now);
    if (h < 6) {
      next.setHours(6,0,0,0); // next 6am today
    } else if (h < 18) {
      next.setHours(18,0,0,0); // next 6pm today
    } else {
      next.setDate(next.getDate() + 1);
      next.setHours(6,0,0,0); // next 6am tomorrow
    }
    return next.getTime() - now.getTime();
  }
  function schedule(){
    applyTheme();
    var delay = msUntilNextBoundary();
    setTimeout(function tick(){
      applyTheme();
      setTimeout(tick, msUntilNextBoundary());
    }, Math.max(1000, delay));
  }
  // React to preference changes from other tabs/windows
  try { window.addEventListener('storage', function(ev){ if (ev && ev.key === 'themePreference') applyTheme(); }); } catch(e) {}
  try { schedule(); } catch(e) {}
})();
