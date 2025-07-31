<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$addressId = $_GET['id'] ?? null;

if (!$addressId) {
    $_SESSION['profile_message'] = "Invalid address ID.";
    header("Location: profile.php");
    exit();
}

$stmt = $pdo->prepare("DELETE FROM addresses WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $addressId, 'user_id' => $userId]);

$_SESSION['profile_message'] = "Address deleted successfully.";
header("Location: profile.php");
exit();
