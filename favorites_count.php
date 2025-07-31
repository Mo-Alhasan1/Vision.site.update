<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$user_email = $_SESSION['user_email'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_email = ?");
$stmt->execute([$user_email]);
$count = (int)$stmt->fetchColumn();

echo json_encode(['count' => $count]);
?>
