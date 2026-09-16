<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET reviews
if ($method === 'GET') {
    $config = get_site_config();
    $reviews = $config['reviews'] ?? [];
    json_response(['success' => true, 'reviews' => $reviews]);
}

// POST or DELETE reviews
if ($method === 'POST') {
    require_admin_auth();
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $action = $input['action'] ?? ($_GET['action'] ?? 'add');
    $config = get_site_config();
    $reviews = $config['reviews'] ?? [];

    if ($action === 'delete') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            json_response(['success' => false, 'message' => 'Review ID required'], 400);
        }
        $reviews = array_values(array_filter($reviews, function($r) use ($id) {
            return strval($r['id']) !== strval($id);
        }));
        $config['reviews'] = $reviews;
        save_json_data('site-config.json', $config);
        json_response(['success' => true, 'message' => 'Review deleted successfully', 'reviews' => $reviews]);
    }

    // Add Review
    $name = trim($input['name'] ?? 'Verified Customer');
    $type = trim($input['type'] ?? 'Android');
    $rating = intval($input['rating'] ?? 5);
    $text = trim($input['text'] ?? '');
    $date = trim($input['date'] ?? 'Just now');

    if (empty($text)) {
        json_response(['success' => false, 'message' => 'Review feedback text is required'], 400);
    }

    $newReview = [
        'id' => 'rev_' . time() . '_' . rand(100, 999),
        'name' => $name,
        'type' => $type,
        'rating' => max(1, min(5, $rating)),
        'text' => $text,
        'date' => $date
    ];

    array_unshift($reviews, $newReview);
    $config['reviews'] = $reviews;
    save_json_data('site-config.json', $config);

    json_response(['success' => true, 'message' => 'Review added successfully', 'review' => $newReview, 'reviews' => $reviews]);
}

if ($method === 'DELETE') {
    require_admin_auth();
    parse_str(file_get_contents('php://input'), $deleteParams);
    $id = $deleteParams['id'] ?? ($_GET['id'] ?? null);
    if (!$id) {
        json_response(['success' => false, 'message' => 'Review ID required'], 400);
    }
    $config = get_site_config();
    $reviews = array_values(array_filter($config['reviews'] ?? [], function($r) use ($id) {
        return strval($r['id']) !== strval($id);
    }));
    $config['reviews'] = $reviews;
    save_json_data('site-config.json', $config);
    json_response(['success' => true, 'message' => 'Review deleted successfully', 'reviews' => $reviews]);
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
