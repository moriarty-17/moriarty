// Moriarty — the small amount of behaviour the static pages need.

(function () {
  'use strict';

  // Account menu in the navbar (matches the "Dropdown" frame in the design).
  var wrappers = document.querySelectorAll('[data-dropdown]');

  function closeAll(except) {
    wrappers.forEach(function (wrap) {
      if (wrap === except) return;
      var menu = wrap.querySelector('[data-dropdown-menu]');
      var trigger = wrap.querySelector('[data-dropdown-trigger]');
      if (menu) menu.hidden = true;
      if (trigger) trigger.setAttribute('aria-expanded', 'false');
    });
  }

  wrappers.forEach(function (wrap) {
    var trigger = wrap.querySelector('[data-dropdown-trigger]');
    var menu = wrap.querySelector('[data-dropdown-menu]');
    if (!trigger || !menu) return;

    trigger.addEventListener('click', function (event) {
      event.stopPropagation();
      var open = menu.hidden;
      closeAll(wrap);
      menu.hidden = !open;
      trigger.setAttribute('aria-expanded', String(open));
    });
  });

  document.addEventListener('click', function () {
    closeAll(null);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeAll(null);
  });

  // "Read More" on the offer description.
  var toggle = document.querySelector('[data-clamp-toggle]');
  var clamped = document.querySelector('[data-clamp]');

  if (toggle && clamped) {
    toggle.addEventListener('click', function () {
      var open = clamped.classList.toggle('is-open');
      toggle.firstChild.nodeValue = open ? 'Read Less ' : 'Read More ';
      toggle.querySelector('span').style.transform = open ? 'rotate(180deg)' : '';
    });
  }
})();
