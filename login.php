<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Vision Fashion</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="styles.css" />
</head>
<body>

  <!-- Header (copy your site's header code, with Login link active) -->
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
              <li class="nav-item">
                <a class="nav-link active" href="login.php"><i class="fas fa-user"></i> Login</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>

  <!-- Login Form -->
  <section class="container my-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login to Your Account</h2>

    <?php if (!empty($_SESSION['login_error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?php 
          echo htmlspecialchars($_SESSION['login_error']);
          unset($_SESSION['login_error']);
        ?>
      </div>
    <?php endif; ?>

    <form action="login_handler.php" method="POST" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          placeholder="Enter your email"
          required
        />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          name="password"
          placeholder="Enter your password"
          required
        />
      </div>

      <button type="submit" class="btn btn-danger w-100">Login</button>
    </form>

    <p class="mt-3 text-center">
      Don't have an account? <a href="register.php">Register here</a>
    </p>
  </section>

  <!-- Footer (copy your site's footer here) -->
  <footer class="footer text-white bg-dark py-4">
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
