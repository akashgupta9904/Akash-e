<?php
/**
 * Front Page Template (vipxstore exact copy)
 */
get_header();
$theme_uri = get_stylesheet_directory_uri();
?>

<main class="container">

  <section class="cards-grid">

    <!-- Card 1: Android Panel -->
    <a href="<?= home_url('/android') ?>" class="panel-card glass-card reveal">
      <div class="card-badge">POPULAR</div>
      <div class="card-thumb">
        <img src="<?= $theme_uri ?>/assets/img/card-android.svg" alt="Android Panel" class="card-hero-img">
      </div>
      <div class="card-body">
        <h3 class="orbitron">Android Panel</h3>
        <p>Only Red Number / Auto Drag / Fast Working Setup / Main ID Safe / Direct Download</p>
        <div class="card-action">
          <span>Explore Android</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
    </a>

    <!-- Card 2: iPhone Panel -->
    <a href="<?= home_url('/ios') ?>" class="panel-card glass-card reveal">
      <div class="card-badge card-badge-verified">VERIFIED</div>
      <div class="card-thumb">
        <img src="<?= $theme_uri ?>/assets/img/card-ios.svg" alt="iPhone Panel" class="card-hero-img">
      </div>
      <div class="card-body">
        <h3 class="orbitron">iPhone Panel</h3>
        <p>Direct DNS / Safe No Jailbreak / Smooth Aim Lock / Setup Files / All iOS Devices</p>
        <div class="card-action">
          <span>Explore iOS</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
    </a>

    <!-- Card 3: All Proofs -->
    <a href="<?= home_url('/proofs') ?>" class="panel-card glass-card reveal">
      <div class="card-badge">LIVE</div>
      <div class="card-thumb">
        <img src="<?= $theme_uri ?>/assets/img/card-proofs.svg" alt="All Proofs" class="card-hero-img">
      </div>
      <div class="card-body">
        <h3 class="orbitron">All Proofs</h3>
        <p>Customer Feedbacks / Working Proofs / Active Reviews / Community Trust</p>
        <div class="card-action">
          <span>See Proofs</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
    </a>

    <!-- Card 4: Panel Gameplay -->
    <a href="<?= home_url('/gameplay') ?>" class="panel-card glass-card reveal">
      <div class="card-badge">LIVE</div>
      <div class="card-thumb">
        <img src="<?= $theme_uri ?>/assets/img/card-gameplay.svg" alt="Panel Gameplay" class="card-hero-img">
      </div>
      <div class="card-body">
        <h3 class="orbitron">Panel Gameplay</h3>
        <p>Watch Demos / Panel Features / Real-time In-game Action / Performance Previews</p>
        <div class="card-action">
          <span>Watch Videos</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </div>
      </div>
    </a>

  </section>

  <!-- Stats row -->
  <section class="stats-row reveal">
    <div class="stat-item">
      <div class="stat-value"><span class="counter-num" data-target="2627">2627</span>+</div>
      <div class="stat-label">Panels Sold</div>
    </div>
    <div class="stat-item">
      <div class="stat-value">24/7</div>
      <div class="stat-label">Support</div>
    </div>
    <div class="stat-item">
      <div class="stat-value">Instant</div>
      <div class="stat-label">Delivery</div>
    </div>
    <div class="stat-item">
      <div class="stat-value">100%</div>
      <div class="stat-label">Working</div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
