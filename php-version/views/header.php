<?php
// Ensure config is loaded
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

$siteConfig = get_site_config();
$productConfig = get_product_config();

// Determine current route for active nav styling
$currentRoute = trim($_SERVER['REQUEST_URI'] ?? '', '/');
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDir = dirname($scriptName);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title><?= htmlspecialchars($siteConfig['site_title'] ?? 'AKASH X STORE') ?> | Official VIP Gaming Panel</title>
  
  <meta name="description" content="Official AKASH X STORE VIP Panel for Android & iOS. High accuracy drag headshots, anti-ban bypass, and direct UPI instant activation.">
  <link rel="icon" type="image/svg+xml" href="<?= $baseDir ?>/favicon.svg">
  <link rel="apple-touch-icon" href="<?= $baseDir ?>/favicon.png">

  <!-- Google Fonts & Font Awesome Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Core Theme Stylesheet -->
  <link rel="stylesheet" href="<?= $baseDir ?>/assets/css/main.css">

  <style>
    /* Global Overrides and Enhancements */
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
    .voice-info {
      flex: 1;
    }
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

    /* Universal Key Box */
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

    /* Modal Backdrop */
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
    .cyber-modal-overlay.active {
      display: flex;
    }
    .cyber-modal {
      background: #091322;
      border: 1px solid var(--border-green);
      border-radius: 20px;
      max-width: 480px;
      width: 100%;
      padding: 1.75rem;
      box-shadow: 0 12px 40px rgba(0,0,0,0.6);
      position: relative;
      animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalPop {
      0% { opacity: 0; transform: scale(0.92); }
      100% { opacity: 1; transform: scale(1); }
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
    .cyber-modal-close:hover {
      color: #fff;
    }
  </style>
</head>
<body>

  <!-- Top Announcement Bar -->
  <?php if (!empty($siteConfig['announcement'])): ?>
  <div class="announcement-bar" style="background: linear-gradient(90deg, #09152b, #0f2b48); border-bottom: 1px solid var(--border-green); padding: 7px 1rem; font-size: 0.82rem; text-align: center; color: var(--text-secondary);">
    <span><i class="fa-solid fa-bullhorn" style="color: var(--neon-green-bright); margin-right: 6px;"></i> <?= htmlspecialchars($siteConfig['announcement']) ?></span>
  </div>
  <?php endif; ?>

  <!-- Main Navigation Header -->
  <header class="main-header" style="position: sticky; top: 0; z-index: 1000; background: rgba(6, 13, 26, 0.94); backdrop-filter: blur(16px); border-bottom: 1px solid var(--border-green);">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0.75rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
      
      <!-- Brand Logo -->
      <a href="<?= $baseDir ?>/" class="brand-logo" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
        <img src="<?= $baseDir ?>/assets/img/logo.png" alt="Akash X Store Logo" style="width: 38px; height: 38px; border-radius: 50%; border: 2px solid var(--neon-green-bright); object-fit: cover;">
        <div>
          <span style="font-weight: 900; font-size: 1.25rem; color: #fff; letter-spacing: 0.5px;"><?= htmlspecialchars($siteConfig['site_title'] ?? 'AKASH X STORE') ?></span>
          <span style="display: block; font-size: 0.65rem; color: var(--neon-green-bright); font-weight: 700; letter-spacing: 1px;">VIP GAMING PANEL</span>
        </div>
      </a>

      <!-- Desktop Nav Menu -->
      <nav class="desktop-nav" style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= $baseDir ?>/" style="color: #fff; font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-solid fa-house" style="margin-right: 5px;"></i> Home</a>
        <a href="<?= $baseDir ?>/android" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-brands fa-android" style="color: #3DDC84; margin-right: 5px;"></i> Android VIP</a>
        <a href="<?= $baseDir ?>/ios" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-brands fa-apple" style="margin-right: 5px;"></i> iOS Panel</a>
        <a href="<?= $baseDir ?>/proofs" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-solid fa-images" style="color: var(--neon-green-bright); margin-right: 5px;"></i> Proofs</a>
        <a href="<?= $baseDir ?>/gameplay" style="color: var(--text-secondary); font-weight: 600; font-size: 0.92rem; text-decoration: none;" class="nav-link"><i class="fa-solid fa-play" style="color: #ff0050; margin-right: 5px;"></i> Gameplay</a>
      </nav>

      <!-- Action Buttons -->
      <div style="display: flex; align-items: center; gap: 0.75rem;">
        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $siteConfig['whatsapp'] ?? '919135164069') ?>" target="_blank" class="btn-whatsapp-header" style="padding: 8px 14px; background: #25D366; color: #fff; font-weight: 700; font-size: 0.85rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
          <i class="fa-brands fa-whatsapp" style="font-size: 1.1rem;"></i> <span>WhatsApp</span>
        </a>
        <button id="mobileMenuBtn" aria-label="Toggle Menu" style="display: none; background: none; border: 1px solid var(--border-green); color: #fff; padding: 7px 10px; border-radius: 8px; font-size: 1.1rem; cursor: pointer;">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>

    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobileDrawer" style="display: none; background: #070f1e; border-top: 1px solid var(--border-green); padding: 1rem 1.25rem;">
      <div style="display: flex; flex-direction: column; gap: 0.85rem;">
        <a href="<?= $baseDir ?>/" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-solid fa-house" style="width: 25px;"></i> Home</a>
        <a href="<?= $baseDir ?>/android" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-brands fa-android" style="width: 25px; color: #3DDC84;"></i> Android VIP Panel</a>
        <a href="<?= $baseDir ?>/ios" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-brands fa-apple" style="width: 25px;"></i> iOS Panel Setup</a>
        <a href="<?= $baseDir ?>/proofs" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-solid fa-images" style="width: 25px; color: var(--neon-green-bright);"></i> Customer Proofs</a>
        <a href="<?= $baseDir ?>/gameplay" style="color: #fff; font-weight: 600; text-decoration: none; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05);"><i class="fa-solid fa-play" style="width: 25px; color: #ff0050;"></i> Gameplay Demo</a>
        <a href="https://t.me/<?= ltrim($siteConfig['telegram'] ?? 'akashxstore', '@https://t.me/') ?>" target="_blank" style="color: #29B6F6; font-weight: 600; text-decoration: none; padding: 8px 0;"><i class="fa-brands fa-telegram" style="width: 25px;"></i> Telegram Channel</a>
      </div>
    </div>
  </header>

  <script>
    // Responsive Mobile Menu Handler
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    if (mobileBtn && mobileDrawer) {
      mobileBtn.addEventListener('click', () => {
        const isVisible = mobileDrawer.style.display === 'block';
        mobileDrawer.style.display = isVisible ? 'none' : 'block';
      });
    }
    function updateMenuVisibility() {
      if (window.innerWidth <= 840) {
        if (mobileBtn) mobileBtn.style.display = 'block';
        const deskNav = document.querySelector('.desktop-nav');
        if (deskNav) deskNav.style.display = 'none';
      } else {
        if (mobileBtn) mobileBtn.style.display = 'none';
        if (mobileDrawer) mobileDrawer.style.display = 'none';
        const deskNav = document.querySelector('.desktop-nav');
        if (deskNav) deskNav.style.display = 'flex';
      }
    }
    window.addEventListener('resize', updateMenuVisibility);
    updateMenuVisibility();
  </script>
