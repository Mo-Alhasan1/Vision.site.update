<?php
session_start();
require_once 'db.php';  // PDO connection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch addresses
$stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = :user_id");
$stmt->execute(['user_id' => $userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group addresses by type
$shippingAddresses = array_filter($addresses, fn($a) => $a['type'] === 'shipping');
$billingAddresses = array_filter($addresses, fn($a) => $a['type'] === 'billing');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Profile - Vision Fashion</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

  <!-- Header -->
  <header class="bg-black py-3">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <form class="d-flex">
          <input class="form-control search-input" type="search" placeholder="Search" aria-label="Search" />
        </form>
        <a href="index.php" class="mx-auto">
          <img src="images/Vision_logo.png" alt="Vision Logo" class="navbar-logo" />
        </a>
        <nav class="navbar navbar-expand-md navbar-dark p-0">
          <button
            class="navbar-toggler ms-2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navMenu"
            aria-controls="navMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item"><a class="nav-link" href="index.php">Home Page</a></li>
              <li class="nav-item"><a class="nav-link" href="categories.php">Catalog</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Get Help</a></li>
              <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
              <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>

  <main class="container my-5" style="max-width: 900px;">
    <h2 class="mb-4 text-center">My Profile</h2>

    <!-- Display messages -->
    <?php if (!empty($_SESSION['profile_message'])): ?>
      <div class="alert alert-info"><?=htmlspecialchars($_SESSION['profile_message'])?></div>
      <?php unset($_SESSION['profile_message']); ?>
    <?php endif; ?>

    <!-- User Info Update Form -->
    <form action="profile_update.php" method="POST" enctype="multipart/form-data" class="mb-5">
      <h4>User Information</h4>

      <div class="mb-3 text-center">
        <?php if ($user['avatar']): ?>
          <img src="uploads/avatars/<?=htmlspecialchars($user['avatar'])?>" alt="Avatar" class="rounded-circle" style="width:100px; height:100px; object-fit:cover;">
        <?php else: ?>
          <i class="fas fa-user-circle fa-7x text-muted"></i>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label for="avatar" class="form-label">Change Avatar</label>
        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" />
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="first_name" class="form-label">First Name</label>
          <input type="text" class="form-control" id="first_name" name="first_name" value="<?=htmlspecialchars($user['first_name'])?>" required />
        </div>
        <div class="col-md-6 mb-3">
          <label for="last_name" class="form-label">Last Name</label>
          <input type="text" class="form-control" id="last_name" name="last_name" value="<?=htmlspecialchars($user['last_name'])?>" required />
        </div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?=htmlspecialchars($user['email'])?>" required />
      </div>

      <div class="mb-3">
        <label for="birth_date" class="form-label">Birth Date</label>
        <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?=htmlspecialchars($user['birth_date'])?>" />
      </div>

      <div class="mb-3">
        <label for="gender" class="form-label">Gender Preference</label>
        <select class="form-select" id="gender" name="gender">
          <option value="" <?= $user['gender'] == '' ? 'selected' : '' ?>>Prefer not to say</option>
          <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
          <option value="other" <?= $user['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
        </select>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="phone_prefix" class="form-label">Phone Prefix</label>
          <input type="text" class="form-control" id="phone_prefix" name="phone_prefix" value="<?=htmlspecialchars($user['phone_prefix'])?>" />
        </div>
        <div class="col-md-8 mb-3">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?=htmlspecialchars($user['phone_number'])?>" />
        </div>
      </div>

      <button type="submit" class="btn btn-danger">Update Profile</button>
    </form>

    <!-- Addresses -->
    <h4>Addresses</h4>

    <div class="row">
      <div class="col-md-6">
        <h5>Shipping Addresses</h5>
        <?php if (count($shippingAddresses) === 0): ?>
          <p>No shipping addresses saved.</p>
        <?php else: ?>
          <?php foreach ($shippingAddresses as $addr): ?>
            <div class="border p-3 mb-3">
              <p><strong>Street:</strong> <?=htmlspecialchars($addr['street'])?></p>
              <p><strong>City:</strong> <?=htmlspecialchars($addr['city'])?></p>
              <p><strong>Postal Code:</strong> <?=htmlspecialchars($addr['postal_code'])?></p>
              <p><strong>Country:</strong> <?=htmlspecialchars($addr['country'])?></p>
              <p><strong>Phone:</strong> <?=htmlspecialchars($addr['phone_prefix'])?> <?=htmlspecialchars($addr['phone_number'])?></p>
              <p><strong>Default:</strong> <?= $addr['is_default'] ? 'Yes' : 'No' ?></p>
              <a href="address_edit.php?id=<?=$addr['id']?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="address_delete.php?id=<?=$addr['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this address?');">Delete</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <a href="address_add.php?type=shipping" class="btn btn-outline-danger">Add Shipping Address</a>
      </div>

      <div class="col-md-6">
        <h5>Billing Addresses</h5>
        <?php if (count($billingAddresses) === 0): ?>
          <p>No billing addresses saved.</p>
        <?php else: ?>
          <?php foreach ($billingAddresses as $addr): ?>
            <div class="border p-3 mb-3">
              <p><strong>Street:</strong> <?=htmlspecialchars($addr['street'])?></p>
              <p><strong>City:</strong> <?=htmlspecialchars($addr['city'])?></p>
              <p><strong>Postal Code:</strong> <?=htmlspecialchars($addr['postal_code'])?></p>
              <p><strong>Country:</strong> <?=htmlspecialchars($addr['country'])?></p>
              <p><strong>Phone:</strong> <?=htmlspecialchars($addr['phone_prefix'])?> <?=htmlspecialchars($addr['phone_number'])?></p>
              <p><strong>Default:</strong> <?= $addr['is_default'] ? 'Yes' : 'No' ?></p>
              <a href="address_edit.php?id=<?=$addr['id']?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="address_delete.php?id=<?=$addr['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this address?');">Delete</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <a href="address_add.php?type=billing" class="btn btn-outline-danger">Add Billing Address</a>
      </div>
    </div>

  </main>

  <!-- Footer -->
  <footer class="footer text-white bg-dark py-4 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-links">
          <a href="#">Who we are</a>
          <a href="#">FAQ</a>
          <a href="#">Return Policy</a>
          <a href="#">Contact</a>
        </div>
        <div class="col-md-4 text-center footer-logo">
          <img src="images/Vision_logo.png" alt="Vision Logo" />
        </div>
        <div class="col-md-4 text-center">
          <a href="payment_methods.php">
            <img src="images/payments.png" alt="Payment Options" class="img-fluid payment-options" />
          </a>
        </div>
      </div>
      <p class="text-center mt-3">&copy; 2025 VISION. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
