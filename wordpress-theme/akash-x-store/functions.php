<?php
/**
 * AKASH X STORE VIP Panel Theme Functions & Clean Router
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// 1. Theme Setup
function akash_x_store_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    register_nav_menus([
        'primary' => __('Primary Navigation Menu', 'akash-x-store')
    ]);
}
add_action('after_setup_theme', 'akash_x_store_setup');

// 2. Enqueue CSS and JS Scripts
function akash_x_store_scripts() {
    // Fonts & FontAwesome
    wp_enqueue_style('akash-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap', [], null);
    wp_enqueue_style('akash-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', [], '6.5.1');
    
    // Core Cyber Theme Stylesheet
    wp_enqueue_style('akash-main-theme', get_stylesheet_directory_uri() . '/assets/css/main.css', [], '2.8.0');
}
add_action('wp_enqueue_scripts', 'akash_x_store_scripts');

// 3. Clean URL Virtual Routing
function akash_x_store_rewrite_rules() {
    add_rewrite_rule('^android/?$', 'index.php?akash_page=android', 'top');
    add_rewrite_rule('^ios/?$', 'index.php?akash_page=ios', 'top');
    add_rewrite_rule('^proofs/?$', 'index.php?akash_page=proofs', 'top');
    add_rewrite_rule('^gameplay/?$', 'index.php?akash_page=gameplay', 'top');
    add_rewrite_rule('^login/?$', 'index.php?akash_page=login', 'top');
    add_rewrite_rule('^admin/?$', 'index.php?akash_page=admin', 'top');
}
add_action('init', 'akash_x_store_rewrite_rules');

function akash_x_store_query_vars($vars) {
    $vars[] = 'akash_page';
    return $vars;
}
add_filter('query_vars', 'akash_x_store_query_vars');

function akash_x_store_template_include($template) {
    $page = get_query_var('akash_page');
    if ($page) {
        $custom_template = get_stylesheet_directory() . '/page-' . sanitize_file_name($page) . '.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'akash_x_store_template_include');

// 4. Helper Function to Get Theme Options
function akash_get_opt($key, $default = '') {
    $options = [
        'site_title' => get_option('akash_site_title', 'AKASH X STORE'),
        'upi_id' => get_option('akash_upi_id', 'igakash@fam'),
        'upi_name' => get_option('akash_upi_name', 'AKASH X STORE'),
        'whatsapp' => get_option('akash_whatsapp', '+91 9135164069'),
        'telegram' => get_option('akash_telegram', 'https://t.me/akashxstore'),
        'voice_url' => get_option('akash_voice_url', 'https://videotourl.com/audio/1781181579079-2c5e78ea-9864-416f-b65f-08f7c8dabf61.mp3'),
        'universal_key' => get_option('akash_universal_key', '7744'),
        'apk_download_link' => get_option('akash_apk_download_link', 'https://example.com/download/akash-vip-panel.apk'),
        'ios_link' => get_option('akash_ios_link', 'https://example.com/ios/setup-profile'),
        'version' => get_option('akash_version', 'v2.8'),
        'announcement' => get_option('akash_announcement', '🔥 Season 43 Anti-Ban v2.8 Updated! Direct UPI Payment & Instant Key Release.'),
        'price_1' => get_option('akash_price_1', '80'),
        'price_15' => get_option('akash_price_15', '150'),
        'price_30' => get_option('akash_price_30', '299'),
        'price_90' => get_option('akash_price_90', '599'),
    ];
    return isset($options[$key]) ? $options[$key] : $default;
}

// 5. WordPress Admin Dashboard Settings Menu
function akash_x_store_admin_menu() {
    add_menu_page(
        'AKASH X STORE',
        'AKASH X STORE',
        'manage_options',
        'akash-x-store-settings',
        'akash_x_store_render_admin_page',
        'dashicons-shield-alt',
        25
    );
}
add_action('admin_menu', 'akash_x_store_admin_menu');

function akash_x_store_render_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['akash_save_settings']) && check_admin_referer('akash_settings_verify')) {
        update_option('akash_site_title', sanitize_text_field($_POST['akash_site_title']));
        update_option('akash_upi_id', sanitize_text_field($_POST['akash_upi_id']));
        update_option('akash_upi_name', sanitize_text_field($_POST['akash_upi_name']));
        update_option('akash_whatsapp', sanitize_text_field($_POST['akash_whatsapp']));
        update_option('akash_telegram', esc_url_raw($_POST['akash_telegram']));
        update_option('akash_voice_url', esc_url_raw($_POST['akash_voice_url']));
        update_option('akash_universal_key', sanitize_text_field($_POST['akash_universal_key']));
        update_option('akash_apk_download_link', esc_url_raw($_POST['akash_apk_download_link']));
        update_option('akash_ios_link', esc_url_raw($_POST['akash_ios_link']));
        update_option('akash_version', sanitize_text_field($_POST['akash_version']));
        update_option('akash_announcement', sanitize_text_field($_POST['akash_announcement']));
        update_option('akash_price_1', intval($_POST['akash_price_1']));
        update_option('akash_price_15', intval($_POST['akash_price_15']));
        update_option('akash_price_30', intval($_POST['akash_price_30']));
        update_option('akash_price_90', intval($_POST['akash_price_90']));
        
        flush_rewrite_rules();
        echo '<div class="notice notice-success is-dismissible"><p><strong>AKASH X STORE settings updated successfully!</strong></p></div>';
    }

    ?>
    <div class="wrap" style="max-width: 900px;">
      <h1><span class="dashicons dashicons-shield-alt" style="font-size: 32px; width: 32px; height: 32px; color: #2271b1;"></span> AKASH X STORE VIP Panel Control</h1>
      <p>Configure your UPI payments, universal keys, WhatsApp number, and voice player settings.</p>

      <form method="POST" action="">
        <?php wp_nonce_field('akash_settings_verify'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row"><label for="akash_site_title">Store Brand Name</label></th>
            <td><input name="akash_site_title" type="text" id="akash_site_title" value="<?php echo esc_attr(akash_get_opt('site_title')); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_upi_id">Official UPI ID</label></th>
            <td><input name="akash_upi_id" type="text" id="akash_upi_id" value="<?php echo esc_attr(akash_get_opt('upi_id')); ?>" class="regular-text">
            <p class="description">Payments via QR and UPI intent links will go to this address.</p></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_upi_name">UPI Payee Name</label></th>
            <td><input name="akash_upi_name" type="text" id="akash_upi_name" value="<?php echo esc_attr(akash_get_opt('upi_name')); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_whatsapp">WhatsApp Number</label></th>
            <td><input name="akash_whatsapp" type="text" id="akash_whatsapp" value="<?php echo esc_attr(akash_get_opt('whatsapp')); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_telegram">Telegram Link</label></th>
            <td><input name="akash_telegram" type="url" id="akash_telegram" value="<?php echo esc_attr(akash_get_opt('telegram')); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_voice_url">Voice Note MP3 URL</label></th>
            <td><input name="akash_voice_url" type="url" id="akash_voice_url" value="<?php echo esc_attr(akash_get_opt('voice_url')); ?>" class="large-text">
            <p class="description">Audio player on homepage, Android and iOS pages will play this note.</p></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_universal_key">Universal Access Key</label></th>
            <td><input name="akash_universal_key" type="text" id="akash_universal_key" value="<?php echo esc_attr(akash_get_opt('universal_key')); ?>" class="regular-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_apk_download_link">Android APK Link</label></th>
            <td><input name="akash_apk_download_link" type="url" id="akash_apk_download_link" value="<?php echo esc_attr(akash_get_opt('apk_download_link')); ?>" class="large-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_ios_link">iOS Setup Link</label></th>
            <td><input name="akash_ios_link" type="url" id="akash_ios_link" value="<?php echo esc_attr(akash_get_opt('ios_link')); ?>" class="large-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_version">Version Tag</label></th>
            <td><input name="akash_version" type="text" id="akash_version" value="<?php echo esc_attr(akash_get_opt('version')); ?>" class="small-text"></td>
          </tr>
          <tr>
            <th scope="row"><label for="akash_announcement">Announcement Bar</label></th>
            <td><input name="akash_announcement" type="text" id="akash_announcement" value="<?php echo esc_attr(akash_get_opt('announcement')); ?>" class="large-text"></td>
          </tr>
          <tr>
            <th scope="row">Pricing Rates (₹ INR)</th>
            <td>
              1 Day: <input name="akash_price_1" type="number" value="<?php echo esc_attr(akash_get_opt('price_1')); ?>" style="width: 80px;"> &nbsp;
              15 Days: <input name="akash_price_15" type="number" value="<?php echo esc_attr(akash_get_opt('price_15')); ?>" style="width: 80px;"> &nbsp;
              30 Days: <input name="akash_price_30" type="number" value="<?php echo esc_attr(akash_get_opt('price_30')); ?>" style="width: 80px;"> &nbsp;
              90 Days: <input name="akash_price_90" type="number" value="<?php echo esc_attr(akash_get_opt('price_90')); ?>" style="width: 80px;">
            </td>
          </tr>
        </table>
        <?php submit_button('Save Store Settings', 'primary', 'akash_save_settings'); ?>
      </form>
    </div>
    <?php
}
