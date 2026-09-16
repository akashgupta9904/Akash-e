<?php
require_once __DIR__ . '/header.php';
$prices = $siteConfig['prices'] ?? ['1' => 80, '15' => 150, '30' => 299, '90' => 599];
?>

<main class="ios-page" style="max-width: 1100px; margin: 0 auto; padding: 2rem 1.25rem;">

  <!-- Hero Header -->
  <div style="text-align: center; margin-bottom: 2.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.3); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: #fff; margin-bottom: 1rem;">
      <i class="fa-brands fa-apple"></i> APPLE IOS OFFICIAL VIP PROFILE <?= htmlspecialchars($productConfig['version'] ?? 'v2.8') ?>
    </div>
    <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem;">
      iPhone &amp; iPad iOS VIP Panel
    </h1>
    <p style="font-size: 1rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Zero jailbreak required. Native iOS 15, 16, 17 &amp; 18 support via MobileConfig DNS and certificates. 100% Anti-Blacklist.
    </p>

    <!-- Voice Note Audio Player -->
    <?php if (!empty($siteConfig['voice_url'])): ?>
    <div style="max-width: 580px; margin: 0 auto 1.5rem;">
      <div class="voice-player-card">
        <button type="button" class="voice-play-btn" onclick="toggleVoicePlayer(this, this.nextElementSibling)">
          <i class="fa-solid fa-play"></i>
        </button>
        <div class="voice-info">
          <div class="voice-title">
            <span>Admin Voice Guide for iOS Setup</span>
            <span class="voice-badge">IMPORTANT</span>
          </div>
          <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Listen before installing DNS configuration profile</div>
          <div class="voice-waveform" onclick="seekVoiceAudio(event)">
            <div class="voice-progress"></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Universal Key Banner -->
    <?php if (!empty($productConfig['universal_key'])): ?>
    <div style="max-width: 580px; margin: 0 auto 2rem;">
      <div class="universal-key-banner">
        <div style="display: flex; align-items: center; gap: 10px;">
          <i class="fa-solid fa-key" style="color: var(--neon-green-bright); font-size: 1.2rem;"></i>
          <div style="text-align: left;">
            <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase;">iOS Universal Key</div>
            <div style="font-weight: 700; color: #fff; font-size: 0.88rem;">Use for panel profile activation</div>
          </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span class="key-badge"><?= htmlspecialchars($productConfig['universal_key']) ?></span>
          <button onclick="copyKey('<?= htmlspecialchars($productConfig['universal_key']) ?>')" style="background: var(--neon-green-bright); color: #060d1a; border: none; border-radius: 8px; padding: 7px 12px; font-weight: 800; font-size: 0.78rem; cursor: pointer;">
            Copy
          </button>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Download / Setup Action -->
    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
      <a href="<?= htmlspecialchars($productConfig['ios_link'] ?? '#') ?>" target="_blank" style="padding: 14px 28px; background: #fff; color: #060d1a; font-weight: 900; font-size: 1rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 20px rgba(255,255,255,0.3);">
        <i class="fa-brands fa-apple"></i> Open iOS Setup Profile
      </a>
      <button onclick="openCheckoutModal('iOS', '30', '<?= $prices['30'] ?? 299 ?>')" style="padding: 14px 24px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; font-size: 1rem; border-radius: 12px; cursor: pointer;">
        <i class="fa-solid fa-cart-shopping" style="margin-right: 6px;"></i> Buy Key (₹<?= $prices['30'] ?? 299 ?>)
      </button>
    </div>
  </div>

  <!-- Features Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 14px; padding: 1.5rem;">
      <div style="color: #fff; font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-lock-open"></i></div>
      <h3 style="font-size: 1.1rem; color: #fff; font-weight: 800; margin-bottom: 0.5rem;">No Jailbreak Needed</h3>
      <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">Install seamlessly using Safari configuration profiles without modifying iOS core firmware.</p>
    </div>
    <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 14px; padding: 1.5rem;">
      <div style="color: var(--neon-green-bright); font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-network-wired"></i></div>
      <h3 style="font-size: 1.1rem; color: #fff; font-weight: 800; margin-bottom: 0.5rem;">Anti-Ban DNS Filter</h3>
      <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">Blocks telemetry servers and server-side log auditing so you stay undetected forever.</p>
    </div>
    <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 14px; padding: 1.5rem;">
      <div style="color: #FFB800; font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-crosshairs"></i></div>
      <h3 style="font-size: 1.1rem; color: #fff; font-weight: 800; margin-bottom: 0.5rem;">Smooth 120Hz Aim Tracking</h3>
      <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">ProMotion 120Hz display optimization for buttery smooth camera movement and instant headshots.</p>
    </div>
  </div>

  <!-- iOS Pricing Options -->
  <div style="margin-bottom: 3rem;">
    <h2 style="text-align: center; color: #fff; font-size: 1.7rem; font-weight: 900; margin-bottom: 1.5rem;">Select Your iOS VIP Duration</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
      <?php
      $plans = [
        ['days' => '1', 'name' => '1 Day Trial', 'price' => $prices['1'] ?? 80],
        ['days' => '15', 'name' => '15 Days VIP', 'price' => $prices['15'] ?? 150],
        ['days' => '30', 'name' => '30 Days Monthly', 'price' => $prices['30'] ?? 299, 'badge' => 'POPULAR'],
        ['days' => '90', 'name' => '90 Days Season', 'price' => $prices['90'] ?? 599]
      ];
      foreach ($plans as $pl):
      ?>
      <div style="background: rgba(13, 25, 48, 0.8); border: 1px solid <?= isset($pl['badge']) ? 'var(--neon-green-bright)' : 'var(--border-green)' ?>; border-radius: 14px; padding: 1.25rem; text-align: center;">
        <?php if (isset($pl['badge'])): ?>
          <span style="background: var(--neon-green-bright); color: #060d1a; font-weight: 800; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px;"><?= $pl['badge'] ?></span>
        <?php endif; ?>
        <h4 style="color: #fff; margin: 0.5rem 0; font-size: 1.1rem; font-weight: 800;"><?= $pl['name'] ?></h4>
        <div style="font-size: 1.8rem; font-weight: 900; color: #fff; margin-bottom: 1rem;">₹<?= $pl['price'] ?></div>
        <button onclick="openCheckoutModal('iOS', '<?= $pl['days'] ?>', '<?= $pl['price'] ?>')" style="width: 100%; padding: 10px; background: #fff; color: #060d1a; font-weight: 900; border: none; border-radius: 8px; cursor: pointer;">
          Pay via UPI
        </button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- iOS Step-by-Step Installation Guide -->
  <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 16px; padding: 2rem;">
    <h3 style="color: #fff; font-size: 1.3rem; font-weight: 800; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
      <i class="fa-brands fa-apple" style="color: #fff;"></i> iOS Step-by-Step Setup Guide
    </h3>
    <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.9rem; color: var(--text-secondary);">
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #fff; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">1</span>
        <div>Open Safari browser and tap the <strong>Open iOS Setup Profile</strong> button.</div>
      </div>
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #fff; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">2</span>
        <div>Go to iPhone <strong>Settings &gt; Profile Downloaded</strong> and tap <strong>Install</strong>.</div>
      </div>
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #fff; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">3</span>
        <div>Activate DNS Bypass with your key (or Universal Key <code><?= htmlspecialchars($productConfig['universal_key']) ?></code>).</div>
      </div>
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #fff; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">4</span>
        <div>Launch the game and enjoy 100% headshot accuracy with zero ban risk!</div>
      </div>
    </div>
  </div>

</main>

<?php require_once __DIR__ . '/footer.php'; ?>
