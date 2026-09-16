<?php
require_once __DIR__ . '/header.php';
$videos = $siteConfig['videos'] ?? [];
?>

<main class="gameplay-page" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem;">

  <div style="text-align: center; margin-bottom: 2.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,0,80,0.15); border: 1px solid rgba(255,0,80,0.4); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: #ff3366; margin-bottom: 1rem;">
      <i class="fa-solid fa-play"></i> OFFICIAL GAMEPLAY DEMOS &amp; HEADSHOT HIGHLIGHTS
    </div>
    <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem;">
      Live Gameplay Showcase
    </h1>
    <p style="font-size: 1rem; color: var(--text-secondary); max-width: 650px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Watch real matches recorded directly on Android and iOS devices showing instant auto-aim lock, 99% drag headshots, and safe anti-ban bypass.
    </p>

    <!-- Filter Buttons -->
    <div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
      <button class="filter-vid-btn active" onclick="filterVideos('all', this)" style="padding: 8px 18px; border-radius: 20px; background: var(--neon-green-bright); color: #060d1a; font-weight: 800; border: none; cursor: pointer; font-size: 0.85rem;">All Videos</button>
      <button class="filter-vid-btn" onclick="filterVideos('Android', this)" style="padding: 8px 18px; border-radius: 20px; background: rgba(13,25,48,0.8); color: #fff; border: 1px solid var(--border-green); font-weight: 700; cursor: pointer; font-size: 0.85rem;">Android</button>
      <button class="filter-vid-btn" onclick="filterVideos('iOS', this)" style="padding: 8px 18px; border-radius: 20px; background: rgba(13,25,48,0.8); color: #fff; border: 1px solid var(--border-green); font-weight: 700; cursor: pointer; font-size: 0.85rem;">iOS</button>
    </div>
  </div>

  <!-- Videos Grid -->
  <div id="videosGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <?php foreach ($videos as $v): ?>
    <div class="video-card" data-type="<?= htmlspecialchars($v['type'] ?? 'All') ?>" style="background: rgba(13, 25, 48, 0.85); border: 1px solid var(--border-green); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
      <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000;">
        <iframe src="<?= htmlspecialchars($v['url']) ?>" title="<?= htmlspecialchars($v['title']) ?>" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;" allowfullscreen></iframe>
      </div>
      <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
          <span style="font-size: 0.72rem; font-weight: 800; background: rgba(126,200,227,0.15); color: var(--neon-green-bright); padding: 2px 8px; border-radius: 6px;">
            <?= htmlspecialchars($v['type']) ?>
          </span>
          <span style="font-size: 0.75rem; color: #94a3b8;">
            <i class="fa-regular fa-clock" style="margin-right: 3px;"></i> <?= htmlspecialchars($v['duration']) ?> • <i class="fa-regular fa-eye" style="margin-left: 5px; margin-right: 3px;"></i> <?= htmlspecialchars($v['views']) ?>
          </span>
        </div>
        <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 1rem; line-height: 1.4;">
          <?= htmlspecialchars($v['title']) ?>
        </h3>
        <button onclick="openCheckoutModal('<?= htmlspecialchars($v['type'] === 'iOS' ? 'iOS' : 'Android') ?>', '30', '<?= $siteConfig['prices']['30'] ?? 299 ?>')" style="margin-top: auto; width: 100%; padding: 10px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 0.85rem;">
          Get VIP Key for this Panel
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</main>

<script>
  function filterVideos(type, btn) {
    document.querySelectorAll('.filter-vid-btn').forEach(b => {
      b.style.background = 'rgba(13,25,48,0.8)';
      b.style.color = '#fff';
      b.style.border = '1px solid var(--border-green)';
    });
    btn.style.background = 'var(--neon-green-bright)';
    btn.style.color = '#060d1a';
    btn.style.border = 'none';

    const cards = document.querySelectorAll('.video-card');
    cards.forEach(card => {
      const cardType = card.getAttribute('data-type').toLowerCase();
      if (type === 'all' || cardType === type.toLowerCase() || cardType === 'all') {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
