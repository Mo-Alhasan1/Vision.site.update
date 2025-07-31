<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['id'] ?? null;

if (!$productId || !is_numeric($productId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$productId = (int)$productId;

// Init cart if needed
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Increment quantity or add new
if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]++;
} else {
    $_SESSION['cart'][$productId] = 1;
}

// Return updated cart count (sum of quantities)
echo json_encode(['success' => true, 'cartCount' => array_sum($_SESSION['cart'])]);
