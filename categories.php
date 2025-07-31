<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<main class="container my-5">
  <h1 class="mb-4 text-center">Shop by Category</h1>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">

    <?php foreach ($categories as $cat): ?>
      <div class="col">
        <a href="category_view.php?slug=<?=urlencode($cat['slug'])?>" class="text-decoration-none text-dark">
          <div class="card h-100 shadow-sm">
            <img 
              src="images/<?= htmlspecialchars($cat['image']) ?>" 
              class="card-img-top" 
              alt="<?= htmlspecialchars($cat['name']) ?>" 
              style="height: 180px; object-fit: cover;"
              onerror="this.onerror=null;this.src='images/default-category.png';"
            />
            <div class="card-body text-center">
              <h5 class="card-title"><?= htmlspecialchars($cat['name']) ?></h5>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>

  </div>
</main>

<?php include 'footer.php'; ?>
