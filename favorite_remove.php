<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_email'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = isset($input['productId']) ? (int)$input['productId'] : 0;

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$user_email = $_SESSION['user_email'];

$stmt = $pdo->prepare("DELETE FROM favorites WHERE user_email = ? AND product_id = ?");
$success = $stmt->execute([$user_email, $productId]);

echo json_encode(['success' => $success]);
