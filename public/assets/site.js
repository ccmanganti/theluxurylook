(() => {
  const toggle = document.querySelector('.nav-toggle');
  const menu = document.getElementById('main-nav');
  if (!toggle || !menu) return;
  const setOpen = (open) => {
    menu.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  };
  toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));
  menu.addEventListener('click', (event) => {
    if (event.target.closest('a')) setOpen(false);
  });
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape') setOpen(false); });
})();
