<?php
require_once dirname(__DIR__) . '/config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET Orders - Admin Only
if ($method === 'GET') {
    require_admin_auth();
    $orders = get_orders();
    json_response(['success' => true, 'orders' => $orders]);
}

// POST Orders - Customer creation OR Admin status update
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $action = $input['action'] ?? 'create';

    // Admin Actions: status update or delete
    if ($action === 'update_status' || $action === 'delete') {
        require_admin_auth();
        $orders = get_orders();
        $orderId = $input['order_id'] ?? ($input['id'] ?? null);

        if (!$orderId) {
            json_response(['success' => false, 'message' => 'Order ID is required'], 400);
        }

        if ($action === 'delete') {
            $filtered = array_values(array_filter($orders, function($o) use ($orderId) {
                return strval($o['id'] ?? $o['order_id']) !== strval($orderId);
            }));
            save_json_data('orders.json', $filtered);
            json_response(['success' => true, 'message' => 'Order removed successfully', 'orders' => $filtered]);
        }

        if ($action === 'update_status') {
            $newStatus = trim($input['status'] ?? 'Approved');
            foreach ($orders as &$order) {
                if (strval($order['id'] ?? $order['order_id']) === strval($orderId)) {
                    $order['status'] = $newStatus;
                    $order['updated_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            save_json_data('orders.json', $orders);
            json_response(['success' => true, 'message' => "Order updated to $newStatus", 'orders' => $orders]);
        }
    }

    // Customer Checkout Order Creation
    $device = trim($input['device'] ?? 'Android');
    $duration = trim($input['duration'] ?? '1');
    $price = trim($input['price'] ?? '80');
    $utr = trim($input['utr'] ?? '');
    $phone = trim($input['phone'] ?? '');

    if (empty($utr)) {
        json_response(['success' => false, 'message' => 'Payment UTR / Reference number is required'], 400);
    }

    $orders = get_orders();
    $newOrder = [
        'id' => 'ORD-' . strtoupper(substr(uniqid(), -6)),
        'order_id' => 'ORD-' . strtoupper(substr(uniqid(), -6)),
        'device' => $device,
        'duration' => $duration . ' Days',
        'price' => '₹' . $price,
        'utr' => $utr,
        'phone' => $phone,
        'status' => 'Pending Verification',
        'created_at' => date('Y-m-d H:i:s')
    ];

    array_unshift($orders, $newOrder);
    save_json_data('orders.json', $orders);

    json_response([
        'success' => true,
        'message' => 'Order submitted successfully! Our team will verify and release your VIP key.',
        'order' => $newOrder
    ]);
}

json_response(['success' => false, 'message' => 'Method not allowed'], 405);
