<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $config = get_site_config();
    json_response(['success' => true, 'config' => $config]);
}

if ($method === 'POST') {
    require_admin_auth();
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $current = get_site_config();

    if (isset($input['site_title'])) $current['site_title'] = trim($input['site_title']);
    if (isset($input['upi_id'])) $current['upi_id'] = trim($input['upi_id']);
    if (isset($input['upi_name'])) $current['upi_name'] = trim($input['upi_name']);
    if (isset($input['whatsapp'])) $current['whatsapp'] = trim($input['whatsapp']);
    if (isset($input['telegram'])) $current['telegram'] = trim($input['telegram']);
    if (isset($input['voice_url'])) $current['voice_url'] = trim($input['voice_url']);
    if (isset($input['announcement'])) $current['announcement'] = trim($input['announcement']);
    if (isset($input['prices']) && is_array($input['prices'])) {
        $current['prices'] = $input['prices'];
    }

    save_json_data('site-config.json', $current);
    json_response(['success' => true, 'message' => 'Settings updated successfully', 'config' => $current]);
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
