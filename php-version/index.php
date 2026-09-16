<?php
/**
 * AKASH X STORE - Front Controller & Clean URL Router
 * Works flawlessly on Apache, cPanel, XAMPP, LiteSpeed, Nginx, or PHP Built-in server
 */

require_once __DIR__ . '/config/config.php';

// Detect base URL path dynamically (works even if deployed in subfolders)
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$baseDir = dirname($scriptName);
if ($baseDir === '/' || $baseDir === '\\') {
    $baseDir = '';
}

// Get the requested URI path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base directory from URI if installed in a subfolder
if ($baseDir !== '' && strpos($requestUri, $baseDir) === 0) {
    $route = substr($requestUri, strlen($baseDir));
} else {
    $route = $requestUri;
}

$route = trim($route, '/');

// Also support ?page= query parameter fallback
if (isset($_GET['page'])) {
    $route = trim($_GET['page'], '/');
}

// Router Table
switch ($route) {
    // API Routes
    case 'api/settings':
        require __DIR__ . '/api/settings.php';
        exit;
    case 'api/product':
        require __DIR__ . '/api/product.php';
        exit;
    case 'api/proofs':
        require __DIR__ . '/api/proofs.php';
        exit;
    case 'api/videos':
        require __DIR__ . '/api/videos.php';
        exit;
    case 'api/reviews':
        require __DIR__ . '/api/reviews.php';
        exit;
    case 'api/orders':
        require __DIR__ . '/api/orders.php';
        exit;
    case 'api/auth':
        require __DIR__ . '/api/auth.php';
        exit;

    // View Pages
    case '':
    case 'index':
    case 'home':
    case 'index.php':
        require __DIR__ . '/views/home.php';
        break;

    case 'android':
    case 'android.php':
        require __DIR__ . '/views/android.php';
        break;

    case 'ios':
    case 'ios.php':
        require __DIR__ . '/views/ios.php';
        break;

    case 'proofs':
    case 'proofs.php':
        require __DIR__ . '/views/proofs.php';
        break;

    case 'gameplay':
    case 'gameplay.php':
        require __DIR__ . '/views/gameplay.php';
        break;

    case 'policy':
    case 'policy.php':
        require __DIR__ . '/views/policy.php';
        break;

    case 'login':
    case 'login.php':
        require __DIR__ . '/views/login.php';
        break;

    case 'admin':
        require __DIR__ . '/views/admin.php';
        break;

    case 'logout':
        unset($_SESSION['akash_admin_logged_in']);
        session_destroy();
        header('Location: ' . ($baseDir ?: '') . '/login');
        exit;

    default:
        // Handle 404 cleanly
        http_response_code(404);
        echo '<!DOCTYPE html><html><head><title>404 Not Found | AKASH X STORE</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="' . ($baseDir ?: '') . '/assets/css/main.css"></head><body style="background:#070d18;color:#fff;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;font-family:sans-serif;text-align:center;"><div style="padding:2rem;"><h1>404</h1><p>Page Not Found</p><a href="' . ($baseDir ?: '') . '/" style="color:#00e5ff;text-decoration:none;font-weight:bold;">← Return to Storefront</a></div></body></html>';
        break;
}
