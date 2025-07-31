<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Vision Fashion</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="styles.css" />
  
  <!-- For Google reCAPTCHA v2 (replace with your site key) -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
              <li class="nav-item">
                <a class="nav-link active" href="register.php"><i class="fas fa-user-plus"></i> Register</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>

  <!-- Register Form -->
  <section class="container my-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Create Your Account</h2>

    <?php if (!empty($_SESSION['register_error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?php 
          echo htmlspecialchars($_SESSION['register_error']);
          unset($_SESSION['register_error']);
        ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['register_success'])): ?>
      <div class="alert alert-success" role="alert">
        <?php 
          echo htmlspecialchars($_SESSION['register_success']);
          unset($_SESSION['register_success']);
        ?>
      </div>
    <?php endif; ?>

    <form action="register_handler.php" method="POST" novalidate>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="first_name" class="form-label">First Name</label>
          <input
            type="text"
            class="form-control"
            id="first_name"
            name="first_name"
            placeholder="First Name"
            required
          />
        </div>
        <div class="col-md-6 mb-3">
          <label for="last_name" class="form-label">Last Name</label>
          <input
            type="text"
            class="form-control"
            id="last_name"
            name="last_name"
            placeholder="Last Name"
            required
          />
        </div>
      </div>

      <div class="mb-3">
        <label for="birth_date" class="form-label">Birth Date</label>
        <input
          type="date"
          class="form-control"
          id="birth_date"
          name="birth_date"
          required
        />
      </div>

      <div class="mb-3">
        <label for="street" class="form-label">Street Address</label>
        <input
          type="text"
          class="form-control"
          id="street"
          name="street"
          placeholder="123 Main St"
          required
        />
      </div>

      <div class="row">
        <div class="col-md-5 mb-3">
          <label for="city" class="form-label">City</label>
          <input
            type="text"
            class="form-control"
            id="city"
            name="city"
            placeholder="City"
            required
          />
        </div>
        <div class="col-md-4 mb-3">
          <label for="postal_code" class="form-label">Postal Code</label>
          <input
            type="text"
            class="form-control"
            id="postal_code"
            name="postal_code"
            placeholder="Postal Code"
            required
          />
        </div>
        <div class="col-md-3 mb-3">
          <label for="country" class="form-label">Country</label>
          <input
            type="text"
            class="form-control"
            id="country"
            name="country"
            placeholder="Country"
            required
          />
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 mb-3">
          <label for="prefix" class="form-label">Phone Prefix</label>
          <input
            type="text"
            class="form-control"
            id="prefix"
            name="prefix"
            placeholder="+1"
            required
          />
        </div>
        <div class="col-md-9 mb-3">
          <label for="phone" class="form-label">Phone Number</label>
          <input
            type="tel"
            class="form-control"
            id="phone"
            name="phone"
            placeholder="123-456-7890"
            required
          />
        </div>
      </div>

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
        <label for="password" class="form-label">Password (min 6 chars)</label>
        <input
          type="password"
          class="form-control"
          id="password"
          name="password"
          minlength="6"
          placeholder="Enter your password"
          required
        />
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" />
        <label class="form-check-label" for="newsletter">Subscribe to newsletter</label>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="privacy" name="privacy" required />
        <label class="form-check-label" for="privacy">
          I accept the <a href="privacy_policy.html" target="_blank">privacy statement</a>
        </label>
      </div>

      <!-- Google reCAPTCHA -->
      <div class="mb-3">
        <div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY_HERE"></div>
      </div>

      <button type="submit" class="btn btn-danger w-100">Register</button>
    </form>

    <p class="mt-3 text-center">
      Already have an account? <a href="login.php">Login here</a>
    </p>
  </section>

  <!-- Footer -->
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
