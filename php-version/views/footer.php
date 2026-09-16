<?php
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}
$siteConfig = get_site_config();
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDir = dirname($scriptName);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = '';
}
?>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">
          <img src="<?= $baseDir ?>/assets/img/vipx_logo.png" alt="VIP X STORE">
          <span><?= htmlspecialchars($siteConfig['site_title'] ?? 'VIP X STORE') ?></span>
        </div>
        <p class="footer-about">Premium gaming panel store with clean setup, trusted support, proof gallery, and secure UPI checkout.</p>
      </div>

      <div>
        <div class="footer-links-title">Useful Links</div>
        <div class="footer-links">
          <a href="<?= $baseDir ?>/">Home</a>
          <a href="<?= $baseDir ?>/android.php">Android</a>
          <a href="<?= $baseDir ?>/ios.php">iOS</a>
          <a href="<?= $baseDir ?>/proofs.php">Proofs</a>
          <a href="<?= $baseDir ?>/gameplay.php">Gameplay</a>
        </div>
      </div>

      <div>
        <div class="footer-links-title">Support</div>
        <div class="footer-links">
          <a href="https://t.me/<?= ltrim($siteConfig['telegram'] ?? 'Real_Panel_100', '@https://t.me/') ?>" onclick="return showSupportLock();">Telegram Channel</a>
        </div>
      </div>

      <div>
        <div class="footer-links-title">Legal</div>
        <div class="footer-links">
          <a href="<?= $baseDir ?>/policy.php?type=terms">Terms of Service</a>
          <a href="<?= $baseDir ?>/policy.php?type=privacy">Privacy Policy</a>
          <a href="<?= $baseDir ?>/policy.php?type=refund">Refund Policy</a>
          <a href="<?= $baseDir ?>/policy.php?type=disclaimer">Disclaimer</a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      &copy; 2026 <?= htmlspecialchars($siteConfig['site_title'] ?? 'VIP X STORE') ?>. All rights reserved.
    </div>
  </div>
</footer>

<div class="lightbox" id="lightbox">
  <button class="close-btn" onclick="closeLightbox()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>
  <div id="lightboxContent"></div>
</div>

<!-- Support Lock Popup -->
<div class="modal-overlay" id="supportLockModal">
  <div class="modal-sheet" style="max-width:340px;text-align:center;">
    <div style="width:64px;height:64px;margin:4px auto 14px;border-radius:50%;background:rgba(126,200,227,0.12);border:1px solid var(--border-green);display:flex;align-items:center;justify-content:center;">
      <svg viewBox="0 0 24 24" fill="none" stroke="var(--neon-green-bright)" stroke-width="2" width="28" height="28"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>
    <h3 style="font-family:var(--font-display);font-size:1.15rem;font-weight:800;color:var(--text-primary);margin:50px 0 8px;">Support Locked</h3>
    <p style="font-size:0.9rem;color:var(--text-secondary);line-height:1.6;margin:0 0 18px;">Please buy any product first.<br>Support will be activated after your purchase.</p>
    <a href="<?= $baseDir ?>/android.php" class="btn btn-primary w-full" style="margin-bottom:10px;">Buy Now</a>
    <button onclick="closeSupportLock()" style="width:100%;padding:12px;border-radius:50px;background:transparent;border:1px solid var(--border-green);color:var(--text-secondary);font-weight:600;font-size:0.9rem;">Close</button>
  </div>
</div>

<div class="chat-widget" id="chatWidget">
  <div class="chat-panel" id="chatPanel">
    <div class="chat-panel-header">
      <span>Need help?</span>
      <button class="chat-close-btn" onclick="toggleChatPanel(false)" aria-label="Close chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <p class="chat-panel-sub">Chat with us directly for instant support.</p>
    <div class="chat-options">
      <a href="https://t.me/<?= ltrim($siteConfig['telegram'] ?? 'Real_Panel_100', '@https://t.me/') ?>" target="_blank" rel="noopener" class="chat-option chat-telegram">
        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M22 4.01L2.5 11.5c-1.1.43-1.1 1.16-.2 1.46l4.94 1.54 1.92 6.16c.23.62.45.86.9.86.46 0 .67-.21 1-.55l2.4-2.34 4.99 3.68c.92.5 1.58.24 1.81-.85l3.27-15.4c.32-1.33-.5-1.93-1.53-1.55zM8.5 14.4l9.9-6.25c.49-.3.95-.14.58.2L9.96 16.1l-.34 3.7-1.12-5.4z"/></svg>
        <span>Telegram</span>
      </a>
    </div>
  </div>

  <button class="chat-fab" id="chatFab" onclick="toggleChatPanel()" aria-label="Open chat support">
    <svg class="chat-fab-icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    <svg class="chat-fab-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>
</div>

<style>
/* Color Themes */
[data-theme="blue"] {
  --bg-primary: #050d18;
  --bg-secondary: #071424;
  --bg-card: rgba(20, 44, 70, 0.55);
  --neon-green: #5e86a9;
  --neon-green-bright: #8fc1ec;
  --neon-green-dim: #2a4f6e;
  --neon-orange: #5e86a9;
  --neon-orange-bright: #8fc1ec;
  --border-green: rgba(143, 193, 236, 0.3);
  --border-orange: rgba(143, 193, 236, 0.3);
}
[data-theme="purple"] {
  --bg-primary: #08050f;
  --bg-secondary: #100820;
  --bg-card: rgba(40, 20, 70, 0.55);
  --neon-green: #8a3fc9;
  --neon-green-bright: #b06ee0;
  --neon-green-dim: #5a2490;
  --neon-orange: #8a3fc9;
  --neon-orange-bright: #b06ee0;
  --border-green: rgba(176, 110, 224, 0.3);
  --border-orange: rgba(176, 110, 224, 0.3);
}
[data-theme="red"] {
  --bg-primary: #0f0505;
  --bg-secondary: #1a0808;
  --bg-card: rgba(50, 15, 15, 0.55);
  --neon-green: #c93f3f;
  --neon-green-bright: #e07070;
  --neon-green-dim: #902424;
  --neon-orange: #c93f3f;
  --neon-orange-bright: #e07070;
  --border-green: rgba(224, 112, 112, 0.3);
  --border-orange: rgba(224, 112, 112, 0.3);
}
[data-theme="lime"] {
  --bg-primary: #05070d;
  --bg-secondary: #0a0e18;
  --bg-card: rgba(11, 18, 32, 0.7);
  --neon-green: #7ac400;
  --neon-green-bright: #9ae600;
  --neon-green-dim: #4d7c00;
  --neon-orange: #7ac400;
  --neon-orange-bright: #9ae600;
  --border-green: rgba(154, 230, 0, 0.3);
  --border-orange: rgba(154, 230, 0, 0.25);
  --text-primary: #ffffff;
  --text-secondary: #c8d8b0;
  --text-muted: #6b7a55;
}
[data-theme="pastel"] {
  --bg-primary: #2a3535;
  --bg-secondary: #344040;
  --bg-card: rgba(93, 107, 107, 0.45);
  --neon-green: #98acad;
  --neon-green-bright: #BDD7D8;
  --neon-green-dim: #5D6B6B;
  --neon-orange: #F7CBCA;
  --neon-orange-bright: #F7CBCA;
  --border-green: rgba(189, 215, 216, 0.35);
  --border-orange: rgba(247, 203, 202, 0.35);
  --text-primary: #F1F7F7;
  --text-secondary: #D5E5E5;
  --text-muted: #98acad;
}
[data-theme="royal"] {
  --bg-primary: #190019;
  --bg-secondary: #2B124C;
  --bg-card: rgba(82, 43, 91, 0.45);
  --neon-green: #854F6B;
  --neon-green-bright: #DFB6B2;
  --neon-green-dim: #522B5B;
  --neon-orange: #854F6B;
  --neon-orange-bright: #FBE4D8;
  --border-green: rgba(223, 182, 178, 0.32);
  --border-orange: rgba(251, 228, 216, 0.28);
  --text-primary: #FBE4D8;
  --text-secondary: #DFB6B2;
  --text-muted: #a37287;
}
[data-theme="mocha"] {
  --bg-primary: #2A0800;
  --bg-secondary: #3b1e12;
  --bg-card: rgba(119, 81, 68, 0.4);
  --neon-green: #C09891;
  --neon-green-bright: #F4D6D8;
  --neon-green-dim: #775144;
  --neon-orange: #C09891;
  --neon-orange-bright: #F4D6D8;
  --border-green: rgba(192, 152, 145, 0.35);
  --border-orange: rgba(190, 168, 167, 0.30);
  --text-primary: #F4D6D8;
  --text-secondary: #BEA8A7;
  --text-muted: #9c7a6d;
}

.theme-picker {
  position: fixed;
  top: 74px;
  right: 16px;
  z-index: 10060;
  min-width: 230px;
  padding: 12px;
  border-radius: 16px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-green);
  box-shadow: 0 18px 50px rgba(0,0,0,0.55);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-8px) scale(0.97);
  transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
}
.theme-picker.open {
  opacity: 1;
  visibility: visible;
  transform: translateY(0) scale(1);
}
.theme-picker-title {
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--text-muted);
  padding: 2px 8px 10px;
}
.theme-option {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  width: 100%;
  padding: 9px 10px;
  border-radius: 10px;
  background: transparent;
  border: 1px solid transparent;
  color: var(--text-secondary);
  font-family: var(--font-body);
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s ease, border-color 0.2s ease;
}
.theme-option:hover { background: rgba(255,255,255,0.06); }
.theme-option.active {
  border-color: var(--border-green);
  background: rgba(255,255,255,0.05);
  color: var(--text-primary);
}
.theme-swatches { display: flex; gap: 5px; flex-shrink: 0; }
.theme-swatches span {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.25);
  display: block;
}
</style>

<div class="theme-picker" id="themePicker">
  <div class="theme-picker-title">Choose Colour Theme</div>
  <div id="themePickerList"></div>
</div>

<script>
window.addEventListener('load', function() {
  document.body.classList.add('page-loaded');
});

function toggleDrawer(open) {
  document.getElementById('mobileDrawer')?.classList.toggle('open', open);
  document.getElementById('mobileDrawerOverlay')?.classList.toggle('open', open);
  document.body.style.overflow = open ? 'hidden' : '';
}

var vxsLightboxStatePushed = false;
function openLightbox(html) {
  document.getElementById('lightboxContent').innerHTML = html;
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
  try {
    history.pushState({ vxsLightbox: true }, '');
    vxsLightboxStatePushed = true;
  } catch (e) { vxsLightboxStatePushed = false; }
}
function closeLightbox(fromPopstate) {
  var lb = document.getElementById('lightbox');
  if (!lb || !lb.classList.contains('open')) return;
  lb.classList.remove('open');
  document.getElementById('lightboxContent').innerHTML = '';
  document.body.style.overflow = '';
  if (!fromPopstate && vxsLightboxStatePushed) {
    vxsLightboxStatePushed = false;
    try { history.back(); } catch (e) {}
  } else {
    vxsLightboxStatePushed = false;
  }
}
window.addEventListener('popstate', function() {
  var lb = document.getElementById('lightbox');
  if (lb && lb.classList.contains('open')) {
    closeLightbox(true);
  }
});
document.getElementById('lightbox')?.addEventListener('click', function(e) {
  if (e.target === this) closeLightbox();
});

window.VXS_SUPPORT_UNLOCKED = false;
function showSupportLock() {
  if (window.VXS_SUPPORT_UNLOCKED) return true;
  document.getElementById('supportLockModal').classList.add('open');
  document.body.style.overflow = 'hidden';
  return false;
}
function closeSupportLock() {
  document.getElementById('supportLockModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('supportLockModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeSupportLock();
});

function toggleChatPanel(force) {
  const panel = document.getElementById('chatPanel');
  const fab = document.getElementById('chatFab');
  const isOpen = panel.classList.contains('open');
  const next = force !== undefined ? force : !isOpen;
  panel.classList.toggle('open', next);
  fab.classList.toggle('open', next);
}
document.addEventListener('click', function(e) {
  const widget = document.getElementById('chatWidget');
  if (widget && !widget.contains(e.target)) {
    toggleChatPanel(false);
  }
});

// Theme switcher
(function(){
  const THEMES = [
    { id: '',       name: '🔵 Steel Blue (Default)', sw: ['#060d1a', '#7ec8e3', '#f0f8ff'] },
    { id: 'lime',   name: '🟢 Lime',                 sw: ['#05070d', '#9ae600', '#ffffff'] },
    { id: 'pastel', name: '🌸 Pastel',               sw: ['#5D6B6B', '#BDD7D8', '#F7CBCA'] },
    { id: 'purple', name: '🟣 Purple',               sw: ['#08050f', '#b06ee0', '#f0e8ff'] },
    { id: 'red',    name: '🔴 Red',                  sw: ['#0f0505', '#e07070', '#ffe8e8'] },
    { id: 'royal',  name: '👑 Royal Mauve',          sw: ['#190019', '#854F6B', '#FBE4D8'] },
    { id: 'mocha',  name: '🤎 Mocha Mono',           sw: ['#2A0800', '#C09891', '#F4D6D8'] }
  ];

  const picker = document.getElementById('themePicker');
  const list = document.getElementById('themePickerList');
  if (!picker || !list) return;

  const saved = localStorage.getItem('vipxsTheme') || '';
  document.documentElement.setAttribute('data-theme', saved);

  THEMES.forEach(function(t) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'theme-option' + (t.id === saved ? ' active' : '');
    btn.setAttribute('data-theme-id', t.id);
    btn.innerHTML = '<span>' + t.name + '</span>' +
      '<span class="theme-swatches">' +
      t.sw.map(function(c) { return '<span style="background:' + c + ';"></span>'; }).join('') +
      '</span>';
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      applyTheme(t.id);
    });
    list.appendChild(btn);
  });

  function applyTheme(id) {
    localStorage.setItem('vipxsTheme', id);
    document.documentElement.setAttribute('data-theme', id);
    list.querySelectorAll('.theme-option').forEach(function(o) {
      o.classList.toggle('active', o.getAttribute('data-theme-id') === id);
    });
  }

  window.toggleThemePicker = function() {
    var sun = document.querySelector('#themeToggleBtn .theme-sun-img');
    if (sun) {
      sun.classList.remove('sun-spin');
      void sun.offsetWidth;
      sun.classList.add('sun-spin');
    }
    if (typeof toggleDrawer === 'function') {
      try { toggleDrawer(false); } catch (e) {}
    }
    picker.classList.toggle('open');
  };

  document.addEventListener('click', function(e) {
    if (!picker.classList.contains('open')) return;
    if (picker.contains(e.target)) return;
    if (e.target.closest('#themeToggleBtn')) return;
    picker.classList.remove('open');
  });
})();
</script>

</body>
</html>
