<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../db.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: categories.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header("Location: categories.php");
    exit;
}

$errors = [];
$imageName = $category['image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if ($name === '') $errors[] = "Category name is required.";
    if ($slug === '') $errors[] = "Slug is required.";

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                $uploadDir = __DIR__ . '/../images/categories/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Sanitize filename
                $originalName = basename($_FILES['image']['name']);
                $originalName = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $originalName);

                // Avoid collisions
                $targetPath = $uploadDir . $originalName;
                $fileBase = pathinfo($originalName, PATHINFO_FILENAME);
                $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
                $counter = 1;
                while (file_exists($targetPath)) {
                    $originalName = $fileBase . '_' . $counter . '.' . $fileExt;
                    $targetPath = $uploadDir . $originalName;
                    $counter++;
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Delete old image if exists
                    if ($category['image'] && file_exists($uploadDir . $category['image'])) {
                        unlink($uploadDir . $category['image']);
                    }
                    $imageName = $originalName;
                } else {
                    $errors[] = "Failed to move uploaded image.";
                }
            } else {
                $errors[] = "Only JPG, PNG, GIF images are allowed.";
            }
        } else {
            $errors[] = "Error uploading image.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $imageName, $id]);
        header("Location: categories.php?msg=Category updated successfully");
        exit;
    }
}

$pageTitle = "Edit Category";
include 'includes/header.php';
?>

<main class="container my-5">
    <h1>Edit Category #<?= htmlspecialchars($id) ?></h1>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? $category['name']) ?>">
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" id="slug" name="slug" class="form-control" required value="<?= htmlspecialchars($_POST['slug'] ?? $category['slug']) ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Category Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <?php if ($category['image']): ?>
                <div class="mt-2">
                    <strong>Current Image:</strong><br>
                    <img src="/vision-site/images/categories/<?= htmlspecialchars($category['image']) ?>" alt="" style="max-height:100px; object-fit: contain;">
                </div>
            <?php endif; ?>
            <small class="form-text text-muted">Upload to replace existing image. Allowed: JPG, PNG, GIF.</small>
        </div>
        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="categories.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
