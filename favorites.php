<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: /vision-site/login.php');
    exit;
}

$user_email = $_SESSION['user_email'];

$stmt = $pdo->prepare("
    SELECT p.*
    FROM products p
    INNER JOIN favorites f ON p.id = f.product_id
    WHERE f.user_email = ?
");
$stmt->execute([$user_email]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Your Favorites – Vision Fashion";
include 'header.php';
?>

<main class="container my-5">
  <h1>Your Favorite Products</h1>

  <?php if (count($favorites) === 0): ?>
    <p>You have no favorite products yet. <a href="/vision-site/categories.php">Browse the catalog</a> to add some!</p>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="favorites-list">
      <?php foreach ($favorites as $product): 
        $images = array_filter(array_map('trim', explode(',', $product['image'])));
        $firstImage = $images[0] ?? 'default.png'; 
      ?>
        <div class="col" data-product-id="<?= htmlspecialchars($product['id']) ?>">
          <div class="card h-100 shadow-sm">
            <img src="/vision-site/images/products/<?= htmlspecialchars($firstImage) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($product['name']) ?>" 
                 style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text text-truncate"><?= htmlspecialchars($product['description']) ?></p>
              <p class="text-danger fw-bold mt-auto">$<?= number_format($product['price'], 2) ?></p>
              <a href="/vision-site/product_view.php?id=<?= htmlspecialchars($product['id']) ?>" 
                 class="btn btn-outline-danger mt-2 mb-2">View Product</a>
              <button class="btn btn-outline-danger remove-favorite-btn">Remove from Favorites</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.remove-favorite-btn').forEach(button => {
    button.addEventListener('click', async () => {
      const card = button.closest('[data-product-id]');
      const productId = card.getAttribute('data-product-id');

      // Removed confirmation dialog here

      try {
        const response = await fetch('/vision-site/favorite_remove.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({ productId: productId })
        });
        const data = await response.json();

        if (data.success) {
          // Remove product card from the DOM
          card.remove();

          // If no favorites left, reload the page
          if (document.querySelectorAll('#favorites-list > div').length === 0) {
            location.reload();
          }
        } else {
          alert('Failed to remove from favorites.');
        }
      } catch {
        alert('Error occurred while removing from favorites.');
      }
    });
  });
});
</script>


<?php include 'footer.php'; ?>
