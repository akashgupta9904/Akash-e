<?php
/**
 * The Header for VIP X STORE / AKASH X STORE WordPress Theme
 */
$site_title = akash_get_opt('site_title', 'VIP X STORE');
$theme_uri = get_stylesheet_directory_uri();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title><?php wp_title('—', true, 'right'); ?><?= htmlspecialchars($site_title) ?></title>
<meta name="theme-color" content="#7ec8e3">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="icon" href="<?= $theme_uri ?>/assets/img/vipx_logo.png" type="image/png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= $theme_uri ?>/assets/css/main.css?v=43">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="bg-grid"></div>
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>

<nav class="navbar">
  <a href="<?= home_url('/') ?>" class="brand">
    <img src="<?= $theme_uri ?>/assets/img/vipx_logo.png" alt="<?= esc_attr($site_title) ?>">
    <span><?= esc_html($site_title) ?></span>
  </a>

  <ul class="desktop-nav">
    <li><a href="<?= home_url('/') ?>" class="<?= is_front_page() ? 'active' : '' ?>">Home</a></li>
    <li><a href="<?= home_url('/android') ?>" class="<?= is_page('android') ? 'active' : '' ?>">Android</a></li>
    <li><a href="<?= home_url('/ios') ?>" class="<?= is_page('ios') ? 'active' : '' ?>">iOS</a></li>
    <li><a href="<?= home_url('/proofs') ?>" class="<?= is_page('proofs') ? 'active' : '' ?>">Proofs</a></li>
    <li><a href="<?= home_url('/gameplay') ?>" class="<?= is_page('gameplay') ? 'active' : '' ?>">Gameplay</a></li>
  </ul>

  <button id="themeToggleBtn" onclick="toggleThemePicker()" class="theme-toggle-btn" title="Change Colour Theme" aria-label="Change Colour Theme">
    <img src="<?= $theme_uri ?>/assets/img/sun.png" alt="Change Colour Theme" class="theme-sun-img">
  </button>
  <a href="<?= home_url('/android') ?>" class="navbar-buy-btn">
    Buy Now <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
  </a>

  <button class="menu-btn mobile-menu-btn" onclick="toggleDrawer(true)" aria-label="Open menu">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
</nav>

<div class="mobile-drawer-overlay" id="mobileDrawerOverlay" onclick="toggleDrawer(false)"></div>
<div class="mobile-drawer" id="mobileDrawer">
  <div class="drawer-header">
    <a href="<?= home_url('/') ?>" class="brand">
      <img src="<?= $theme_uri ?>/assets/img/vipx_logo.png" alt="<?= esc_attr($site_title) ?>" style="height:30px;width:30px;">
      <span><?= esc_html($site_title) ?></span>
    </a>
    <button onclick="toggleDrawer(false)" aria-label="Close menu" style="width:36px;height:36px;border-radius:50%;background:rgba(154,230,0,0.12);border:1px solid var(--border-green);display:flex;align-items:center;justify-content:center;color:var(--neon-green-bright);cursor:pointer;flex-shrink:0;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="24" height="24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <a href="<?= home_url('/') ?>" class="drawer-link <?= is_front_page() ? 'active' : '' ?>">Home</a>
  <a href="<?= home_url('/android') ?>" class="drawer-link <?= is_page('android') ? 'active' : '' ?>">Android</a>
  <a href="<?= home_url('/ios') ?>" class="drawer-link <?= is_page('ios') ? 'active' : '' ?>">iOS</a>
  <a href="<?= home_url('/proofs') ?>" class="drawer-link <?= is_page('proofs') ? 'active' : '' ?>">Proofs</a>
  <a href="<?= home_url('/gameplay') ?>" class="drawer-link <?= is_page('gameplay') ? 'active' : '' ?>">Gameplay</a>
</div>
