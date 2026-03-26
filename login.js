(() => {
  /* ── Elements ── */
  const overlay   = document.getElementById('modalOverlay');
  const btnClose  = document.getElementById('modalClose');
  const tabLogin  = document.getElementById('tabLogin');
  const tabSignup = document.getElementById('tabSignup');
  const panelLogin  = document.getElementById('panelLogin');
  const panelSignup = document.getElementById('panelSignup');
  const togglePwLogin = document.getElementById('togglePwLogin');
  const togglePwReg   = document.getElementById('togglePwReg');

  /* ── Tab switching ── */
  window.switchTab = function(tab) {
    const isLogin = tab === 'login';
    panelLogin.style.display  = isLogin ? '' : 'none';
    panelSignup.style.display = isLogin ? 'none' : '';
    tabLogin.classList.toggle('active', isLogin);
    tabSignup.classList.toggle('active', !isLogin);
  };

  /* ── Open modal ── */
  window.openModal = function(tab = 'login') {
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    switchTab(tab);
    // focus first input after transition
    setTimeout(() => {
      const first = overlay.querySelector('.tab-panel:not([style*=none]) input');
      first?.focus();
    }, 80);
  };

  /* ── Close modal ── */
  function closeModal() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  btnClose?.addEventListener('click', closeModal);
  overlay?.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && overlay?.classList.contains('open')) closeModal();
  });

  /* ── Password toggles ── */
  function makeToggle(btn, inputId) {
    btn?.addEventListener('click', () => {
      const inp = document.getElementById(inputId);
      if (!inp) return;
      const show = inp.type === 'password';
      inp.type = show ? 'text' : 'password';
      btn.textContent = show ? 'Hide' : 'Show';
    });
  }
  makeToggle(togglePwLogin, 'password');
  makeToggle(togglePwReg,   'reg_password');

  /* ── Auto-open modal if there is a flash message or tab param ── */
  if (typeof HAS_FLASH !== 'undefined' && HAS_FLASH) {
    openModal(typeof INITIAL_TAB !== 'undefined' ? INITIAL_TAB : 'login');
  } else if (typeof INITIAL_TAB !== 'undefined' && INITIAL_TAB === 'signup') {
    openModal('signup');
  }
})();
