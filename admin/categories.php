<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../db.php';

// Handle delete action
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$delId]);
        header("Location: categories.php?msg=Category deleted successfully");
        exit;
    }
}

// Fetch all categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Manage Categories";
include 'includes/header.php';
?>

<main class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <?php
$backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
?>
<a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn-danger mb-3">&larr; Back</a>

        <h1>Categories</h1>
        <a href="category_add.php" class="btn btn-success">+ Add New Category</a>
    </div>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info">No categories found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td>
                                <?php if ($cat['image']): ?>
                                    <img src="/vision-site/images/categories/<?= htmlspecialchars($cat['image']) ?>" alt="" style="max-height:50px; object-fit: contain;">
                                <?php else: ?>
                                    &mdash;
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td><?= htmlspecialchars($cat['slug']) ?></td>
                            <td>
                                <a href="category_edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
