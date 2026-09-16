<?php
/**
 * Template Name: Customer Proofs
 */
get_header();
$telegram = akash_get_opt('telegram', 'https://t.me/akashxstore');
?>

<main class="proofs-page" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem;">
  <div style="text-align: center; margin-bottom: 2.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: var(--neon-green-bright); margin-bottom: 1rem;">
      <i class="fa-solid fa-camera-retro"></i> 100% VERIFIED LIVE CUSTOMER PROOFS
    </div>
    <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem;">
      Rank Push &amp; Headshot Proofs
    </h1>
    <p style="font-size: 1rem; color: var(--text-secondary); max-width: 650px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Real match screenshots from players who reached Grandmaster and Heroic using AKASH X STORE VIP Panel without any blacklist or ban.
    </p>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div class="proof-card" style="background: rgba(13, 25, 48, 0.85); border: 1px solid var(--border-green); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png" alt="Proof" style="width: 100%; height: 230px; object-fit: cover; background: #081020;">
      <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
        <span style="font-size: 0.72rem; font-weight: 800; color: var(--neon-green-bright); margin-bottom: 4px;">Anti-Ban Verified</span>
        <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">Season 43 Grandmaster Rank Push Proof (Android 14)</h3>
        <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.75rem;">Clean 15 win streak in Master/Grandmaster lobby with 99.2% headshot rate. Zero warnings or blacklists.</p>
      </div>
    </div>
    <div class="proof-card" style="background: rgba(13, 25, 48, 0.85); border: 1px solid var(--border-green); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png" alt="Proof" style="width: 100%; height: 230px; object-fit: cover; background: #081020;">
      <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
        <span style="font-size: 0.72rem; font-weight: 800; color: var(--neon-green-bright); margin-bottom: 4px;">Safe DNS Bypass</span>
        <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem;">iPhone 15 Pro Max CS-Ranked Showcase</h3>
        <p style="font-size: 0.82rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.75rem;">Smooth 120 FPS headshot clip with zero recoil on M1887 and Desert Eagle.</p>
      </div>
    </div>
  </div>

  <div style="background: linear-gradient(135deg, rgba(34,158,217,0.2) 0%, rgba(13,25,48,0.8) 100%); border: 1px solid rgba(34,158,217,0.4); border-radius: 18px; padding: 2rem; text-align: center;">
    <h3 style="color: #fff; font-size: 1.4rem; font-weight: 900; margin-bottom: 0.5rem;">Join Our Official Telegram Community</h3>
    <p style="color: var(--text-secondary); font-size: 0.92rem; max-width: 550px; margin: 0 auto 1.25rem;">
      Over 10,000+ members post daily rank push screenshots, tournament results, and gameplay proofs in our channel.
    </p>
    <a href="<?php echo esc_url($telegram); ?>" target="_blank" style="padding: 12px 26px; background: #229ED9; color: #fff; font-weight: 800; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
      <i class="fa-brands fa-telegram" style="font-size: 1.2rem;"></i> Join Telegram Channel
    </a>
  </div>
</main>

<?php get_footer(); ?>
