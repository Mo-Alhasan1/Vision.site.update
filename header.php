<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$cartCount = 0;
$favoritesCount = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  $cartCount = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Vision Fashion</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="/vision-site/styles.css" />

  <style>
    header > div.container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 8px;
    }

    form.search-form {
      flex: 0 1 230px;
      display: flex;
      gap: 5px;
    }
    form.search-form input[type="search"] {
      flex-grow: 1;
      font-size: 0.8rem;
      height: 30px;
      padding: 4px 8px;
    }
    form.search-form button {
      font-size: 0.8rem;
      padding: 4px 10px;
      height: 30px;
    }

    a.logo-link {
      flex: 0 0 auto;
      margin: 0 6px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .navbar-logo {
      height: 50px;
    }

    nav.navbar {
      flex: 1 1 auto;
      max-width: 380px;
      margin-left: 0 !important;
      padding-left: 0 !important;
    }
    nav .navbar-nav {
      justify-content: flex-end;
      padding-left: 0 !important;
      margin-left: 0 !important;
      gap: 6px;
      font-size: 0.8rem;
      white-space: nowrap;
    }
    nav .nav-item {
      position: relative;
      white-space: nowrap;
    }

    #favorites-count, #cart-count {
      font-size: 0.55rem;
      position: absolute;
      top: -5px;
      right: -7px;
    }

    .navbar-toggler {
      margin-left: 8px;
      padding: 0.2rem 0.4rem;
    }
  </style>
</head>
<body>

<header class="bg-black py-2">
  <div class="container">
    <form class="search-form" action="/vision-site/search.php" method="GET" role="search" aria-label="Site search">
      <input
        class="form-control"
        type="search"
        name="q"
        placeholder="Search"
        required
        aria-label="Search"
      />
      <button class="btn btn-danger" type="submit">Search</button>
    </form>

    <a href="/vision-site/index.php" class="logo-link" aria-label="Vision Fashion Home">
      <img src="/vision-site/images/Vision_logo.png" alt="Vision Logo" class="navbar-logo" />
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
        <ul class="navbar-nav align-items-center w-100">
          <li class="nav-item"><a class="nav-link" href="/vision-site/index.php">Home Page</a></li>
          <li class="nav-item"><a class="nav-link" href="/vision-site/categories.php">Catalog</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Get Help</a></li>

          <?php if (isset($_SESSION['user_email'])):
            $email = $_SESSION['user_email'];
            $username = explode('@', $email)[0];
          ?>
            <li class="nav-item">
              <a class="nav-link" href="/vision-site/profile.php">
                <i class="fas fa-user"></i> Hi! <?= htmlspecialchars($username) ?>
              </a>
            </li>

            <li class="nav-item position-relative">
              <a class="nav-link" href="/vision-site/favorites.php" id="favorites-link" title="My Favorites">
                <i class="fas fa-heart"></i>
                <span id="favorites-count" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" style="<?= $favoritesCount > 0 ? 'display:inline-block;' : 'display:none;' ?>">
                  <?= $favoritesCount ?>
                </span>
                Favorites
              </a>
            </li>

            <li class="nav-item position-relative">
              <a class="nav-link" href="/vision-site/cart.php" id="cart-link" title="Shopping Cart">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" style="<?= $cartCount > 0 ? 'display:inline-block;' : 'display:none;' ?>">
                  <?= $cartCount ?>
                </span>
                Cart
              </a>
            </li>

            <li class="nav-item"><a class="nav-link" href="/vision-site/logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/vision-site/login.php"><i class="fas fa-user"></i> Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  window.updateFavoritesCount = function() {
    const favCountEl = document.getElementById('favorites-count');
    if (!favCountEl) return;

    fetch('/vision-site/favorites_count.php')
      .then(res => res.json())
      .then(data => {
        const count = data.count ?? 0;
        if (count > 0) {
          favCountEl.textContent = count;
          favCountEl.style.display = 'inline-block';
        } else {
          favCountEl.style.display = 'none';
        }
      })
      .catch(() => {
        favCountEl.style.display = 'none';
      });
  };

  window.updateCartCount = function() {
    const cartCountEl = document.getElementById('cart-count');
    if (!cartCountEl) return;

    fetch('/vision-site/cart_count.php')
      .then(res => res.json())
      .then(data => {
        const count = data.count ?? 0;
        if (count > 0) {
          cartCountEl.textContent = count;
          cartCountEl.style.display = 'inline-block';
        } else {
          cartCountEl.style.display = 'none';
        }
      })
      .catch(() => {
        cartCountEl.style.display = 'none';
      });
  };

  document.addEventListener('DOMContentLoaded', function() {
    window.updateFavoritesCount();
    window.updateCartCount();
  });
</script>
