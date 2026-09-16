<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Handle Proofs GET
if ($method === 'GET') {
    $proofs = get_proofs();
    json_response(['success' => true, 'proofs' => $proofs]);
}

// Handle Proofs POST / DELETE
if ($method === 'POST') {
    require_admin_auth();

    $action = $_POST['action'] ?? ($_GET['action'] ?? '');
    
    // Deletion
    if ($action === 'delete') {
        $id = $_POST['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            json_response(['success' => false, 'message' => 'Proof ID required'], 400);
        }
        $proofs = get_proofs();
        $filtered = array_values(array_filter($proofs, function($item) use ($id) {
            return strval($item['id']) !== strval($id);
        }));
        save_json_data('proofs.json', $filtered);
        json_response(['success' => true, 'message' => 'Proof removed successfully', 'proofs' => $filtered]);
    }

    // Adding a new Proof
    $title = trim($_POST['title'] ?? 'Customer Proof');
    $tag = trim($_POST['tag'] ?? 'Anti-Ban Verified');
    $type = trim($_POST['type'] ?? 'Android');
    $description = trim($_POST['description'] ?? '');
    $imageUrl = trim($_POST['image_url'] ?? '');

    // Check for uploaded file
    if (!empty($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['proof_image']['tmp_name'];
        $originalName = basename($_FILES['proof_image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $allowed)) {
            json_response(['success' => false, 'message' => 'Only image files (jpg, png, webp, gif) are allowed'], 400);
        }

        if (!is_dir(UPLOADS_PATH)) {
            mkdir(UPLOADS_PATH, 0755, true);
        }

        $filename = 'proof_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $destination = UPLOADS_PATH . '/' . $filename;

        if (move_uploaded_file($tmpName, $destination)) {
            $imageUrl = '/uploads/' . $filename;
        } else {
            json_response(['success' => false, 'message' => 'Failed to save uploaded file'], 500);
        }
    }

    if (empty($imageUrl)) {
        json_response(['success' => false, 'message' => 'Image file or Image URL is required'], 400);
    }

    $proofs = get_proofs();
    $newProof = [
        'id' => time() . rand(100, 999),
        'title' => $title,
        'tag' => $tag,
        'type' => $type,
        'image_url' => $imageUrl,
        'description' => $description,
        'created_at' => date('Y-m-d H:i:s')
    ];

    array_unshift($proofs, $newProof);
    save_json_data('proofs.json', $proofs);

    json_response(['success' => true, 'message' => 'Proof added successfully', 'proof' => $newProof, 'proofs' => $proofs]);
}

if ($method === 'DELETE') {
    require_admin_auth();
    parse_str(file_get_contents('php://input'), $deleteParams);
    $id = $deleteParams['id'] ?? ($_GET['id'] ?? null);
    if (!$id) {
        json_response(['success' => false, 'message' => 'Proof ID required'], 400);
    }
    $proofs = get_proofs();
    $filtered = array_values(array_filter($proofs, function($item) use ($id) {
        return strval($item['id']) !== strval($id);
    }));
    save_json_data('proofs.json', $filtered);
    json_response(['success' => true, 'message' => 'Proof removed successfully', 'proofs' => $filtered]);
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
