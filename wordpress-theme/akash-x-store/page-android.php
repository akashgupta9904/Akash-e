<?php
/**
 * Template Name: Android VIP Panel
 */
get_header();

$apk_link = akash_get_opt('apk_download_link');
$universal_key = akash_get_opt('universal_key', '7744');
$version = akash_get_opt('version', 'v2.8');
$voice_url = akash_get_opt('voice_url');
$p30 = akash_get_opt('price_30', '299');
?>

<main class="android-page" style="max-width: 1100px; margin: 0 auto; padding: 2rem 1.25rem;">
  <div style="text-align: center; margin-bottom: 2.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(61,220,132,0.15); border: 1px solid rgba(61,220,132,0.4); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: #3DDC84; margin-bottom: 1rem;">
      <i class="fa-brands fa-android"></i> ANDROID OFFICIAL VIP BUILD <?php echo esc_html($version); ?>
    </div>
    <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem;">
      Free Fire &amp; BGMI Android VIP Panel
    </h1>
    <p style="font-size: 1rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Engineered for Android 9, 10, 11, 12, 13, 14 &amp; 15. Safe anti-ban memory injection with instant headshot lock.
    </p>

    <?php if (!empty($voice_url)): ?>
    <div style="max-width: 580px; margin: 0 auto 1.5rem;">
      <div class="voice-player-card">
        <button type="button" class="voice-play-btn" onclick="toggleVoicePlayer(this)">
          <i class="fa-solid fa-play"></i>
        </button>
        <div class="voice-info">
          <div class="voice-title">
            <span>Admin Voice Guide for Android</span>
            <span class="voice-badge">IMPORTANT</span>
          </div>
          <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Listen to setup instructions before installation</div>
          <div class="voice-waveform">
            <div class="voice-progress"></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($universal_key)): ?>
    <div style="max-width: 580px; margin: 0 auto 2rem;">
      <div class="universal-key-banner">
        <div style="display: flex; align-items: center; gap: 10px;">
          <i class="fa-solid fa-key" style="color: var(--neon-green-bright); font-size: 1.2rem;"></i>
          <div style="text-align: left;">
            <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase;">Android Universal Key</div>
            <div style="font-weight: 700; color: #fff; font-size: 0.88rem;">Use inside panel login screen</div>
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

    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
      <a href="<?php echo esc_url($apk_link); ?>" target="_blank" style="padding: 14px 28px; background: #3DDC84; color: #060d1a; font-weight: 900; font-size: 1rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: gap 10px; box-shadow: 0 4px 20px rgba(61,220,132,0.4);">
        <i class="fa-solid fa-download"></i> Download Android APK
      </a>
      <button onclick="openCheckoutModal('Android', '30', '<?php echo esc_js($p30); ?>')" style="padding: 14px 24px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; font-size: 1rem; border-radius: 12px; cursor: pointer;">
        <i class="fa-solid fa-cart-shopping"></i> Buy Key (₹<?php echo esc_html($p30); ?>)
      </button>
    </div>
  </div>

  <div style="background: rgba(13, 25, 48, 0.7); border: 1px solid var(--border-green); border-radius: 16px; padding: 2rem;">
    <h3 style="color: #fff; font-size: 1.3rem; font-weight: 800; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-list-check" style="color: #3DDC84;"></i> Android Step-by-Step Installation Guide
    </h3>
    <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.9rem; color: var(--text-secondary);">
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #3DDC84; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">1</span>
        <div>Click the <strong>Download Android APK</strong> button above to download the latest setup file.</div>
      </div>
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #3DDC84; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">2</span>
        <div>Open settings and enable <strong>Allow Install from Unknown Sources</strong> if prompted.</div>
      </div>
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #3DDC84; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">3</span>
        <div>Launch the app and enter your VIP key (or universal key <code><?php echo esc_html($universal_key); ?></code>).</div>
      </div>
      <div style="display: flex; gap: 12px; align-items: flex-start;">
        <span style="background: #3DDC84; color: #060d1a; font-weight: 900; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;">4</span>
        <div>Tap <strong>Inject Bypass</strong> and start your game for instant headshots!</div>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
