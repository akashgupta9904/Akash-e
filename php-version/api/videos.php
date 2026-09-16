<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET videos
if ($method === 'GET') {
    $config = get_site_config();
    $videos = $config['videos'] ?? [];
    json_response(['success' => true, 'videos' => $videos]);
}

// POST or DELETE videos
if ($method === 'POST') {
    require_admin_auth();
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $action = $input['action'] ?? ($_GET['action'] ?? 'add');
    $config = get_site_config();
    $videos = $config['videos'] ?? [];

    if ($action === 'delete') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            json_response(['success' => false, 'message' => 'Video ID required'], 400);
        }
        $videos = array_values(array_filter($videos, function($v) use ($id) {
            return strval($v['id']) !== strval($id);
        }));
        $config['videos'] = $videos;
        save_json_data('site-config.json', $config);
        json_response(['success' => true, 'message' => 'Video deleted successfully', 'videos' => $videos]);
    }

    // Add Video
    $rawUrl = trim($input['url'] ?? '');
    if (empty($rawUrl)) {
        json_response(['success' => false, 'message' => 'Video URL is required'], 400);
    }
    $embedUrl = extract_youtube_embed($rawUrl);

    $newVideo = [
        'id' => 'vid_' . time() . '_' . rand(100, 999),
        'title' => trim($input['title'] ?? 'Gameplay Demo'),
        'type' => trim($input['type'] ?? 'Android'),
        'duration' => trim($input['duration'] ?? '2:30'),
        'views' => trim($input['views'] ?? '5.0K'),
        'url' => $embedUrl
    ];

    $videos[] = $newVideo;
    $config['videos'] = $videos;
    save_json_data('site-config.json', $config);

    json_response(['success' => true, 'message' => 'Video added successfully', 'video' => $newVideo, 'videos' => $videos]);
}

if ($method === 'DELETE') {
    require_admin_auth();
    parse_str(file_get_contents('php://input'), $deleteParams);
    $id = $deleteParams['id'] ?? ($_GET['id'] ?? null);
    if (!$id) {
        json_response(['success' => false, 'message' => 'Video ID required'], 400);
    }
    $config = get_site_config();
    $videos = array_values(array_filter($config['videos'] ?? [], function($v) use ($id) {
        return strval($v['id']) !== strval($id);
    }));
    $config['videos'] = $videos;
    save_json_data('site-config.json', $config);
    json_response(['success' => true, 'message' => 'Video deleted successfully', 'videos' => $videos]);
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
