<?php
/**
 * Template Name: Owner Control Center
 */
if (!current_user_can('manage_options')) {
    wp_safe_redirect(wp_login_url(get_permalink()));
    exit;
}
wp_safe_redirect(admin_url('admin.php?page=akash-x-store-settings'));
exit;
