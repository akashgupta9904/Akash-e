<?php
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

$siteConfig = get_site_config();
$productConfig = get_product_config();

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDir = dirname($scriptName);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = '';
}
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en" data-theme="red">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title><?= htmlspecialchars($siteConfig['site_title'] ?? 'AKASH X STORE') ?> — Premium Gaming Panels</title>
<meta name="description" content="Premium gaming panel store with clean setup, trusted support, proof gallery, and secure UPI checkout.">
<meta name="theme-color" content="#ff4757">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="icon" href="<?= $baseDir ?>/assets/img/vipx_logo.png" type="image/png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= $baseDir ?>/assets/css/main.css?v=43">
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>

<nav class="navbar">
  <a href="<?= $baseDir ?>/" class="brand">
    <img src="<?= $baseDir ?>/assets/img/vipx_logo.png" alt="VIP X STORE">
    <span><?= htmlspecialchars($siteConfig['site_title'] ?? 'VIP X STORE') ?></span>
  </a>

  <ul class="desktop-nav">
    <li><a href="<?= $baseDir ?>/" class="<?= ($currentPath == $baseDir . '/' || $currentPath == $baseDir . '/index.php') ? 'active' : '' ?>">Home</a></li>
    <li><a href="<?= $baseDir ?>/android.php" class="<?= strpos($currentPath, 'android') !== false ? 'active' : '' ?>">Android</a></li>
    <li><a href="<?= $baseDir ?>/ios.php" class="<?= strpos($currentPath, 'ios') !== false ? 'active' : '' ?>">iOS</a></li>
    <li><a href="<?= $baseDir ?>/proofs.php" class="<?= strpos($currentPath, 'proofs') !== false ? 'active' : '' ?>">Proofs</a></li>
    <li><a href="<?= $baseDir ?>/gameplay.php" class="<?= strpos($currentPath, 'gameplay') !== false ? 'active' : '' ?>">Gameplay</a></li>
  </ul>

  <button id="themeToggleBtn" onclick="toggleThemePicker()" class="theme-toggle-btn" title="Change Colour Theme" aria-label="Change Colour Theme">
    <img src="<?= $baseDir ?>/assets/img/sun.png" alt="Change Colour Theme" class="theme-sun-img">
  </button>
  <a href="<?= $baseDir ?>/android.php" class="navbar-buy-btn">
    Buy Now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
  </a>

  <button class="menu-btn mobile-menu-btn" onclick="toggleDrawer(true)" aria-label="Open menu">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</nav>

<div class="mobile-drawer-overlay" id="mobileDrawerOverlay" onclick="toggleDrawer(false)"></div>
<div class="mobile-drawer" id="mobileDrawer">
  <div class="drawer-header">
    <a href="<?= $baseDir ?>/" class="brand">
      <img src="<?= $baseDir ?>/assets/img/vipx_logo.png" alt="VIP X STORE" style="height:30px;width:30px;">
      <span><?= htmlspecialchars($siteConfig['site_title'] ?? 'VIP X STORE') ?></span>
    </a>
    <button onclick="toggleDrawer(false)" aria-label="Close menu" style="width:36px;height:36px;border-radius:50%;background:rgba(154,230,0,0.12);border:1px solid var(--border-green);display:flex;align-items:center;justify-content:center;color:var(--neon-green-bright);cursor:pointer;flex-shrink:0;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <a href="<?= $baseDir ?>/" class="drawer-link <?= ($currentPath == $baseDir . '/' || $currentPath == $baseDir . '/index.php') ? 'active' : '' ?>">Home</a>
  <a href="<?= $baseDir ?>/android.php" class="drawer-link <?= strpos($currentPath, 'android') !== false ? 'active' : '' ?>">Android</a>
  <a href="<?= $baseDir ?>/ios.php" class="drawer-link <?= strpos($currentPath, 'ios') !== false ? 'active' : '' ?>">iOS</a>
  <a href="<?= $baseDir ?>/proofs.php" class="drawer-link <?= strpos($currentPath, 'proofs') !== false ? 'active' : '' ?>">Proofs</a>
  <a href="<?= $baseDir ?>/gameplay.php" class="drawer-link <?= strpos($currentPath, 'gameplay') !== false ? 'active' : '' ?>">Gameplay</a>
</div>
