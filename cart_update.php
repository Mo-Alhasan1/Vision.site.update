<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'No JSON received']);
    exit;
}

if (!isset($input['productId'], $input['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$productId = (int)$input['productId'];
$quantity = (int)$input['quantity'];

if ($productId <= 0 || $quantity < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid productId or quantity']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($quantity === 0) {
    unset($_SESSION['cart'][$productId]);
} else {
    $_SESSION['cart'][$productId] = $quantity;
}

if (empty($_SESSION['cart'])) {
    echo json_encode([
        'success' => true,
        'newTotal' => 0,
        'cartCount' => 0,
        'productPrice' => 0
    ]);
    exit;
}

$placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
$stmt->execute(array_keys($_SESSION['cart']));
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$newTotal = 0;
$cartCount = 0;
$productPrice = 0;

foreach ($products as $product) {
    $pid = $product['id'];
    $price = (float)$product['price'];
    $qty = $_SESSION['cart'][$pid] ?? 0;
    $newTotal += $price * $qty;
    $cartCount += $qty;
    if ($pid === $productId) {
        $productPrice = $price;
    }
}

echo json_encode([
    'success' => true,
    'newTotal' => $newTotal,
    'cartCount' => $cartCount,
    'productPrice' => $productPrice
]);
