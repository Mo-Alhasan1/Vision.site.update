<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../db.php';

// Fetch all products with category name
$stmt = $pdo->query("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Manage Products";

// Include header
include 'includes/header.php';
?>

<main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
<?php
$backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
?>
<a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn-danger mb-3">&larr; Back</a>

        <h1>Products</h1>
        <a href="product_add.php" class="btn btn-success">+ Add New Product</a>
    </div>

    <?php if (count($products) === 0): ?>
        <div class="alert alert-info">No products found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php
                                    // Use image column, fallback to placeholder.png
                                    $img = trim($p['image']) ?: 'placeholder.png';
                                ?>
                                <img src="/vision-site/images/products/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>" width="60" height="60" style="object-fit: cover;">
                            </td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td>$<?= number_format($p['price'], 2) ?></td>
                            <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                            <td>
                                <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
