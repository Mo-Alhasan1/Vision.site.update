<?php
session_start();
require_once 'db.php';

$productId = $_GET['id'] ?? null;
if (!$productId || !is_numeric($productId)) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    http_response_code(404);
    $pageTitle = "Product Not Found - Vision Fashion";
    include 'header.php';
    echo '
    <main class="container my-5 text-center">
      <h1 class="display-4 text-danger">404 - Product Not Found</h1>
      <p class="lead">Sorry, the product you are looking for does not exist.</p>
      <a href="index.php" class="btn btn-danger">Return to Home</a>
    </main>';
    include 'footer.php';
    exit;
}

$images = array_filter(array_map('trim', explode(',', $product['image'])));

$stmtCat = $pdo->prepare("SELECT slug FROM categories WHERE id = ?");
$stmtCat->execute([$product['category_id']]);
$category = $stmtCat->fetch(PDO::FETCH_ASSOC);
$categorySlug = $category['slug'] ?? '';

$inCart = false;
if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product['id']]) && $_SESSION['cart'][$product['id']] > 0) {
    $inCart = true;
}

$pageTitle = htmlspecialchars($product['name']) . " – Vision Fashion";
include 'header.php';
?>

<main class="container my-5">
  <div class="row">
    <div class="col-md-6">
      <img id="mainProductImage" 
           src="/vision-site/images/products/<?= htmlspecialchars($images[0] ?? 'placeholder.png') ?>" 
           alt="<?= htmlspecialchars($product['name']) ?>" 
           class="img-fluid mb-3" 
           style="height: 500px; object-fit: cover; width: 100%; cursor: pointer;">

      <div class="d-flex flex-wrap gap-2">
        <?php foreach ($images as $img): ?>
          <img src="/vision-site/images/products/<?= htmlspecialchars($img) ?>" 
               alt="<?= htmlspecialchars($product['name']) ?>" 
               class="img-thumbnail" 
               style="height: 80px; width: 80px; object-fit: cover; cursor: pointer;"
               onclick="document.getElementById('mainProductImage').src=this.src;">
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-6">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <h3 class="text-danger">$<?= number_format($product['price'], 2) ?></h3>

      <?php if ($categorySlug): ?>
        <a href="category_view.php?slug=<?= htmlspecialchars($categorySlug) ?>" class="btn btn-outline-danger mb-3">Back to Category</a>
      <?php else: ?>
        <a href="categories.php" class="btn btn-outline-danger mb-3">Back to Catalog</a>
      <?php endif; ?>

      <div class="d-flex mb-3" style="max-width: 220px; gap: 10px;">
        <input type="number" id="quantityInput" class="form-control" min="1" value="1" 
               style="width: 70px; font-size: 1.1rem; padding: 6px 10px;" <?= $inCart ? 'disabled' : '' ?>>
        <button id="addToCartBtn" class="btn btn-danger flex-grow-1" <?= $inCart ? 'disabled' : '' ?>>
          <?= $inCart ? 'Added to Cart' : 'Add to Cart' ?>
        </button>
      </div>

      <a href="/vision-site/cart.php" id="checkoutBtn" class="btn btn-success w-100 mb-3" style="display: <?= $inCart ? 'block' : 'none' ?>;">Checkout</a>

      <?php if (isset($_SESSION['user_email'])): ?>
        <button id="favorite-btn" class="btn btn-outline-danger w-100 mb-3">
          <i class="fas fa-heart"></i> <span id="favorite-text">Add to Favorites</span>
        </button>
      <?php else: ?>
        <a href="/vision-site/login.php" class="btn btn-outline-danger w-100 mb-3">
          <i class="fas fa-heart"></i> Login to add favorites
        </a>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const productId = <?= json_encode((int)$product['id']) ?>;
  const addToCartBtn = document.getElementById('addToCartBtn');
  const quantityInput = document.getElementById('quantityInput');
  const checkoutBtn = document.getElementById('checkoutBtn');

  addToCartBtn.addEventListener('click', function() {
    const quantity = parseInt(quantityInput.value);
    if (isNaN(quantity) || quantity < 1) {
      alert('Please enter a valid quantity (1 or more).');
      return;
    }

    fetch('/vision-site/cart_add.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id: productId, quantity: quantity})
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Update cart count badge if available
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
          cartCountEl.textContent = data.cartCount;
          cartCountEl.style.display = data.cartCount > 0 ? 'inline-block' : 'none';
        }
        if (window.updateCartCount) window.updateCartCount();

        // Show checkout button
        checkoutBtn.style.display = 'block';

        // Disable Add to Cart button and quantity input
        addToCartBtn.disabled = true;
        quantityInput.disabled = true;
        addToCartBtn.textContent = 'Added to Cart';
      } else {
        console.error('Failed to add to cart');
      }
    })
    .catch(() => {
      console.error('Failed to add to cart');
    });
  });

  // Favorite button logic (unchanged)
  const favBtn = document.getElementById('favorite-btn');
  if (favBtn) {
    const favText = document.getElementById('favorite-text');

    function updateButton(isFavorite) {
      if (isFavorite) {
        favBtn.classList.add('btn-danger');
        favBtn.classList.remove('btn-outline-danger');
        favText.textContent = 'Remove from Favorites';
      } else {
        favBtn.classList.remove('btn-danger');
        favBtn.classList.add('btn-outline-danger');
        favText.textContent = 'Add to Favorites';
      }
    }

    fetch(`/vision-site/favorite_check.php?id=${productId}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) updateButton(data.isFavorite);
        else updateButton(false);
      })
      .catch(() => updateButton(false));

    favBtn.addEventListener('click', function() {
      fetch('/vision-site/favorite_toggle.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: productId})
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          updateButton(data.favorited);
          if(window.updateFavoritesCount) window.updateFavoritesCount();
        } else {
          alert('Error updating favorites.');
        }
      })
      .catch(() => alert('Error updating favorites.'));
    });
  }
});
</script>

<?php include 'footer.php'; ?>
