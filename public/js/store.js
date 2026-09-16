/**
 * Akash X Store — Core Interactive Script
 * Theme Switcher, Voice Note Player, Payment & Delivery Modals, Proofs Lightbox
 */

// 1. Color Themes Configuration
const THEMES = [
  { id: '',       name: '🔵 Steel Blue (Default)', sw: ['#060d1a', '#7ec8e3', '#f0f8ff'] },
  { id: 'lime',   name: '🟢 Lime',                 sw: ['#05070d', '#9ae600', '#ffffff'] },
  { id: 'pastel', name: '🌸 Pastel',               sw: ['#5D6B6B', '#BDD7D8', '#F7CBCA'] },
  { id: 'purple', name: '🟣 Purple',               sw: ['#08050f', '#b06ee0', '#f0e8ff'] },
  { id: 'red',    name: '🔴 Red',                  sw: ['#0f0505', '#e07070', '#ffe8e8'] },
  { id: 'royal',  name: '👑 Royal Mauve',          sw: ['#190019', '#854F6B', '#FBE4D8'] },
  { id: 'mocha',  name: '🤎 Mocha Mono',           sw: ['#2A0800', '#C09891', '#F4D6D8'] }
];

document.addEventListener('DOMContentLoaded', () => {
  // Apply saved theme
  const savedTheme = localStorage.getItem('akashxTheme') || '';
  document.documentElement.setAttribute('data-theme', savedTheme);

  // Reveal Admin-only elements exclusively if authenticated as Admin
  try {
    const user = JSON.parse(localStorage.getItem('nexus_user') || 'null');
    if (user && user.role === 'admin') {
      document.querySelectorAll('.admin-only-link').forEach(el => {
        el.classList.add('show-admin');
      });
    }
  } catch (e) {}

  // Build Theme Picker options
  const list = document.getElementById('themePickerList');
  if (list) {
    THEMES.forEach(t => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'theme-option' + (t.id === savedTheme ? ' active' : '');
      btn.setAttribute('data-theme-id', t.id);
      btn.innerHTML = `
        <span>${t.name}</span>
        <span class="theme-swatches">
          ${t.sw.map(c => `<span style="background:${c};"></span>`).join('')}
        </span>
      `;
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        applyTheme(t.id);
      });
      list.appendChild(btn);
    });
  }

  // Voice Note Waveform Setup
  initVoiceNote();

  // Animated Counter
  initCounters();

  // Global Buy Now Delegation
  document.addEventListener('click', (e) => {
    const buyBtn = e.target.closest('.buy-now-btn');
    if (!buyBtn) return;
    e.preventDefault();

    const planId = buyBtn.dataset.planId || '1';
    const planTitle = buyBtn.dataset.planTitle || 'Panel Access';
    const planPrice = buyBtn.dataset.planPrice || '80';
    const panelName = buyBtn.dataset.panelName || (window.CURRENT_PANEL || 'Android Panel');

    openPaymentModal(planId, planTitle, planPrice, panelName);
  });
});

// Theme toggle
function toggleThemePicker() {
  const picker = document.getElementById('themePicker');
  const sun = document.querySelector('#themeToggleBtn .theme-sun-img');
  if (sun) {
    sun.classList.remove('sun-spin');
    void sun.offsetWidth;
    sun.classList.add('sun-spin');
  }
  if (picker) picker.classList.toggle('open');
}

function applyTheme(id) {
  localStorage.setItem('akashxTheme', id);
  document.documentElement.setAttribute('data-theme', id);
  document.querySelectorAll('.theme-option').forEach(o => {
    o.classList.toggle('active', o.getAttribute('data-theme-id') === id);
  });
}

// Close theme picker when clicking outside
document.addEventListener('click', (e) => {
  const picker = document.getElementById('themePicker');
  if (picker && picker.classList.contains('open')) {
    if (!picker.contains(e.target) && !e.target.closest('#themeToggleBtn')) {
      picker.classList.remove('open');
    }
  }
});

// Mobile drawer
function toggleDrawer(open) {
  const drawer = document.getElementById('mobileDrawer');
  const overlay = document.getElementById('mobileDrawerOverlay');
  if (drawer) drawer.classList.toggle('open', open);
  if (overlay) overlay.classList.toggle('open', open);
  document.body.style.overflow = open ? 'hidden' : '';
}

// Voice Note Player
function initVoiceNote() {
  const playBtn = document.getElementById('voicePlayBtn');
  const curEl = document.getElementById('voiceCurrent');
  const durEl = document.getElementById('voiceDuration');
  const waveform = document.getElementById('waveform');
  if (!playBtn) return;

  // Generate 48 waveform bars if not present
  if (waveform && waveform.children.length === 0) {
    const barHeights = [14, 22, 12, 18, 25, 20, 26, 18, 28, 15, 27, 24, 16, 12, 10, 26, 19, 21, 25, 22, 14, 16, 24, 10, 14, 25, 18, 24, 22, 21, 12, 22, 19, 15, 28, 26, 25, 19, 20, 23, 24, 21, 23, 17, 12, 22, 19, 14];
    barHeights.forEach(h => {
      const b = document.createElement('div');
      b.className = 'bar';
      b.style.height = `${h}px`;
      waveform.appendChild(b);
    });
  }

  let isPlaying = false;
  let timer = null;
  let seconds = 0;
  const totalSeconds = 42; // 00:42 guide duration

  if (durEl) durEl.textContent = '00:42';

  playBtn.onclick = () => {
    isPlaying = !isPlaying;
    if (isPlaying) {
      playBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>`;
      if (waveform) waveform.classList.add('playing');
      timer = setInterval(() => {
        seconds++;
        if (seconds > totalSeconds) {
          seconds = 0;
          isPlaying = false;
          clearInterval(timer);
          playBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M8 5v14l11-7z"/></svg>`;
          if (waveform) waveform.classList.remove('playing');
        }
        if (curEl) {
          const m = String(Math.floor(seconds / 60)).padStart(2, '0');
          const s = String(seconds % 60).padStart(2, '0');
          curEl.textContent = `${m}:${s}`;
        }
      }, 1000);
    } else {
      clearInterval(timer);
      playBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M8 5v14l11-7z"/></svg>`;
      if (waveform) waveform.classList.remove('playing');
    }
  };
}

// Animated Counter
function initCounters() {
  document.querySelectorAll('.counter-num[data-target]').forEach(el => {
    const target = parseInt(el.getAttribute('data-target'), 10);
    let start = 0;
    const duration = 1500;
    const startTime = performance.now();

    function step(now) {
      const progress = Math.min((now - startTime) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.floor(start + (target - start) * eased).toLocaleString();
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = target.toLocaleString();
    }

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          requestAnimationFrame(step);
          obs.unobserve(el);
        }
      });
    }, { threshold: 0.2 });
    obs.observe(el);
  });
}

// Support Lock & Telegram Chat
window.VXS_SUPPORT_UNLOCKED = false;

function showSupportLock() {
  if (window.VXS_SUPPORT_UNLOCKED) return true;
  const modal = document.getElementById('supportLockModal');
  if (modal) modal.classList.add('open');
  document.body.style.overflow = 'hidden';
  return false;
}

function closeSupportLock() {
  const modal = document.getElementById('supportLockModal');
  if (modal) modal.classList.remove('open');
  document.body.style.overflow = '';
}

function toggleChatPanel(force) {
  const panel = document.getElementById('chatPanel');
  if (!panel) return;
  const isOpen = panel.classList.contains('open');
  const next = force !== undefined ? force : !isOpen;
  panel.classList.toggle('open', next);
}

// ─── Payment & Delivery Modal ───
let currentCheckoutPlan = null;

function openPaymentModal(planId, title, price, panelName) {
  currentCheckoutPlan = { planId, title, price, panelName };

  document.getElementById('payPlanTitle').textContent = title;
  document.getElementById('payPanelName').textContent = `${panelName} (₹${price})`;

  // Generate dynamic UPI QR Code for igakash@fam
  const upiId = 'igakash@fam';
  const upiUrl = `upi://pay?pa=${upiId}&pn=AKASH%20X%20STORE&am=${price}&cu=INR&tn=AKASH-X-PANEL-${planId}`;
  const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data=${encodeURIComponent(upiUrl)}`;

  const qrImg = document.getElementById('payQrImg');
  if (qrImg) qrImg.src = qrUrl;

  // Reset steps & file
  document.getElementById('payStep1').style.display = 'block';
  document.getElementById('payStep3').style.display = 'none';
  document.getElementById('payFileName').style.display = 'none';
  document.getElementById('payFilePreview').style.display = 'none';
  document.getElementById('payScreenshotInput').value = '';

  const modal = document.getElementById('paymentModal');
  if (modal) modal.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closePaymentModal() {
  const modal = document.getElementById('paymentModal');
  if (modal) modal.classList.remove('open');
  document.body.style.overflow = '';
}

// Screenshot file picker trigger
document.addEventListener('DOMContentLoaded', () => {
  const pickBtn = document.getElementById('payPickFileBtn');
  const fileInp = document.getElementById('payScreenshotInput');
  if (pickBtn && fileInp) {
    pickBtn.onclick = () => fileInp.click();
    fileInp.onchange = () => {
      if (fileInp.files && fileInp.files[0]) {
        const file = fileInp.files[0];
        const fn = document.getElementById('payFileName');
        const fp = document.getElementById('payFilePreview');
        if (fn) {
          fn.textContent = `✓ Selected: ${file.name}`;
          fn.style.display = 'block';
        }
        if (fp) {
          fp.src = URL.createObjectURL(file);
          fp.style.display = 'block';
        }
      }
    };
  }

  // Submit payment confirmation
  const submitBtn = document.getElementById('paySubmitBtn');
  if (submitBtn) {
    submitBtn.onclick = handlePaymentConfirm;
  }
});

async function handlePaymentConfirm() {
  if (!currentCheckoutPlan) return;

  // Step 1 -> Step 3 (Loading screen)
  document.getElementById('payStep1').style.display = 'none';
  document.getElementById('payStep3').style.display = 'block';

  try {
    // 1. Create order record in backend
    const orderNumber = `AXS-${Date.now().toString().slice(-6)}-${Math.floor(1000 + Math.random() * 9000)}`;
    const licenseKey = `AXS-VIP-${Math.random().toString(36).substring(2, 6).toUpperCase()}-${Math.random().toString(36).substring(2, 6).toUpperCase()}-${Math.random().toString(36).substring(2, 6).toUpperCase()}`;

    // Record order in backend so it immediately shows up in Admin Dashboard
    await API.post('/orders/create', {
      order_number: orderNumber,
      customer_name: 'VIP Gamer',
      customer_email: 'buyer@akashxstore.in',
      customer_phone: '+91 9135164069',
      shipping_address: {
        item: `${currentCheckoutPlan.panelName} — ${currentCheckoutPlan.title}`,
        price: Number(currentCheckoutPlan.price)
      },
      direct_item: {
        name: `${currentCheckoutPlan.panelName} (${currentCheckoutPlan.title})`,
        price: Number(currentCheckoutPlan.price),
        quantity: 1
      },
      payment_method: 'upi_qr'
    }).catch(err => console.error('Order record notice:', err));

    // Artificial delay for authentic payment verification feel (1.5 seconds)
    await new Promise(resolve => setTimeout(resolve, 1600));

    // Close Payment Modal & Open Delivery Modal
    closePaymentModal();
    openDeliveryModal(orderNumber, licenseKey, currentCheckoutPlan.panelName);

    // Unlock Support Channel
    window.VXS_SUPPORT_UNLOCKED = true;

  } catch (err) {
    alert('Payment verification error: ' + err.message);
    document.getElementById('payStep1').style.display = 'block';
    document.getElementById('payStep3').style.display = 'none';
  }
}

function openDeliveryModal(orderNo, licenseKey, panelName) {
  document.getElementById('dlvOrderNo').textContent = orderNo;
  document.getElementById('dlvLicense').textContent = licenseKey;

  document.getElementById('dlvLicenseWrap').style.display = 'block';
  document.getElementById('dlvZipSection').style.display = 'block';
  document.getElementById('dlvGuideSection').style.display = 'block';
  document.getElementById('dlvSupportSection').style.display = 'block';

  // Build ZIP download link (Coming Soon)
  document.getElementById('dlvZipList').innerHTML = `
    <a href="#" onclick="alert('Panel File / App Download is Coming Soon! Please chat with Akash on WhatsApp (+91 9135164069) to receive direct file.'); return false;" class="btn btn-primary" style="font-size:0.85rem; padding:10px 18px; background:rgba(126,200,227,0.18); border:1px solid var(--border-green);">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Download ${panelName} (Coming Soon)
    </a>
  `;

  // Setup Video & Tutorial (Coming Soon)
  document.getElementById('dlvGuideList').innerHTML = `
    <a href="#" onclick="alert('Setup Video & Tutorial is Coming Soon! Please message on WhatsApp (+91 9135164069) for direct setup assistance.'); return false;" class="btn btn-primary" style="font-size:0.85rem; padding:10px 18px; background:rgba(126,200,227,0.12); color:var(--text-secondary); border:1px solid var(--border-green); margin-bottom:8px;">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
      Setup Video (Coming Soon)
    </a>
    <a href="https://wa.me/919135164069?text=Hello%20Akash%20X%20Store%2C%20I%20bought%20${encodeURIComponent(panelName)}%20Order%20${orderNo}%20Key%20${licenseKey}%20please%20send%20tutorial" target="_blank" class="btn btn-primary" style="font-size:0.85rem; padding:10px 18px; background:rgba(37,211,102,0.15); color:#25d366; border:1px solid rgba(37,211,102,0.4);">
      <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m-3.53 3.96c-.19 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.21 3.08c.15.2 2.09 3.19 5.07 4.48.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.75-.71 2-1.4.25-.69.25-1.28.17-1.4-.07-.12-.27-.2-.56-.34-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.18.2-.35.22-.64.08-.3-.15-1.25-.46-2.39-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.51.15-.17.2-.3.3-.49.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.56-.01z"/></svg>
      Get Live Setup Tutorial on WhatsApp
    </a>
  `;


  document.getElementById('dlvSupportSection').innerHTML = `
    <a href="https://wa.me/919135164069?text=Hello%20Akash%20X%20Store%2C%20I%20have%20completed%20payment%20for%20Order%20${orderNo}%20License%20Key%20${licenseKey}" target="_blank" class="btn btn-primary btn-block" style="background:#25d366; box-shadow:0 4px 16px rgba(37,211,102,0.4); color:#fff; font-size:0.88rem; margin-bottom:8px;">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m-3.53 3.96c-.19 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.21 3.08c.15.2 2.09 3.19 5.07 4.48.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.75-.71 2-1.4.25-.69.25-1.28.17-1.4-.07-.12-.27-.2-.56-.34-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.18.2-.35.22-.64.08-.3-.15-1.25-.46-2.39-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.51.15-.17.2-.3.3-.49.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.56-.01z"/></svg>
      Chat with Akash on WhatsApp (Unlocked)
    </a>
    <a href="#" onclick="alert('Telegram Channel is Coming Soon! Please chat on WhatsApp: +91 9135164069'); return false;" class="btn btn-primary btn-block" style="background:#229ed9; box-shadow:0 4px 16px rgba(34,158,217,0.3); color:#fff; font-size:0.88rem; opacity:0.85;">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.19-.08-.05-.19-.02-.27 0-.12.03-1.99 1.27-5.62 3.72-.53.36-1.01.54-1.44.53-.47-.01-1.38-.27-2.05-.49-.83-.27-1.49-.42-1.43-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.64-2.87 7.97-3.44 3.79-1.58 4.58-1.86 5.1-1.87.11 0 .37.03.54.17.14.12.18.28.2.45-.02.07-.02.13-.04.22z"/></svg>
      VIP Telegram Group (Coming Soon)
    </a>
  `;


  const dlvModal = document.getElementById('deliveryModal');
  if (dlvModal) dlvModal.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeDeliveryModal() {
  const dlvModal = document.getElementById('deliveryModal');
  if (dlvModal) dlvModal.classList.remove('open');
  document.body.style.overflow = '';
}

function copyLicenseKey() {
  const key = document.getElementById('dlvLicense').textContent;
  navigator.clipboard.writeText(key).then(() => {
    alert('License Key copied to clipboard: ' + key);
  });
}

// Lightbox for Proofs & Gameplay
function openLightbox(content) {
  const lb = document.getElementById('lightbox');
  const lbc = document.getElementById('lightboxContent');
  if (lb && lbc) {
    if (typeof content === 'string' && content.trim().startsWith('<')) {
      lbc.innerHTML = content;
    } else {
      lbc.innerHTML = `<img src="${content}" alt="Proof" style="max-width:90vw; max-height:85vh; border-radius:12px; border:1px solid var(--border-green);">`;
    }
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
}

function closeLightbox() {
  const lb = document.getElementById('lightbox');
  const lbc = document.getElementById('lightboxContent');
  if (lb) {
    lb.classList.remove('open');
    if (lbc) lbc.innerHTML = '';
  }
  document.body.style.overflow = '';
}

// Global modal close on Escape or backdrop click
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeLightbox();
    closePaymentModal();
    closeDeliveryModal();
    closeSupportLock();
  }
});

document.addEventListener('click', (e) => {
  const lb = document.getElementById('lightbox');
  if (lb && lb.classList.contains('open') && e.target === lb) {
    closeLightbox();
  }
  const sl = document.getElementById('supportLockModal');
  if (sl && sl.classList.contains('open') && e.target === sl) {
    closeSupportLock();
  }
});

