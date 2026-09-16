<?php
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}
$siteConfig = get_site_config();
$cleanPhone = preg_replace('/[^0-9]/', '', $siteConfig['whatsapp'] ?? '919135164069');
?>

  <!-- Footer Section -->
  <footer style="background: #040914; border-top: 1px solid var(--border-green); padding: 3rem 1.25rem 2rem; margin-top: 4rem; color: var(--text-secondary);">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
      
      <!-- Col 1: Brand & Disclaimer -->
      <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <img src="<?= $baseDir ?>/assets/img/logo.png" alt="Logo" style="width: 36px; height: 36px; border-radius: 50%;">
          <span style="font-weight: 800; font-size: 1.15rem; color: #fff;"><?= htmlspecialchars($siteConfig['site_title']) ?></span>
        </div>
        <p style="font-size: 0.85rem; line-height: 1.6; color: #7e9bb5; margin-bottom: 1rem;">
          Official high-performance VIP gaming panel for Android and iOS. 100% Anti-Ban protection with drag headshots and smooth aim assist.
        </p>
        <div style="display: flex; gap: 0.75rem;">
          <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(37,211,102,0.15); color: #25D366; display: flex; align-items: center; justify-content: center; text-decoration: none;">
            <i class="fa-brands fa-whatsapp"></i>
          </a>
          <a href="<?= htmlspecialchars($siteConfig['telegram'] ?? 'https://t.me/akashxstore') ?>" target="_blank" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(41,182,246,0.15); color: #29B6F6; display: flex; align-items: center; justify-content: center; text-decoration: none;">
            <i class="fa-brands fa-telegram"></i>
          </a>
        </div>
      </div>

      <!-- Col 2: Fast Links -->
      <div>
        <h4 style="color: #fff; font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;">Navigation</h4>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem;">
          <li><a href="<?= $baseDir ?>/" style="color: var(--text-secondary); text-decoration: none;">Home Storefront</a></li>
          <li><a href="<?= $baseDir ?>/android" style="color: var(--text-secondary); text-decoration: none;">Android VIP APK</a></li>
          <li><a href="<?= $baseDir ?>/ios" style="color: var(--text-secondary); text-decoration: none;">iOS Panel Setup</a></li>
          <li><a href="<?= $baseDir ?>/proofs" style="color: var(--text-secondary); text-decoration: none;">Live Customer Proofs</a></li>
          <li><a href="<?= $baseDir ?>/gameplay" style="color: var(--text-secondary); text-decoration: none;">Gameplay Videos</a></li>
        </ul>
      </div>

      <!-- Col 3: Instant Support & Payment -->
      <div>
        <h4 style="color: #fff; font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;">Direct Payments</h4>
        <p style="font-size: 0.85rem; color: #7e9bb5; margin-bottom: 0.75rem;">Instant activation via UPI QR Code, GPay, PhonePe, Paytm or BHIM.</p>
        <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 10px; padding: 0.75rem; font-size: 0.82rem;">
          <div style="color: #94a3b8; font-size: 0.72rem; text-transform: uppercase;">Official UPI ID</div>
          <div style="color: #fff; font-weight: 700; font-family: monospace; font-size: 0.95rem; margin-top: 2px;">
            <?= htmlspecialchars($siteConfig['upi_id'] ?? 'igakash@fam') ?>
          </div>
        </div>
      </div>

    </div>

    <div style="max-width: 1200px; margin: 0 auto; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; font-size: 0.78rem; color: #5a7a95;">
      <div>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteConfig['site_title']) ?>. All rights reserved. For educational testing only.</div>
      <div style="display: flex; gap: 1rem;">
        <span>Anti-Ban v2.8 Active</span>
        <span>•</span>
        <span>Instant UPI Delivery</span>
      </div>
    </div>
  </footer>

  <!-- Floating WhatsApp & Telegram Badges -->
  <div style="position: fixed; bottom: 20px; right: 20px; z-index: 999; display: flex; flex-direction: column; gap: 10px;">
    <a href="<?= htmlspecialchars($siteConfig['telegram'] ?? 'https://t.me/akashxstore') ?>" target="_blank" aria-label="Join Telegram" style="width: 50px; height: 50px; border-radius: 50%; background: #229ED9; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; text-decoration: none; box-shadow: 0 4px 15px rgba(34, 158, 217, 0.5); transition: transform 0.2s;">
      <i class="fa-brands fa-telegram"></i>
    </a>
    <a href="https://wa.me/<?= $cleanPhone ?>?text=Hello%20AKASH%20X%20STORE,%20I%20want%20to%20buy%20VIP%20Panel" target="_blank" aria-label="Chat on WhatsApp" style="width: 50px; height: 50px; border-radius: 50%; background: #25D366; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; text-decoration: none; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.5); transition: transform 0.2s;">
      <i class="fa-brands fa-whatsapp"></i>
    </a>
  </div>

  <!-- Shared Universal UPI Payment Checkout Modal -->
  <div id="checkoutModal" class="cyber-modal-overlay">
    <div class="cyber-modal">
      <button class="cyber-modal-close" onclick="closeCheckoutModal()">&times;</button>
      
      <div style="text-align: center; margin-bottom: 1.25rem;">
        <span style="font-size: 0.72rem; font-weight: 800; background: rgba(126,200,227,0.15); color: var(--neon-green-bright); padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px;">Instant Payment Checkout</span>
        <h3 id="modalPlanTitle" style="color: #fff; font-size: 1.35rem; margin: 0.5rem 0 0.25rem;">Android VIP (30 Days)</h3>
        <div id="modalPlanPrice" style="font-size: 1.8rem; font-weight: 900; color: var(--neon-green-bright);">₹299</div>
      </div>

      <!-- QR Code Container -->
      <div style="background: #fff; border-radius: 14px; padding: 12px; width: 190px; height: 190px; margin: 0 auto 1.25rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
        <img id="modalQrImg" src="" alt="Scan & Pay UPI QR" style="width: 100%; height: 100%; object-fit: contain;">
      </div>

      <!-- UPI ID Copy Box -->
      <div style="display: flex; align-items: center; justify-content: space-between; background: #060d1a; border: 1px solid var(--border-green); border-radius: 10px; padding: 8px 12px; margin-bottom: 1.25rem;">
        <div style="text-align: left;">
          <div style="font-size: 0.65rem; color: #94a3b8; text-transform: uppercase;">UPI ID</div>
          <div id="modalUpiIdText" style="font-weight: 700; color: #fff; font-family: monospace; font-size: 0.88rem;"><?= htmlspecialchars($siteConfig['upi_id'] ?? 'igakash@fam') ?></div>
        </div>
        <button onclick="copyModalUpi()" style="background: var(--neon-green-bright); color: #060d1a; border: none; border-radius: 6px; padding: 6px 12px; font-weight: 700; font-size: 0.75rem; cursor: pointer;">
          <i class="fa-solid fa-copy"></i> Copy
        </button>
      </div>

      <!-- Direct UPI Intent Link for Mobile -->
      <div style="text-align: center; margin-bottom: 1.25rem;">
        <a id="modalUpiIntentBtn" href="#" class="btn-primary-cyber" style="display: block; width: 100%; padding: 11px; background: #25D366; color: #fff; font-weight: 800; border-radius: 10px; text-decoration: none; font-size: 0.9rem;">
          <i class="fa-solid fa-mobile-screen"></i> Pay Directly via GPay / PhonePe / Paytm
        </a>
      </div>

      <!-- UTR Verification Form -->
      <form id="utrForm" onsubmit="submitOrder(event)" style="background: rgba(13, 25, 48, 0.6); border: 1px solid rgba(126,200,227,0.15); border-radius: 12px; padding: 1rem;">
        <div style="font-size: 0.78rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 5px;">
          <i class="fa-solid fa-receipt" style="color: var(--neon-green-bright);"></i> Step 2: Submit Payment UTR (12-Digit)
        </div>
        <div style="margin-bottom: 0.75rem;">
          <input type="text" id="utrInput" placeholder="Enter 12-Digit UTR / Ref Number" required style="width: 100%; background: #060d1a; border: 1px solid var(--border-green); color: #fff; padding: 10px 12px; border-radius: 8px; font-size: 0.88rem; outline: none;">
        </div>
        <div style="margin-bottom: 0.75rem;">
          <input type="tel" id="phoneInput" placeholder="Your WhatsApp Mobile Number" required style="width: 100%; background: #060d1a; border: 1px solid var(--border-green); color: #fff; padding: 10px 12px; border-radius: 8px; font-size: 0.88rem; outline: none;">
        </div>
        <button type="submit" id="btnSubmitOrder" style="width: 100%; padding: 11px; background: var(--neon-green-bright); color: #060d1a; font-weight: 800; border: none; border-radius: 8px; font-size: 0.9rem; cursor: pointer;">
          Submit & Receive VIP Key
        </button>
      </form>

    </div>
  </div>

  <!-- Global Audio Player Script (VIPXSTORE Logic) -->
  <audio id="vipVoiceAudio" src="<?= htmlspecialchars($siteConfig['voice_url'] ?? '') ?>" preload="none"></audio>

  <script>
    // Universal Voice Player Controller
    const voiceAudio = document.getElementById('vipVoiceAudio');
    let isPlaying = false;

    function toggleVoicePlayer(buttonEl, progressEl) {
      if (!voiceAudio || !voiceAudio.src) return;
      if (voiceAudio.paused) {
        voiceAudio.play();
        isPlaying = true;
        if (buttonEl) buttonEl.innerHTML = '<i class="fa-solid fa-pause"></i>';
      } else {
        voiceAudio.pause();
        isPlaying = false;
        if (buttonEl) buttonEl.innerHTML = '<i class="fa-solid fa-play"></i>';
      }
    }

    if (voiceAudio) {
      voiceAudio.addEventListener('timeupdate', () => {
        const pEls = document.querySelectorAll('.voice-progress');
        if (voiceAudio.duration) {
          const pct = (voiceAudio.currentTime / voiceAudio.duration) * 100;
          pEls.forEach(el => el.style.width = pct + '%');
        }
      });
      voiceAudio.addEventListener('ended', () => {
        isPlaying = false;
        document.querySelectorAll('.voice-play-btn').forEach(b => b.innerHTML = '<i class="fa-solid fa-play"></i>');
        document.querySelectorAll('.voice-progress').forEach(el => el.style.width = '0%');
      });
    }

    // Universal Copy Key Helper
    function copyKey(keyText) {
      navigator.clipboard.writeText(keyText).then(() => {
        alert('Key copied to clipboard: ' + keyText);
      }).catch(() => {
        prompt('Copy your VIP key:', keyText);
      });
    }

    // Checkout Modal Controller
    let currentCheckout = { device: 'Android', duration: '30', price: 299 };

    function openCheckoutModal(device, duration, price) {
      currentCheckout = { device, duration, price };
      document.getElementById('modalPlanTitle').textContent = `${device} VIP (${duration} Days)`;
      document.getElementById('modalPlanPrice').textContent = `₹${price}`;

      const upiId = '<?= htmlspecialchars($siteConfig['upi_id'] ?? 'igakash@fam') ?>';
      const upiName = encodeURIComponent('<?= htmlspecialchars($siteConfig['upi_name'] ?? 'AKASH X STORE') ?>');
      const note = encodeURIComponent(`AKASH VIP ${device} ${duration}D`);
      const upiLink = `upi://pay?pa=${upiId}&pn=${upiName}&am=${price}&cu=INR&tn=${note}`;

      // Dynamic QR Generator via qrserver API
      const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(upiLink)}`;
      document.getElementById('modalQrImg').src = qrUrl;
      document.getElementById('modalUpiIntentBtn').href = upiLink;

      document.getElementById('checkoutModal').classList.add('active');
    }

    function closeCheckoutModal() {
      document.getElementById('checkoutModal').classList.remove('active');
    }

    function copyModalUpi() {
      const upi = document.getElementById('modalUpiIdText').textContent.trim();
      navigator.clipboard.writeText(upi).then(() => {
        alert('UPI ID copied: ' + upi);
      });
    }

    async function submitOrder(e) {
      e.preventDefault();
      const utr = document.getElementById('utrInput').value.trim();
      const phone = document.getElementById('phoneInput').value.trim();
      const btn = document.getElementById('btnSubmitOrder');

      if (!utr || !phone) {
        alert('Please provide both UTR and WhatsApp Number');
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Verifying Order...';

      try {
        const res = await fetch('<?= $baseDir ?>/api/orders', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            device: currentCheckout.device,
            duration: currentCheckout.duration,
            price: currentCheckout.price,
            utr: utr,
            phone: phone
          })
        });
        const data = await res.json();
        if (data.success) {
          alert('Order submitted successfully! Order ID: ' + (data.order?.id || 'SUCCESS') + '\nWe will verify your UTR and activate your key immediately.');
          closeCheckoutModal();
          document.getElementById('utrForm').reset();
          // Also optionally open WhatsApp with proof details
          const waMsg = encodeURIComponent(`Hello AKASH X STORE, I have paid ₹${currentCheckout.price} for ${currentCheckout.device} VIP (${currentCheckout.duration} Days).\nMy UTR is: ${utr}\nMy Phone: ${phone}`);
          window.open(`https://wa.me/<?= $cleanPhone ?>?text=${waMsg}`, '_blank');
        } else {
          alert('Error: ' + (data.message || 'Failed to submit order'));
        }
      } catch (err) {
        alert('Order submitted! Please send screenshot of payment to our WhatsApp support.');
        closeCheckoutModal();
      } finally {
        btn.disabled = false;
        btn.textContent = 'Submit & Receive VIP Key';
      }
    }
  </script>

</body>
</html>
