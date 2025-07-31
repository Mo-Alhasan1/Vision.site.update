<?php
session_start();
require_once 'db.php';

// Get slug from URL safely
$slug = $_GET['slug'] ?? '';
if (!$slug) {
  header("Location: index.php");
  exit;
}

// Fetch category info by slug
$stmt = $pdo->prepare("SELECT id, name FROM categories WHERE slug = ?");
$stmt->execute([$slug]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
  // Category not found — show a nice 404 page consistent with site style
  http_response_code(404);
  $pageTitle = "Category Not Found - Vision Fashion";
  include 'header.php';
  echo '
  <main class="container my-5 text-center">
    <h1 class="display-4 text-danger">404 - Category Not Found</h1>
    <p class="lead">Sorry, the category you are looking for does not exist.</p>
    <a href="index.php" class="btn btn-danger">Return to Home</a>
  </main>';
  include 'footer.php';
  exit;
}

// Fetch products belonging to this category
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY name ASC");
$stmt->execute([$category['id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = htmlspecialchars($category['name']) . " – Vision Fashion";
include 'header.php';
?>

<main class="container my-5">
  <h2 class="mb-4 text-center"><?= htmlspecialchars($category['name']) ?></h2>

  <?php if (count($products) > 0): ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($products as $product): ?>
        <?php
          // Split images and get the first one safely
          $imageList = array_filter(array_map('trim', explode(',', $product['image'])));
          $firstImage = $imageList ? $imageList[0] : 'default-image.png'; // fallback image if empty
        ?>
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <a href="product_view.php?id=<?= htmlspecialchars($product['id']) ?>" class="text-decoration-none text-dark">
              <img src="/vision-site/images/products/<?= htmlspecialchars($firstImage) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="card-text text-truncate"><?= htmlspecialchars($product['description']) ?></p>
              </div>
              <div class="card-footer bg-white border-0">
                <span class="text-danger fw-bold fs-5">$<?= number_format($product['price'], 2) ?></span>
                <button class="btn btn-outline-danger float-end">View Product</button>
              </div>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">
      No products found in this category at the moment. Please check back later.
    </div>
  <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
