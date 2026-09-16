<?php
/**
 * The Header for AKASH X STORE WordPress Theme
 */
$site_title = akash_get_opt('site_title', get_bloginfo('name'));
$announcement = akash_get_opt('announcement');
$whatsapp = akash_get_opt('whatsapp');
$clean_wa = preg_replace('/[^0-9]/', '', $whatsapp);
$telegram = akash_get_opt('telegram');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="icon" type="image/svg+xml" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.svg">
  
  <?php wp_head(); ?>

  <style>
    :root {
      --primary-glow: rgba(126, 200, 227, 0.45);
      --accent-cyan: #7ec8e3;
    }
    .voice-player-card {
      background: linear-gradient(135deg, rgba(13, 25, 48, 0.85) 0%, rgba(8, 16, 32, 0.95) 100%);
      border: 1px solid var(--border-green);
      border-radius: 16px;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
      margin: 1.25rem 0;
    }
    .voice-play-btn {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: var(--neon-green-bright);
      color: #060d1a;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      cursor: pointer;
      flex-shrink: 0;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .voice-play-btn:hover {
      transform: scale(1.08);
      box-shadow: 0 0 18px var(--primary-glow);
    }
    .voice-info { flex: 1; }
    .voice-title {
      font-weight: 700;
      font-size: 0.95rem;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .voice-badge {
      font-size: 0.7rem;
      background: rgba(126, 200, 227, 0.15);
      color: var(--neon-green-bright);
      padding: 2px 8px;
      border-radius: 6px;
      font-weight: 600;
    }
    .voice-waveform {
      width: 100%;
      height: 6px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 3px;
      margin-top: 6px;
      overflow: hidden;
      cursor: pointer;
      position: relative;
    }
    .voice-progress {
      width: 0%;
      height: 100%;
      background: var(--neon-green-bright);
      border-radius: 3px;
      transition: width 0.1s linear;
    }
    .universal-key-banner {
      background: linear-gradient(90deg, rgba(126, 200, 227, 0.12) 0%, rgba(13, 32, 64, 0.6) 100%);
      border: 1px dashed var(--neon-green-bright);
      border-radius: 12px;
      padding: 0.9rem 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 1rem 0;
    }
    .key-badge {
      font-family: monospace;
      font-size: 1.2rem;
      font-weight: 800;
      color: #fff;
      background: #081224;
      padding: 4px 12px;
      border-radius: 8px;
      border: 1px solid var(--border-green);
      letter-spacing: 2px;
    }
    .cyber-modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(4, 9, 18, 0.85);
      backdrop-filter: blur(10px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .cyber-modal-overlay.active { display: flex; }
    .cyber-modal {
      background: #091322;
      border: 1px solid var(--border-green);
      border-radius: 20px;
      max-width: 480px;
      width: 100%;
      padding: 1.75rem;
      box-shadow: 0 12px 40px rgba(0,0,0,0.6);
      position: relative;
    }
    .cyber-modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      border: none;
      color: #94a3b8;
      font-size: 1.4rem;
      cursor: pointer;
    }
  </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <?php if (!empty($announcement)): ?>
  <div class="announcement-bar" style="background: linear-gradient(90deg, #09152b, #0f2b48); border-bottom: 1px solid var(--border-green); padding: 7px 1rem; font-size: 0.82rem; text-align: center; color: var(--text-secondary);">
    <span><i class="fa-solid fa-bullhorn" style="color: var(--neon-green-bright); margin-right: 6px;"></i> <?php echo esc_html($announcement); ?></span>
  </div>
  <?php endif; ?>

  <header class="main-header" style="position: sticky; top: 0; z-index: 1000; background: rgba(6, 13, 26, 0.94); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border-green);">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0.75rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
      
      <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-logo" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png" alt="Logo" style="width: 38px; height: 38px; border-radius: 50%; border: 2px solid var(--neon-green-bright); object-fit: cover;">
        <div>
          <span style="font-weight: 900; font-size: 1.25rem; color: #fff; letter-spacing: 0.5px;"><?php echo esc_html($site_title); ?></span>
          <span style="display: block; font-size: 0.65rem; color: var(--neon-green-bright); font-weight: 700; letter-spacing: 1px;">VIP GAMING PANEL</span>
        </div>
      </a>

      <nav class="desktop-nav" style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #fff; font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-solid fa-house" style="margin-right: 5px;"></i> Home</a>
        <a href="<?php echo esc_url(home_url('/android/')); ?>" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-brands fa-android" style="color: #3DDC84; margin-right: 5px;"></i> Android VIP</a>
        <a href="<?php echo esc_url(home_url('/ios/')); ?>" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-brands fa-apple" style="margin-right: 5px;"></i> iOS Panel</a>
        <a href="<?php echo esc_url(home_url('/proofs/')); ?>" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-solid fa-images" style="color: var(--neon-green-bright); margin-right: 5px;"></i> Proofs</a>
        <a href="<?php echo esc_url(home_url('/gameplay/')); ?>" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-solid fa-play" style="color: #ff0050; margin-right: 5px;"></i> Gameplay</a>
      </nav>

      <div style="display: flex; align-items: center; gap: 0.75rem;">
        <a href="https://wa.me/<?php echo esc_attr($clean_wa); ?>" target="_blank" style="padding: 8px 14px; background: #25D366; color: #fff; font-weight: 700; font-size: 0.85rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
          <i class="fa-brands fa-whatsapp" style="font-size: 1.1rem;"></i> <span>WhatsApp</span>
        </a>
        <button id="wpMobileMenuBtn" aria-label="Toggle Menu" style="display: none; background: none; border: 1px solid var(--border-green); color: #fff; padding: 7px 10px; border-radius: 8px; font-size: 1.1rem; cursor: pointer;">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>

    </div>

    <!-- Mobile Drawer Menu -->
    <div id="wpMobileDrawer" style="display: none; background: #070f1e; border-top: 1px solid var(--border-green); padding: 1rem 1.25rem;">
      <div style="display: flex; flex-direction: column; gap: 0.85rem;">
        <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-solid fa-house" style="width: 25px;"></i> Home</a>
        <a href="<?php echo esc_url(home_url('/android/')); ?>" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-brands fa-android" style="width: 25px; color: #3DDC84;"></i> Android VIP Panel</a>
        <a href="<?php echo esc_url(home_url('/ios/')); ?>" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-brands fa-apple" style="width: 25px;"></i> iOS Panel Setup</a>
        <a href="<?php echo esc_url(home_url('/proofs/')); ?>" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-solid fa-images" style="width: 25px; color: var(--neon-green-bright);"></i> Customer Proofs</a>
        <a href="<?php echo esc_url(home_url('/gameplay/')); ?>" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-solid fa-play" style="width: 25px; color: #ff0050;"></i> Gameplay Demo</a>
        <a href="<?php echo esc_url($telegram); ?>" target="_blank" style="color: #29B6F6; font-weight: 600; text-decoration: none; padding: 8px 0;"><i class="fa-brands fa-telegram" style="width: 25px;"></i> Telegram Channel</a>
      </div>
    </div>
  </header>

  <script>
    const wpMBtn = document.getElementById('wpMobileMenuBtn');
    const wpMDrawer = document.getElementById('wpMobileDrawer');
    if (wpMBtn && wpMDrawer) {
      wpMBtn.addEventListener('click', () => {
        wpMDrawer.style.display = (wpMDrawer.style.display === 'block') ? 'none' : 'block';
      });
    }
    function updateWpMenu() {
      if (window.innerWidth <= 840) {
        if (wpMBtn) wpMBtn.style.display = 'block';
        const dNav = document.querySelector('.desktop-nav');
        if (dNav) dNav.style.display = 'none';
      } else {
        if (wpMBtn) wpMBtn.style.display = 'none';
        if (wpMDrawer) wpMDrawer.style.display = 'none';
        const dNav = document.querySelector('.desktop-nav');
        if (dNav) dNav.style.display = 'flex';
      }
    }
    window.addEventListener('resize', updateWpMenu);
    updateWpMenu();
  </script>
