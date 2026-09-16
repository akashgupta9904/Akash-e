<?php
require_once __DIR__ . '/header.php';
$prices = $siteConfig['prices'] ?? ['1' => 80, '15' => 150, '30' => 299, '90' => 599];
$videos = array_slice($siteConfig['videos'] ?? [], 0, 4);
$reviews = $siteConfig['reviews'] ?? [];
$proofs = array_slice(get_proofs(), 0, 4);
?>

<main class="home-page" style="max-width: 1200px; margin: 0 auto; padding: 1.5rem 1.25rem;">

  <!-- Hero Section -->
  <section class="hero-section" style="text-align: center; padding: 2.5rem 0 1.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(126,200,227,0.12); border: 1px solid var(--border-green); padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; color: var(--neon-green-bright); margin-bottom: 1.25rem;">
      <i class="fa-solid fa-shield-halved"></i> SEASON 43 ANTI-BAN BYPASS v2.8
    </div>

    <h1 style="font-size: clamp(2rem, 5vw, 3.4rem); font-weight: 900; line-height: 1.15; color: #fff; margin-bottom: 1rem; letter-spacing: -0.5px;">
      ELITE VIP GAMING PANEL<br>
      <span style="background: linear-gradient(90deg, var(--neon-green-bright), #25D366); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">FOR ANDROID &amp; IOS</span>
    </h1>

    <p style="font-size: clamp(0.95rem, 2vw, 1.12rem); color: var(--text-secondary); max-width: 650px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Dominate every Free Fire &amp; BGMI match with 99% Headshot accuracy, location ESP, and safe main ID anti-ban bypass. Instant activation.
    </p>

    <!-- Voice Note Audio Player (VIPXSTORE Logic) -->
    <?php if (!empty($siteConfig['voice_url'])): ?>
    <div style="max-width: 600px; margin: 0 auto 1.5rem;">
      <div class="voice-player-card">
        <button type="button" class="voice-play-btn" onclick="toggleVoicePlayer(this, this.nextElementSibling)">
          <i class="fa-solid fa-play"></i>
        </button>
        <div class="voice-info">
          <div class="voice-title">
            <span>Admin Voice Guide</span>
            <span class="voice-badge">IMPORTANT</span>
          </div>
          <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Listen to voice note before buying VIP access</div>
          <div class="voice-waveform" onclick="seekVoiceAudio(event)">
            <div class="voice-progress"></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Universal Key Banner (VIPXSTORE Logic) -->
    <?php if (!empty($productConfig['universal_key'])): ?>
    <div style="max-width: 600px; margin: 0 auto 2rem;">
      <div class="universal-key-banner">
        <div style="display: flex; align-items: center; gap: 10px;">
          <i class="fa-solid fa-key" style="color: var(--neon-green-bright); font-size: 1.2rem;"></i>
          <div style="text-align: left;">
            <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase;">Universal Access Key</div>
            <div style="font-weight: 700; color: #fff; font-size: 0.88rem;">Valid for all registered users</div>
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

    <!-- Device Selection Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 750px; margin: 0 auto 2.5rem;">
      
      <!-- Android Card -->
      <div style="background: rgba(13, 25, 48, 0.85); border: 1px solid rgba(61, 220, 132, 0.4); border-radius: 18px; padding: 1.5rem; text-align: left; position: relative; overflow: hidden;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
          <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(61, 220, 132, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #3DDC84;">
            <i class="fa-brands fa-android"></i>
          </div>
          <span style="font-size: 0.72rem; font-weight: 800; background: rgba(61, 220, 132, 0.2); color: #3DDC84; padding: 3px 8px; border-radius: 6px;">Android 9 - 15</span>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">Android VIP APK</h3>
        <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.25rem;">
          Drag Headshot 99%, Auto Aim-Lock, ESP Lines, High Damage, Safe Main ID.
        </p>
        <div style="display: flex; gap: 0.75rem;">
          <a href="<?= $baseDir ?>/android" style="flex: 1; text-align: center; padding: 10px; background: #3DDC84; color: #060d1a; font-weight: 800; border-radius: 10px; text-decoration: none; font-size: 0.85rem;">
            Explore Android
          </a>
          <button onclick="openCheckoutModal('Android', '30', '<?= $prices['30'] ?? 299 ?>')" style="flex: 1; padding: 10px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 700; border-radius: 10px; cursor: pointer; font-size: 0.85rem;">
            Buy ₹<?= $prices['30'] ?? 299 ?>
          </button>
        </div>
      </div>

      <!-- iOS Card -->
      <div style="background: rgba(13, 25, 48, 0.85); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 18px; padding: 1.5rem; text-align: left; position: relative; overflow: hidden;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
          <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff;">
            <i class="fa-brands fa-apple"></i>
          </div>
          <span style="font-size: 0.72rem; font-weight: 800; background: rgba(255, 255, 255, 0.15); color: #fff; padding: 3px 8px; border-radius: 6px;">iOS 14 - 18</span>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">iOS VIP Panel</h3>
        <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.25rem;">
          No Jailbreak needed, DNS &amp; Profile setup, Anti-Blacklist, Smooth aim tracking.
        </p>
        <div style="display: flex; gap: 0.75rem;">
          <a href="<?= $baseDir ?>/ios" style="flex: 1; text-align: center; padding: 10px; background: #fff; color: #060d1a; font-weight: 800; border-radius: 10px; text-decoration: none; font-size: 0.85rem;">
            Explore iOS
          </a>
          <button onclick="openCheckoutModal('iOS', '30', '<?= $prices['30'] ?? 299 ?>')" style="flex: 1; padding: 10px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 700; border-radius: 10px; cursor: pointer; font-size: 0.85rem;">
            Buy ₹<?= $prices['30'] ?? 299 ?>
          </button>
        </div>
      </div>

    </div>

  </section>

  <!-- Pricing Plans Section -->
  <section class="pricing-section" style="padding: 2.5rem 0;">
    <div style="text-align: center; margin-bottom: 2rem;">
      <span style="font-size: 0.75rem; font-weight: 800; color: var(--neon-green-bright); text-transform: uppercase; letter-spacing: 1.5px;">Transparent Pricing</span>
      <h2 style="font-size: 1.9rem; font-weight: 900; color: #fff; margin-top: 0.25rem;">Choose Your VIP Plan</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
      
      <!-- 1 Day Trial -->
      <div style="background: rgba(13, 25, 48, 0.75); border: 1px solid var(--border-green); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column;">
        <div style="color: #94a3b8; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">1 Day Trial</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">
          ₹<?= $prices['1'] ?? 80 ?>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary); flex: 1;">
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Full VIP Feature Access</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> 99% Drag Headshots</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Safe Anti-Ban v2.8</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Instant Delivery</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '1', '<?= $prices['1'] ?? 80 ?>')" style="width: 100%; padding: 11px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 10px; cursor: pointer;">
          Buy 1 Day
        </button>
      </div>

      <!-- 15 Days -->
      <div style="background: rgba(13, 25, 48, 0.75); border: 1px solid var(--border-green); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column;">
        <div style="color: #94a3b8; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">15 Days VIP</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">
          ₹<?= $prices['15'] ?? 150 ?>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary); flex: 1;">
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Full VIP Features</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Anti-Ban Bypass Active</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Rank Push Support</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> WhatsApp Support</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '15', '<?= $prices['15'] ?? 150 ?>')" style="width: 100%; padding: 11px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 10px; cursor: pointer;">
          Buy 15 Days
        </button>
      </div>

      <!-- 30 Days (Most Popular) -->
      <div style="background: linear-gradient(135deg, rgba(17, 36, 70, 0.95), rgba(9, 18, 36, 0.95)); border: 2px solid var(--neon-green-bright); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; position: relative; box-shadow: 0 0 25px rgba(126,200,227,0.2);">
        <div style="position: absolute; top: -11px; right: 18px; background: var(--neon-green-bright); color: #060d1a; font-weight: 800; font-size: 0.68rem; padding: 2px 10px; border-radius: 10px; text-transform: uppercase;">
          Best Seller
        </div>
        <div style="color: var(--neon-green-bright); font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">30 Days Monthly</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">
          ₹<?= $prices['30'] ?? 299 ?>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: #e2e8f0; flex: 1;">
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> 30 Days Full Access</li>
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Grandmaster Push Safe</li>
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Free Updates During Plan</li>
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> 24/7 VIP Priority Support</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '30', '<?= $prices['30'] ?? 299 ?>')" style="width: 100%; padding: 12px; background: var(--neon-green-bright); color: #060d1a; font-weight: 900; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 15px rgba(126,200,227,0.3);">
          Buy 30 Days Now
        </button>
      </div>

      <!-- 90 Days / Season -->
      <div style="background: rgba(13, 25, 48, 0.75); border: 1px solid var(--border-green); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column;">
        <div style="color: #94a3b8; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">90 Days Full Season</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">
          ₹<?= $prices['90'] ?? 599 ?>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary); flex: 1;">
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Complete Season Pass</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> All Game Updates Covered</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Lifetime Support Priority</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '90', '<?= $prices['90'] ?? 599 ?>')" style="width: 100%; padding: 11px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 10px; cursor: pointer;">
          Buy Season Pass
        </button>
      </div>

    </div>
  </section>

  <!-- Gameplay Showcase Section (VIPXSTORE Logic) -->
  <?php if (!empty($videos)): ?>
  <section class="gameplay-section" style="padding: 2.5rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
      <div>
        <span style="font-size: 0.75rem; font-weight: 800; color: var(--neon-green-bright); text-transform: uppercase; letter-spacing: 1.5px;">Real Action</span>
        <h2 style="font-size: 1.8rem; font-weight: 900; color: #fff; margin: 0.25rem 0 0;">Gameplay Showcase</h2>
      </div>
      <a href="<?= $baseDir ?>/gameplay" style="color: var(--neon-green-bright); font-size: 0.85rem; font-weight: 700; text-decoration: none;">View All Videos →</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
      <?php foreach ($videos as $v): ?>
      <div style="background: rgba(13, 25, 48, 0.8); border: 1px solid var(--border-green); border-radius: 14px; overflow: hidden;">
        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000;">
          <iframe src="<?= htmlspecialchars($v['url']) ?>" title="<?= htmlspecialchars($v['title']) ?>" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;" allowfullscreen></iframe>
        </div>
        <div style="padding: 0.85rem 1rem;">
          <div style="font-size: 0.72rem; color: var(--neon-green-bright); font-weight: 700; margin-bottom: 4px;"><?= htmlspecialchars($v['type']) ?> • <?= htmlspecialchars($v['duration']) ?> • <?= htmlspecialchars($v['views']) ?> views</div>
          <div style="font-weight: 700; font-size: 0.92rem; color: #fff;"><?= htmlspecialchars($v['title']) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Customer Proofs Showcase -->
  <?php if (!empty($proofs)): ?>
  <section class="proofs-section" style="padding: 2.5rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
      <div>
        <span style="font-size: 0.75rem; font-weight: 800; color: var(--neon-green-bright); text-transform: uppercase; letter-spacing: 1.5px;">Real Results</span>
        <h2 style="font-size: 1.8rem; font-weight: 900; color: #fff; margin: 0.25rem 0 0;">Customer Proofs</h2>
      </div>
      <a href="<?= $baseDir ?>/proofs" style="color: var(--neon-green-bright); font-size: 0.85rem; font-weight: 700; text-decoration: none;">View All Proofs →</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
      <?php foreach ($proofs as $p): ?>
      <div style="background: rgba(13, 25, 48, 0.8); border: 1px solid var(--border-green); border-radius: 14px; overflow: hidden;">
        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="Proof" style="width: 100%; height: 180px; object-fit: cover; background: #081020;">
        <div style="padding: 0.85rem 1rem;">
          <span style="font-size: 0.7rem; font-weight: 700; color: var(--neon-green-bright);"><?= htmlspecialchars($p['tag'] ?? 'Verified') ?></span>
          <h4 style="font-size: 0.88rem; font-weight: 700; color: #fff; margin: 4px 0;"><?= htmlspecialchars($p['title']) ?></h4>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Customer Reviews (VIPXSTORE Logic) -->
  <?php if (!empty($reviews)): ?>
  <section class="reviews-section" style="padding: 2.5rem 0;">
    <div style="text-align: center; margin-bottom: 2rem;">
      <span style="font-size: 0.75rem; font-weight: 800; color: var(--neon-green-bright); text-transform: uppercase; letter-spacing: 1.5px;">Feedback</span>
      <h2 style="font-size: 1.8rem; font-weight: 900; color: #fff; margin-top: 0.25rem;">What Gamers Say</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
      <?php foreach ($reviews as $r): ?>
      <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 14px; padding: 1.25rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
          <div style="font-weight: 800; color: #fff; font-size: 0.95rem;"><?= htmlspecialchars($r['name']) ?></div>
          <span style="font-size: 0.7rem; font-weight: 700; background: rgba(126,200,227,0.15); color: var(--neon-green-bright); padding: 2px 6px; border-radius: 4px;"><?= htmlspecialchars($r['type']) ?></span>
        </div>
        <div style="color: #FFB800; font-size: 0.85rem; margin-bottom: 0.5rem;">
          <?= str_repeat('★', intval($r['rating'])) ?>
        </div>
        <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; margin: 0 0 0.5rem;">
          "<?= htmlspecialchars($r['text']) ?>"
        </p>
        <div style="font-size: 0.72rem; color: #5a7a95;"><?= htmlspecialchars($r['date']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/footer.php'; ?>
