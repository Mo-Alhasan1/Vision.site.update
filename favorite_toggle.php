<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit;
}

$user_email = $_SESSION['user_email'];

// Read JSON POST data
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['id'] ?? null;

if (!$product_id || !is_numeric($product_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_email = ? AND product_id = ?");
    $stmt->execute([$user_email, $product_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Delete favorite
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_email = ? AND product_id = ?");
        $stmt->execute([$user_email, $product_id]);
        echo json_encode(['success' => true, 'favorited' => false]);
    } else {
        // Insert favorite
        $stmt = $pdo->prepare("INSERT INTO favorites (user_email, product_id) VALUES (?, ?)");
        $stmt->execute([$user_email, $product_id]);
        echo json_encode(['success' => true, 'favorited' => true]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
