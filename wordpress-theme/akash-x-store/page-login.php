<?php
/**
 * Template Name: Owner Login Portal
 */
if (is_user_logged_in() && current_user_can('manage_options')) {
    wp_safe_redirect(admin_url('admin.php?page=akash-x-store-settings'));
    exit;
}
get_header();
?>

<main style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
  <div style="background: rgba(13, 25, 48, 0.9); border: 1px solid var(--border-green); border-radius: 20px; padding: 2.5rem 2rem; max-width: 420px; width: 100%; box-shadow: 0 15px 50px rgba(0,0,0,0.7); text-align: center;">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png" alt="Logo" style="width: 64px; height: 64px; border-radius: 50%; border: 2px solid var(--neon-green-bright); margin-bottom: 1rem;">
    <h2 style="color: #fff; font-size: 1.5rem; font-weight: 900; margin: 0 0 0.25rem;">Owner Control Portal</h2>
    <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 1.75rem;">Access store configuration dashboard</p>

    <a href="<?php echo esc_url(wp_login_url(admin_url('admin.php?page=akash-x-store-settings'))); ?>" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 13px; background: var(--neon-green-bright); color: #060d1a; font-weight: 900; border-radius: 10px; text-decoration: none; font-size: 1rem;">
      <i class="fa-solid fa-lock"></i> Log In via WordPress Admin
    </a>
  </div>
</main>

<?php get_footer(); ?>
