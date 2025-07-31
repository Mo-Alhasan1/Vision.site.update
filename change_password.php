<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$current_password || !$new_password || !$confirm_password) {
        $errors[] = "Please fill in all fields.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current_password, $user['password'])) {
            $errors[] = "Current password is incorrect.";
        } else {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
            $update->execute(['password' => $new_hash, 'id' => $_SESSION['user_id']]);
            $success = "Password updated successfully.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Change Password - Vision Fashion</title>
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
          <input class="form-control search-input" type="search" placeholder="Search" aria-label="Search">
        </form>

        <a href="index.html" class="mx-auto">
          <img src="images/Vision_logo.png" alt="Vision Logo" class="navbar-logo">
        </a>

        <nav class="navbar navbar-expand-md navbar-dark p-0">
          <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                  aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item"><a class="nav-link" href="index.html">Home Page</a></li>
              <li class="nav-item"><a class="nav-link" href="categories.php">Catalog</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Get Help</a></li>
              <li class="nav-item">
                <a class="nav-link" href="profile.php"><i class="fas fa-user"></i> Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>

  <!-- Change Password Form -->
  <section class="container my-5" style="max-width: 400px;">
    <h2>Change Password</h2>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <label for="current_password" class="form-label">Current Password</label>
        <input type="password" class="form-control" id="current_password" name="current_password" required />
      </div>

      <div class="mb-3">
        <label for="new_password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6" />
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm New Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" />
      </div>

      <button type="submit" class="btn btn-primary w-100">Change Password</button>
    </form>

    <a href="profile.php" class="btn btn-link mt-3">Back to Profile</a>
  </section>

  <!-- Footer -->
  <footer class="footer text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-links">
          <a href="#">Who we are</a>
          <a href="#">FAQ</a>
          <a href="#">Return Policy</a>
          <a href="#">Contact</a>
        </div>
        <div class="col-md-4 text-center footer-logo">
          <img src="images/Vision_logo.png" alt="Vision Logo">
        </div>
        <div class="col-md-4 text-center">
          <a href="payment_methods.php">
            <img src="images/payments.png" alt="Payment Options" class="img-fluid payment-options">
          </a>
        </div>
      </div>
      <p class="text-center mt-3">&copy; 2025 VISION. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
