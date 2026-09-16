<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Verify authentication status
if ($method === 'GET') {
    if (is_admin_authenticated()) {
        json_response([
            'success' => true,
            'authenticated' => true,
            'user' => [
                'email' => ADMIN_EMAIL,
                'role' => 'owner'
            ]
        ]);
    } else {
        json_response(['success' => false, 'authenticated' => false], 401);
    }
}

// Login
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $action = $input['action'] ?? 'login';

    if ($action === 'logout') {
        unset($_SESSION['akash_admin_logged_in']);
        if (session_id()) {
            session_destroy();
        }
        json_response(['success' => true, 'message' => 'Logged out successfully']);
    }

    $password = $input['password'] ?? '';
    $email = $input['email'] ?? '';

    if ($password === ADMIN_PASSWORD) {
        $_SESSION['akash_admin_logged_in'] = true;
        json_response([
            'success' => true,
            'message' => 'Owner authentication successful',
            'token' => 'akash_admin_token_vip',
            'user' => [
                'email' => ADMIN_EMAIL,
                'role' => 'owner'
            ]
        ]);
    } else {
        json_response(['success' => false, 'message' => 'Incorrect admin security key / password'], 401);
    }
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
