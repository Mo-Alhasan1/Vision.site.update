<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../db.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: products.php");
    exit;
}

// Delete product
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$result = $stmt->execute([$id]);

if ($result) {
    header("Location: products.php?msg=Product deleted successfully");
    exit;
} else {
    header("Location: products.php?error=Failed to delete product");
    exit;
}
