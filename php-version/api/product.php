<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $product = get_product_config();
    json_response(['success' => true, 'product' => $product]);
}

if ($method === 'POST') {
    require_admin_auth();
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $current = get_product_config();

    if (isset($input['name'])) $current['name'] = trim($input['name']);
    if (isset($input['universal_key'])) $current['universal_key'] = trim($input['universal_key']);
    if (isset($input['apk_download_link'])) $current['apk_download_link'] = trim($input['apk_download_link']);
    if (isset($input['ios_link'])) $current['ios_link'] = trim($input['ios_link']);
    if (isset($input['version'])) $current['version'] = trim($input['version']);
    if (isset($input['description'])) $current['description'] = trim($input['description']);

    save_json_data('product-config.json', $current);
    json_response(['success' => true, 'message' => 'Product configuration updated successfully', 'product' => $current]);
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
