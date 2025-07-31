<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Determine type from URL param (shipping or billing)
$type = $_GET['type'] ?? 'shipping';
if (!in_array($type, ['shipping', 'billing'])) {
    $type = 'shipping';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $street = trim($_POST['street'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $phone_prefix = trim($_POST['phone_prefix'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    if (!$street || !$city || !$postal_code || !$country) {
        $_SESSION['address_error'] = "Please fill in all required fields.";
        header("Location: address_add.php?type=$type");
        exit();
    }

    // Insert into addresses table
    $stmt = $pdo->prepare("INSERT INTO addresses (user_id, type, street, city, postal_code, country, phone_prefix, phone_number, is_default) VALUES (:user_id, :type, :street, :city, :postal_code, :country, :phone_prefix, :phone_number, :is_default)");
    $stmt->execute([
        ':user_id' => $userId,
        ':type' => $type,
        ':street' => $street,
        ':city' => $city,
        ':postal_code' => $postal_code,
        ':country' => $country,
        ':phone_prefix' => $phone_prefix,
        ':phone_number' => $phone_number,
        ':is_default' => $is_default,
    ]);

    $_SESSION['profile_message'] = ucfirst($type) . " address added successfully.";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add <?=htmlspecialchars(ucfirst($type))?> Address</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container py-4">

  <h2>Add <?=htmlspecialchars(ucfirst($type))?> Address</h2>

  <?php if (!empty($_SESSION['address_error'])): ?>
    <div class="alert alert-danger">
      <?=htmlspecialchars($_SESSION['address_error'])?>
    </div>
    <?php unset($_SESSION['address_error']); ?>
  <?php endif; ?>

  <form method="POST" action="address_add.php?type=<?=htmlspecialchars($type)?>">
    <div class="mb-3">
      <label for="street" class="form-label">Street</label>
      <input type="text" id="street" name="street" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="city" class="form-label">City</label>
      <input type="text" id="city" name="city" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="postal_code" class="form-label">Postal Code</label>
      <input type="text" id="postal_code" name="postal_code" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="country" class="form-label">Country</label>
      <input type="text" id="country" name="country" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="phone_prefix" class="form-label">Phone Prefix</label>
      <input type="text" id="phone_prefix" name="phone_prefix" class="form-control" />
    </div>
    <div class="mb-3">
      <label for="phone_number" class="form-label">Phone Number</label>
      <input type="tel" id="phone_number" name="phone_number" class="form-control" />
    </div>
    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" id="is_default" name="is_default" />
      <label for="is_default" class="form-check-label">Set as default address</label>
    </div>

    <button type="submit" class="btn btn-danger">Add Address</button>
    <a href="profile.php" class="btn btn-secondary ms-2">Cancel</a>
  </form>

</body>
</html>
