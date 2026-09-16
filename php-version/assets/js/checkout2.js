/**
 * VIP X STORE — Checkout Flow (Auto UPI QR + Screenshot manual)
 * Location: assets/js/checkout2.js
 *
 * Flow:
 *  Buy Now -> api/create-order.php -> payment modal (auto QR from UPI+amount, upload, confirm — sab ek screen)
 *  -> Upload Screenshot -> Confirm Payment -> api/submit-utr.php (screenshot upload, order paid)
 *  -> "Loading..." (timer backend se) -> download box turant unlock [delivery.js]
 */
(function () {
  'use strict';

  var BASE = (typeof window.BASE_URL !== 'undefined') ? window.BASE_URL : '';
  var currentOrder = null;
  var pickedFile = null;

  function el(id) { return document.getElementById(id); }

  function openModal() {
    el('paymentModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  window.closePaymentModal = function () {
    el('paymentModal').classList.remove('open');
    document.body.style.overflow = '';
    showStep(1);
  };

  function showStep(n) {
    el('payStep1').style.display = n === 1 ? 'block' : 'none';
    el('payStep3').style.display = n === 3 ? 'block' : 'none';
    var errEl = el('payError');
    if (errEl) errEl.style.display = 'none';
  }

  function setError(msg) {
    var e = el('payError');
    e.textContent = msg;
    e.style.display = 'block';
  }

  // ── Buy Now buttons ──────────────────────────────
  document.addEventListener('click', function (ev) {
    var btn = ev.target.closest('.buy-now-btn');
    if (!btn) return;
    ev.preventDefault();

    var panelId = btn.dataset.panelId;
    var planId  = btn.dataset.planId;
    if (!panelId || !planId) return;

    btn.disabled = true;
    var oldText = btn.textContent;
    btn.textContent = 'Please wait...';

    var body = new URLSearchParams();
    body.append('panel_id', panelId);
    body.append('plan_id', planId);

    fetch(BASE + '/api/create-order.php', { method: 'POST', body: body })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        btn.disabled = false;
        btn.textContent = oldText;
        if (!data.ok) { alert(data.message || 'Something went wrong.'); return; }

        currentOrder = data;
        pickedFile = null;

        el('payPlanTitle').textContent = data.plan_title;
        el('payPanelName').textContent = data.panel_name;

        // Validity (duration) show
        if (data.duration_label) {
          el('payDuration').textContent = data.duration_label;
          el('payDurationWrap').style.display = 'block';
        } else {
          el('payDurationWrap').style.display = 'none';
        }

        // QR auto-generate: UPI + amount wale upi_uri se
        var qrImg = el('payQrImg');
        var qrMiss = el('payQrMissing');
        if (data.upi_uri) {
          qrImg.style.display = 'inline-block';
          qrMiss.style.display = 'none';
          qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=340x340&data='
            + encodeURIComponent(data.upi_uri);
        } else {
          qrImg.style.display = 'none';
          qrMiss.style.display = 'block';
        }

        // Screenshot picker reset
        el('payScreenshotInput').value = '';
        el('payFileName').style.display = 'none';
        el('payFilePreview').style.display = 'none';

        showStep(1);
        openModal();
      })
      .catch(function () {
        btn.disabled = false;
        btn.textContent = oldText;
        alert('Network error. Please try again.');
      });
  });

  document.addEventListener('DOMContentLoaded', function () {

    // ── Screenshot picker ──────────────────────────
    el('payPickFileBtn') && el('payPickFileBtn').addEventListener('click', function () {
      el('payScreenshotInput').click();
    });

    el('payScreenshotInput') && el('payScreenshotInput').addEventListener('change', function () {
      var f = this.files && this.files[0];
      if (!f) { pickedFile = null; return; }
      pickedFile = f;
      el('payFileName').textContent = f.name;
      el('payFileName').style.display = 'block';
      var prev = el('payFilePreview');
      if (f.type && f.type.indexOf('image/') === 0) {
        prev.src = URL.createObjectURL(f);
        prev.style.display = 'block';
      } else {
        prev.style.display = 'none';
      }
      var e = el('payError'); if (e) e.style.display = 'none';
    });

    // ── Confirm Payment → upload screenshot → Loading... ──
    el('paySubmitBtn') && el('paySubmitBtn').addEventListener('click', function () {
      if (!currentOrder) return;
      if (!pickedFile) {
        setError('Please upload your payment screenshot first.');
        return;
      }

      var self = this;
      self.disabled = true;
      self.textContent = 'Uploading...';

      var fd = new FormData();
      fd.append('order', currentOrder.order_number);
      fd.append('screenshot', pickedFile);

      fetch(BASE + '/api/submit-utr.php', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          self.disabled = false;
          self.textContent = 'Confirm Payment';
          if (!data.ok) { setError(data.message || 'Submission failed.'); return; }

          var orderNo = data.order_number;
          // Timer backend se (seconds). Loading me sirf spinner, number nahi dikhta.
          var waitSec = parseInt(data.loading_seconds, 10);
          if (isNaN(waitSec) || waitSec < 0) waitSec = 0;

          showStep(3); // Loading...

          setTimeout(function () {
            // Timer khatam → download box turant (order pehle hi paid ho chuka)
            window.closePaymentModal();
            if (window.showDeliveryBox) window.showDeliveryBox(orderNo);
          }, waitSec * 1000);
        })
        .catch(function () {
          self.disabled = false;
          self.textContent = 'Confirm Payment';
          setError('Network error. Please try again.');
        });
    });
  });

  // Loading (step 3) ke time overlay tap se close na ho
  document.addEventListener('DOMContentLoaded', function () {
    var pm = el('paymentModal');
    pm && pm.addEventListener('click', function (e) {
      if (e.target !== this) return;
      if (el('payStep3') && el('payStep3').style.display === 'block') return; // loading me close block
      window.closePaymentModal();
    });
  });
})();