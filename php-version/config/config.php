<?php
/**
 * AKASH X STORE — Core Configuration & Data Store
 * Zero-dependency JSON storage with full VIPXSTORE logic integration
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('ADMIN_PASSWORD', '4141');
define('ADMIN_EMAIL', 'akash@4141');

// Helper to safely read JSON data
function get_json_data($file, $default = []) {
    $path = DATA_PATH . '/' . $file;
    if (!file_exists($path)) {
        save_json_data($file, $default);
        return $default;
    }
    $content = file_get_contents($path);
    $data = json_decode($content, true);
    return is_array($data) ? $data : $default;
}

// Helper to safely save JSON data
function save_json_data($file, $data) {
    if (!is_dir(DATA_PATH)) {
        mkdir(DATA_PATH, 0755, true);
    }
    file_put_contents(
        DATA_PATH . '/' . $file,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );
}

// Default Site Configuration (VIPXSTORE logic + Akash Panel settings)
function get_site_config() {
    $default = [
        'site_title' => 'AKASH X STORE',
        'upi_id' => 'igakash@fam',
        'upi_name' => 'AKASH X STORE',
        'whatsapp' => '+91 9135164069',
        'telegram' => 'https://t.me/akashxstore',
        'voice_url' => 'https://videotourl.com/audio/1781181579079-2c5e78ea-9864-416f-b65f-08f7c8dabf61.mp3',
        'announcement' => '🔥 Season 43 Anti-Ban v2.8 Updated! Direct UPI Payment & Instant Key Release.',
        'prices' => [
            '1' => 80,
            '15' => 150,
            '30' => 299,
            '90' => 599
        ],
        'videos' => [
            [
                'id' => 'bgmi-android',
                'title' => 'Android Free Fire & BGMI Gameplay',
                'type' => 'Android',
                'duration' => '3:45',
                'views' => '12.5K',
                'url' => 'https://www.youtube.com/embed/0iz1BizFR2w'
            ],
            [
                'id' => 'ios-ff-demo',
                'title' => 'iOS Panel Demo & Headshot Showcase',
                'type' => 'iOS',
                'duration' => '2:30',
                'views' => '8.9K',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ],
            [
                'id' => 'auto-aim-showcase',
                'title' => 'Drag Headshot & Auto Aim Showcase',
                'type' => 'All',
                'duration' => '1:15',
                'views' => '15.2K',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ],
            [
                'id' => 'setup-tutorial',
                'title' => 'Complete Setup & Installation Guide',
                'type' => 'All',
                'duration' => '4:20',
                'views' => '20.1K',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ]
        ],
        'reviews' => [
            [
                'id' => 'r-1',
                'name' => 'Rahul_G4mer',
                'type' => 'Android',
                'rating' => 5,
                'text' => 'Panel is working perfectly. Fast delivery on WhatsApp. Highly recommended!',
                'date' => '2 days ago'
            ],
            [
                'id' => 'r-2',
                'name' => 'ProXSlayer',
                'type' => 'iOS',
                'rating' => 5,
                'text' => 'No jailbreak needed, works exactly as described on iPhone. Worth the money.',
                'date' => '1 week ago'
            ],
            [
                'id' => 'r-3',
                'name' => 'SK_Gaming99',
                'type' => 'Android',
                'rating' => 5,
                'text' => 'Best panel I have used. Safe anti-ban bypass, reached Grandmaster in 3 days.',
                'date' => '1 week ago'
            ],
            [
                'id' => 'r-4',
                'name' => 'Toxic_Player',
                'type' => 'Android',
                'rating' => 5,
                'text' => '100% trusted. Got my key instantly after payment screenshot.',
                'date' => '2 weeks ago'
            ]
        ]
    ];
    return get_json_data('site-config.json', $default);
}

// Product & Keys Configuration
function get_product_config() {
    $default = [
        'id' => 'akash-panel-vip',
        'name' => 'AKASH X VIP Gaming Panel',
        'universal_key' => '7744',
        'apk_download_link' => 'https://example.com/download/akash-panel.apk',
        'ios_link' => 'https://example.com/ios/setup',
        'version' => 'v2.8 (Anti-Ban Season 43)',
        'description' => 'Official AKASH X STORE VIP Panel with 99% Headshot accuracy and zero blacklist safe lobby.'
    ];
    return get_json_data('product-config.json', $default);
}

// Customer Proofs
function get_proofs() {
    $default = [
        [
            'id' => 1,
            'title' => 'Grandmaster Rank Push 99.4% Headshot Proof',
            'tag' => 'Anti-Ban Verified',
            'image_url' => '/assets/img/logo.png',
            'description' => 'Verified match stats with Anti-Ban v2.8 bypass on Android 14. Smooth drag headshots.',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    return get_json_data('proofs.json', $default);
}

// Customer Orders
function get_orders() {
    return get_json_data('orders.json', []);
}

// Helper to extract YouTube embed URL
function extract_youtube_embed($url) {
    if (empty($url)) return '';
    if (preg_match('/(?:v=|youtu\.be\/|embed\/|shorts\/)([A-Za-z0-9_-]{6,})/i', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }
    return $url;
}

// JSON API Response
function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

// Admin Auth Guard
function is_admin_authenticated() {
    if (!empty($_SESSION['akash_admin_logged_in'])) {
        return true;
    }
    // Check Authorization Header token
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    if (preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
        return ($matches[1] === ADMIN_PASSWORD || $matches[1] === 'akash_admin_token_vip');
    }
    return false;
}

function require_admin_auth() {
    if (!is_admin_authenticated()) {
        json_response(['success' => false, 'message' => 'Unauthorized access'], 401);
    }
}
