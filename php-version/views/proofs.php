<?php
require_once __DIR__ . '/header.php';
$proofs = get_proofs();
?>

<main class="proofs-page" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem;">

  <div style="text-align: center; margin-bottom: 2.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: var(--neon-green-bright); margin-bottom: 1rem;">
      <i class="fa-solid fa-camera-retro"></i> 100% VERIFIED LIVE CUSTOMER PROOFS
    </div>
    <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem;">
      Rank Push &amp; Headshot Proofs
    </h1>
    <p style="font-size: 1rem; color: var(--text-secondary); max-width: 650px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Real match screenshots from players who reached Grandmaster and Heroic using AKASH X STORE VIP Panel without any blacklist or ban.
    </p>

    <!-- Filter Buttons -->
    <div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
      <button class="filter-btn active" onclick="filterProofs('all', this)" style="padding: 8px 18px; border-radius: 20px; background: var(--neon-green-bright); color: #060d1a; font-weight: 800; border: none; cursor: pointer; font-size: 0.85rem;">All Proofs</button>
      <button class="filter-btn" onclick="filterProofs('Android', this)" style="padding: 8px 18px; border-radius: 20px; background: rgba(13,25,48,0.8); color: #fff; border: 1px solid var(--border-green); font-weight: 700; cursor: pointer; font-size: 0.85rem;">Android</button>
      <button class="filter-btn" onclick="filterProofs('iOS', this)" style="padding: 8px 18px; border-radius: 20px; background: rgba(13,25,48,0.8); color: #fff; border: 1px solid var(--border-green); font-weight: 700; cursor: pointer; font-size: 0.85rem;">iOS</button>
    </div>
  </div>

  <!-- Proofs Grid -->
  <div id="proofsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <?php foreach ($proofs as $p): ?>
    <div class="proof-card" data-type="<?= htmlspecialchars($p['type'] ?? 'Android') ?>" style="background: rgba(13, 25, 48, 0.85); border: 1px solid var(--border-green); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s;">
      <div style="position: relative; overflow: hidden; cursor: pointer;" onclick="openProofZoom('<?= htmlspecialchars($p['image_url']) ?>', '<?= htmlspecialchars($p['title']) ?>')">
        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="Proof" style="width: 100%; height: 230px; object-fit: cover; background: #081020; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
        <span style="position: absolute; top: 12px; left: 12px; background: rgba(6,13,26,0.85); backdrop-filter: blur(8px); border: 1px solid var(--border-green); color: var(--neon-green-bright); font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
          <?= htmlspecialchars($p['tag'] ?? 'Anti-Ban Verified') ?>
        </span>
      </div>
      <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
        <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; line-height: 1.4;">
          <?= htmlspecialchars($p['title']) ?>
        </h3>
        <?php if (!empty($p['description'])): ?>
        <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.75rem; flex: 1;">
          <?= htmlspecialchars($p['description']) ?>
        </p>
        <?php endif; ?>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #5a7a95; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 0.75rem; margin-top: auto;">
          <span><i class="fa-solid fa-gamepad" style="margin-right: 4px;"></i> <?= htmlspecialchars($p['type'] ?? 'Android') ?></span>
          <span><?= htmlspecialchars(substr($p['created_at'] ?? date('Y-m-d'), 0, 10)) ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Telegram Join Banner -->
  <div style="background: linear-gradient(135deg, rgba(34,158,217,0.2) 0%, rgba(13,25,48,0.8) 100%); border: 1px solid rgba(34,158,217,0.4); border-radius: 18px; padding: 2rem; text-align: center;">
    <h3 style="color: #fff; font-size: 1.4rem; font-weight: 900; margin-bottom: 0.5rem;">Join Our Official Telegram Community</h3>
    <p style="color: var(--text-secondary); font-size: 0.92rem; max-width: 550px; margin: 0 auto 1.25rem;">
      Over 10,000+ members post daily rank push screenshots, tournament results, and gameplay proofs in our channel.
    </p>
    <a href="<?= htmlspecialchars($siteConfig['telegram'] ?? 'https://t.me/akashxstore') ?>" target="_blank" style="padding: 12px 26px; background: #229ED9; color: #fff; font-weight: 800; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
      <i class="fa-brands fa-telegram" style="font-size: 1.2rem;"></i> Join Telegram Channel
    </a>
  </div>

  <!-- Proof Zoom Modal -->
  <div id="proofZoomModal" class="cyber-modal-overlay" onclick="closeProofZoom()">
    <div style="max-width: 900px; width: 95%; max-height: 90vh; text-align: center; position: relative;" onclick="event.stopPropagation()">
      <button onclick="closeProofZoom()" style="position: absolute; top: -40px; right: 0; background: none; border: none; color: #fff; font-size: 2rem; cursor: pointer;">&times;</button>
      <img id="zoomModalImg" src="" alt="Zoomed Proof" style="width: 100%; max-height: 80vh; object-fit: contain; border-radius: 14px; border: 1px solid var(--border-green); background: #000;">
      <div id="zoomModalTitle" style="color: #fff; font-weight: 700; font-size: 1rem; margin-top: 0.75rem;"></div>
    </div>
  </div>

</main>

<script>
  function filterProofs(type, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => {
      b.style.background = 'rgba(13,25,48,0.8)';
      b.style.color = '#fff';
      b.style.border = '1px solid var(--border-green)';
    });
    btn.style.background = 'var(--neon-green-bright)';
    btn.style.color = '#060d1a';
    btn.style.border = 'none';

    const cards = document.querySelectorAll('.proof-card');
    cards.forEach(card => {
      if (type === 'all' || card.getAttribute('data-type').toLowerCase() === type.toLowerCase()) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function openProofZoom(imgSrc, title) {
    document.getElementById('zoomModalImg').src = imgSrc;
    document.getElementById('zoomModalTitle').textContent = title;
    document.getElementById('proofZoomModal').classList.add('active');
  }

  function closeProofZoom() {
    document.getElementById('proofZoomModal').classList.remove('active');
  }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
