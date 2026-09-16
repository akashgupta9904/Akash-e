<?php
/**
 * Template Name: Gameplay Videos
 */
get_header();
$p30 = akash_get_opt('price_30', '299');
?>

<main class="gameplay-page" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1.25rem;">
  <div style="text-align: center; margin-bottom: 2.5rem;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,0,80,0.15); border: 1px solid rgba(255,0,80,0.4); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 800; color: #ff3366; margin-bottom: 1rem;">
      <i class="fa-solid fa-play"></i> OFFICIAL GAMEPLAY DEMOS &amp; HEADSHOT HIGHLIGHTS
    </div>
    <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #fff; margin-bottom: 0.75rem;">
      Live Gameplay Showcase
    </h1>
    <p style="font-size: 1rem; color: var(--text-secondary); max-width: 650px; margin: 0 auto 1.5rem; line-height: 1.6;">
      Watch real matches recorded directly on Android and iOS devices showing instant auto-aim lock, 99% drag headshots, and safe anti-ban bypass.
    </p>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div style="background: rgba(13, 25, 48, 0.85); border: 1px solid var(--border-green); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;">
      <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000;">
        <iframe src="https://www.youtube.com/embed/0iz1BizFR2w" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;" allowfullscreen></iframe>
      </div>
      <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
        <span style="font-size: 0.72rem; font-weight: 800; background: rgba(126,200,227,0.15); color: var(--neon-green-bright); padding: 2px 8px; border-radius: 6px; width: fit-content; margin-bottom: 6px;">Android Demo</span>
        <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 1rem;">Free Fire Android Drag Headshot Demonstration</h3>
        <button onclick="openCheckoutModal('Android', '30', '<?php echo esc_js($p30); ?>')" style="margin-top: auto; width: 100%; padding: 10px; background: rgba(126,200,227,0.15); border: 1px solid var(--border-green); color: #fff; font-weight: 800; border-radius: 8px; cursor: pointer;">Get VIP Key for this Panel</button>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
