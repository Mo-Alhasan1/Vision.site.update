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

$stmt = $pdo->prepare("SELECT * FROM addresses WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $addressId, 'user_id' => $userId]);
$address = $stmt->fetch();

if (!$address) {
    $_SESSION['profile_message'] = "Address not found or access denied.";
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $country = trim($_POST['country']);
    $phone_prefix = trim($_POST['phone_prefix']);
    $phone_number = trim($_POST['phone_number']);
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE addresses SET 
        street = :street, city = :city, postal_code = :postal_code, 
        country = :country, phone_prefix = :phone_prefix, 
        phone_number = :phone_number, is_default = :is_default 
        WHERE id = :id AND user_id = :user_id");

    $stmt->execute([
        'street' => $street,
        'city' => $city,
        'postal_code' => $postal_code,
        'country' => $country,
        'phone_prefix' => $phone_prefix,
        'phone_number' => $phone_number,
        'is_default' => $is_default,
        'id' => $addressId,
        'user_id' => $userId
    ]);

    $_SESSION['profile_message'] = "Address updated successfully.";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Address</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2>Edit Address</h2>
  <form method="POST">
    <div class="mb-3"><label>Street</label><input type="text" name="street" class="form-control" value="<?=htmlspecialchars($address['street'])?>" required></div>
    <div class="mb-3"><label>City</label><input type="text" name="city" class="form-control" value="<?=htmlspecialchars($address['city'])?>" required></div>
    <div class="mb-3"><label>Postal Code</label><input type="text" name="postal_code" class="form-control" value="<?=htmlspecialchars($address['postal_code'])?>" required></div>
    <div class="mb-3"><label>Country</label><input type="text" name="country" class="form-control" value="<?=htmlspecialchars($address['country'])?>" required></div>
    <div class="mb-3"><label>Phone Prefix</label><input type="text" name="phone_prefix" class="form-control" value="<?=htmlspecialchars($address['phone_prefix'])?>"></div>
    <div class="mb-3"><label>Phone Number</label><input type="text" name="phone_number" class="form-control" value="<?=htmlspecialchars($address['phone_number'])?>"></div>
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="is_default" id="is_default" <?= $address['is_default'] ? 'checked' : '' ?>>
      <label class="form-check-label" for="is_default">Set as Default</label>
    </div>
    <button type="submit" class="btn btn-danger">Save Changes</button>
    <a href="profile.php" class="btn btn-secondary">Cancel</a>
  </form>
</body>
</html>
