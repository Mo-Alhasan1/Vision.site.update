<?php
session_start();
require_once 'db.php'; // your PDO connection

// Get the search query from the user
$q = trim($_GET['q'] ?? ''); // If not set, default to empty string
$results = [];

if ($q !== '') {
  // SQL: Find matches in product name, description, or category name
  $stmt = $pdo->prepare("
    SELECT products.* 
    FROM products
    JOIN categories ON products.category_id = categories.id
    WHERE products.name LIKE ?
       OR products.description LIKE ?
       OR categories.name LIKE ?
  ");

  // Use the same pattern for all 3 fields
  $searchTerm = "%$q%";
  $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);

  // Fetch results as associative array
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Set a custom page title
$pageTitle = "Search Results";
include 'header.php';
?>

<main class="container my-5">
  <h2 class="mb-4">Search Results for "<?= htmlspecialchars($q) ?>"</h2>

  <?php if (count($results) > 0): ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($results as $product): ?>
        <?php
          // Get first image from comma-separated images string
          $imageList = array_filter(array_map('trim', explode(',', $product['image'])));
          $firstImage = $imageList ? $imageList[0] : 'default-image.png'; // fallback if no image
        ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="/vision-site/images/products/<?= htmlspecialchars($firstImage) ?>" 
                 class="card-img-top" 
                 alt="<?= htmlspecialchars($product['name']) ?>" 
                 style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
              <p class="text-danger fw-bold">$<?= number_format($product['price'], 2) ?></p>
              <a href="product_view.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-outline-danger w-100">View Product</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No results found.</p>
  <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
