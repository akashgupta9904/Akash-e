/**
 * VIP X STORE — Auto Delivery
 * Payment success ke baad: showDeliveryBox(orderNumber) call karo.
 * checkout.js me UTR-submit success wale point par 1 line lagti hai:
 *     if (window.showDeliveryBox) showDeliveryBox(orderNumber);
 */
(function () {
  'use strict';

  var BASE = (typeof window.BASE_URL !== 'undefined') ? window.BASE_URL : '';

  function el(id) { return document.getElementById(id); }

  function openModal(id) {
    el(id).classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  window.closeDeliveryBox = function () {
    el('deliveryModal').classList.remove('open');
    document.body.style.overflow = '';
  };

  function linkRow(item, isZip) {
    var a = document.createElement('a');
    a.href = item.url;
    a.style.cssText = 'display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:12px;' +
      'background:rgba(126,200,227,0.08);border:1px solid var(--border-green);' +
      'color:var(--text-primary);font-weight:600;font-size:0.88rem;text-decoration:none;';
    var icon = isZip
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="var(--neon-green-bright)" stroke-width="2" width="20" height="20" style="flex-shrink:0"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>'
      : (item.type === 'video'
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="var(--neon-green-bright)" stroke-width="2" width="20" height="20" style="flex-shrink:0"><polygon points="5 3 19 12 5 21 5 3"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="var(--neon-green-bright)" stroke-width="2" width="20" height="20" style="flex-shrink:0"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>');
    var label = '<span style="flex:1;">' + escapeHtml(item.name) + '</span>' +
      '<span style="font-size:0.72rem;color:var(--neon-green-bright);font-weight:700;">DOWNLOAD</span>';
    a.innerHTML = icon + label;
    return a;
  }

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s == null ? '' : String(s);
    return d.innerHTML;
  }

  window.showDeliveryBox = function (orderNumber) {
    if (!orderNumber) return;
    fetch(BASE + '/api/get-delivery.php?order=' + encodeURIComponent(orderNumber))
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.ok) {
          if (data.pending) {
            if (data.message) el('dlvPendingMsg').textContent = data.message;
            var ordEl = el('dlvPendingOrderNo');
            if (ordEl) ordEl.textContent = orderNumber;
            window.__lastPendingOrder = orderNumber;
            openModal('deliveryPendingModal');
          } else if (data.message) {
            alert(data.message);
          }
          return;
        }

        // Payment successful — is device par support turant unlock
        // (footer ka lock interceptor is flag ko dekhta hai)
        window.VXS_SUPPORT_UNLOCKED = true;

        el('dlvOrderNo').textContent = data.order_number;

        // License
        if (data.license_key) {
          el('dlvLicense').textContent = data.license_key;
          el('dlvLicenseWrap').style.display = 'block';
        } else {
          el('dlvLicenseWrap').style.display = 'none';
        }

        // ZIPs
        var zipList = el('dlvZipList'); zipList.innerHTML = '';
        (data.zips || []).forEach(function (z) { zipList.appendChild(linkRow(z, true)); });
        el('dlvZipSection').style.display = (data.zips && data.zips.length) ? 'block' : 'none';

        // Guides
        var gList = el('dlvGuideList'); gList.innerHTML = '';
        (data.guides || []).forEach(function (g) { gList.appendChild(linkRow(g, false)); });
        el('dlvGuideSection').style.display = (data.guides && data.guides.length) ? 'block' : 'none';

        // Note
        if (data.note) {
          el('dlvNote').textContent = data.note;
          el('dlvNote').style.display = 'block';
        } else {
          el('dlvNote').style.display = 'none';
        }

        // Support activated
        var hasSupport = false;
        if (data.support && data.support.whatsapp) {
          el('dlvWhatsapp').href = data.support.whatsapp;
          el('dlvWhatsapp').style.display = 'block';
          hasSupport = true;
        }
        if (data.support && data.support.telegram) {
          el('dlvTelegram').href = data.support.telegram;
          el('dlvTelegram').style.display = 'block';
          hasSupport = true;
        }
        el('dlvSupportSection').style.display = hasSupport ? 'block' : 'none';

        el('deliveryPendingModal').classList.remove('open');
        openModal('deliveryModal');
      })
      .catch(function () { /* network error: silently ignore */ });
  };

  // Page reload / link se wapas aane par (?order=HBX...) box dobara khol do
  document.addEventListener('DOMContentLoaded', function () {
    var params = new URLSearchParams(window.location.search);
    var ord = params.get('order');
    if (ord) window.showDeliveryBox(ord);

    // "Check Status" — admin verify karne ke baad customer khud unlock kar sakta hai
    var chk = el('dlvCheckStatusBtn');
    if (chk) chk.addEventListener('click', function () {
      if (window.__lastPendingOrder) window.showDeliveryBox(window.__lastPendingOrder);
    });
  });
})();