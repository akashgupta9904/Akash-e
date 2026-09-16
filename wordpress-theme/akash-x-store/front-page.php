<?php
/**
 * The Front Page template for AKASH X STORE WordPress Theme
 */
get_header();

$voice_url = akash_get_opt('voice_url');
$universal_key = akash_get_opt('universal_key', '7744');
$p1 = akash_get_opt('price_1', '80');
$p15 = akash_get_opt('price_15', '150');
$p30 = akash_get_opt('price_30', '299');
$p90 = akash_get_opt('price_90', '599');
?>

<main class="home-page" style="max-width: 1200px; margin: 0 auto; padding: 1.5rem 1.25rem;">

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
    <?php if (!empty($voice_url)): ?>
    <div style="max-width: 600px; margin: 0 auto 1.5rem;">
      <div class="voice-player-card">
        <button type="button" class="voice-play-btn" onclick="toggleVoicePlayer(this)">
          <i class="fa-solid fa-play"></i>
        </button>
        <div class="voice-info">
          <div class="voice-title">
            <span>Admin Voice Guide</span>
            <span class="voice-badge">IMPORTANT</span>
          </div>
          <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Listen to voice note before buying VIP access</div>
          <div class="voice-waveform">
            <div class="voice-progress"></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Universal Key Banner (VIPXSTORE Logic) -->
    <?php if (!empty($universal_key)): ?>
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
          <span class="key-badge"><?php echo esc_html($universal_key); ?></span>
          <button onclick="copyKey('<?php echo esc_js($universal_key); ?>')" style="background: var(--neon-green-bright); color: #060d1a; border: none; border-radius: 8px; padding: 7px 12px; font-weight: 800; font-size: 0.78rem; cursor: pointer;">
            Copy
          </button>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Device Selection Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 750px; margin: 0 auto 2.5rem;">
      <div style="background: rgba(13, 25, 48, 0.85); border: 1px solid rgba(61, 220, 132, 0.4); border-radius: 18px; padding: 1.5rem; text-align: left;">
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
          <a href="<?php echo esc_url(home_url('/android/')); ?>" style="flex: 1; text-align: center; padding: 10px; background: #3DDC84; color: #060d1a; font-weight: 800; border-radius: 10px; text-decoration: none; font-size: 0.85rem;">
            Explore Android
          </a>
          <button onclick="openCheckoutModal('Android', '30', '<?php echo esc_js($p30); ?>')" style="flex: 1; padding: 10px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 700; border-radius: 10px; cursor: pointer; font-size: 0.85rem;">
            Buy ₹<?php echo esc_html($p30); ?>
          </button>
        </div>
      </div>

      <div style="background: rgba(13, 25, 48, 0.85); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 18px; padding: 1.5rem; text-align: left;">
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
          <a href="<?php echo esc_url(home_url('/ios/')); ?>" style="flex: 1; text-align: center; padding: 10px; background: #fff; color: #060d1a; font-weight: 800; border-radius: 10px; text-decoration: none; font-size: 0.85rem;">
            Explore iOS
          </a>
          <button onclick="openCheckoutModal('iOS', '30', '<?php echo esc_js($p30); ?>')" style="flex: 1; padding: 10px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 700; border-radius: 10px; cursor: pointer; font-size: 0.85rem;">
            Buy ₹<?php echo esc_html($p30); ?>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Plans -->
  <section class="pricing-section" style="padding: 2.5rem 0;">
    <div style="text-align: center; margin-bottom: 2rem;">
      <span style="font-size: 0.75rem; font-weight: 800; color: var(--neon-green-bright); text-transform: uppercase; letter-spacing: 1.5px;">Transparent Pricing</span>
      <h2 style="font-size: 1.9rem; font-weight: 900; color: #fff; margin-top: 0.25rem;">Choose Your VIP Plan</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
      <div style="background: rgba(13, 25, 48, 0.75); border: 1px solid var(--border-green); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column;">
        <div style="color: #94a3b8; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">1 Day Trial</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">₹<?php echo esc_html($p1); ?></div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary); flex: 1;">
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Full VIP Feature Access</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> 99% Drag Headshots</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Safe Anti-Ban v2.8</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '1', '<?php echo esc_js($p1); ?>')" style="width: 100%; padding: 11px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 10px; cursor: pointer;">Buy 1 Day</button>
      </div>

      <div style="background: rgba(13, 25, 48, 0.75); border: 1px solid var(--border-green); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column;">
        <div style="color: #94a3b8; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">15 Days VIP</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">₹<?php echo esc_html($p15); ?></div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary); flex: 1;">
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Anti-Ban Bypass Active</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Rank Push Support</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> WhatsApp Support</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '15', '<?php echo esc_js($p15); ?>')" style="width: 100%; padding: 11px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 10px; cursor: pointer;">Buy 15 Days</button>
      </div>

      <div style="background: linear-gradient(135deg, rgba(17, 36, 70, 0.95), rgba(9, 18, 36, 0.95)); border: 2px solid var(--neon-green-bright); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; position: relative;">
        <div style="position: absolute; top: -11px; right: 18px; background: var(--neon-green-bright); color: #060d1a; font-weight: 800; font-size: 0.68rem; padding: 2px 10px; border-radius: 10px; text-transform: uppercase;">Best Seller</div>
        <div style="color: var(--neon-green-bright); font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">30 Days Monthly</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">₹<?php echo esc_html($p30); ?></div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: #e2e8f0; flex: 1;">
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> 30 Days Full Access</li>
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Grandmaster Push Safe</li>
          <li><i class="fa-solid fa-circle-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> 24/7 Priority Support</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '30', '<?php echo esc_js($p30); ?>')" style="width: 100%; padding: 12px; background: var(--neon-green-bright); color: #060d1a; font-weight: 900; border: none; border-radius: 10px; cursor: pointer;">Buy 30 Days Now</button>
      </div>

      <div style="background: rgba(13, 25, 48, 0.75); border: 1px solid var(--border-green); border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column;">
        <div style="color: #94a3b8; font-size: 0.82rem; font-weight: 700; text-transform: uppercase;">90 Days Season</div>
        <div style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.5rem 0 1rem;">₹<?php echo esc_html($p90); ?></div>
        <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem; display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.82rem; color: var(--text-secondary); flex: 1;">
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> Complete Season Pass</li>
          <li><i class="fa-solid fa-check" style="color: var(--neon-green-bright); margin-right: 6px;"></i> All Game Updates Included</li>
        </ul>
        <button onclick="openCheckoutModal('Android / iOS', '90', '<?php echo esc_js($p90); ?>')" style="width: 100%; padding: 11px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 10px; cursor: pointer;">Buy Season Pass</button>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
