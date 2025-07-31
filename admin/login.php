<?php
session_start();
require_once '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($email && $pass) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($pass, $admin['password'])) {
            // Success — login and update last_active
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $admin['email'];

            // Update last_active timestamp
            $pdo->prepare("UPDATE admins SET last_active = NOW() WHERE id = ?")->execute([$admin['id']]);

            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
  <form method="POST" class="bg-white p-4 rounded shadow" style="width: 320px;">
    <h4 class="mb-3 text-center">Admin Login</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="mb-2">
      <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button class="btn btn-danger w-100">Login</button>
  </form>
</body>
</html>
