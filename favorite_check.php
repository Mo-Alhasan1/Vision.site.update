<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'isFavorite' => false];

// Check user login
if (!isset($_SESSION['user_email'])) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

// Validate product ID from GET
$productId = $_GET['id'] ?? null;
if (!$productId || !is_numeric($productId)) {
    $response['message'] = 'Invalid or missing product ID';
    echo json_encode($response);
    exit;
}

$user_email = $_SESSION['user_email'];

try {
    // Prepare and execute query to check favorite
    $stmt = $pdo->prepare("SELECT 1 FROM favorites WHERE user_email = ? AND product_id = ?");
    $stmt->execute([$user_email, $productId]);
    $exists = (bool)$stmt->fetchColumn();

    $response['success'] = true;
    $response['isFavorite'] = $exists;

} catch (PDOException $e) {
    // Optional: Log error for debugging
    // error_log("Favorite check DB error: " . $e->getMessage());

    $response['message'] = 'Database error occurred';
}

echo json_encode($response);
