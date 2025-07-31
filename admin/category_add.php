<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../db.php';

$errors = [];
$imageName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if ($name === '') {
        $errors[] = "Category name is required.";
    }
    if ($slug === '') {
        $errors[] = "Slug is required.";
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                $uploadDir = __DIR__ . '/../images/categories/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Sanitize original filename
                $originalName = basename($_FILES['image']['name']);
                $originalName = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $originalName);

                // Avoid filename collisions
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
    } else {
        $errors[] = "Category image is required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, image) VALUES (?, ?, ?)");
        $result = $stmt->execute([$name, $slug, $imageName]);

        if ($result) {
            header("Location: categories.php?msg=Category added successfully");
            exit;
        } else {
            $errors[] = "Failed to add category.";
        }
    }
}

$pageTitle = "Add New Category";
include 'includes/header.php';
?>

<main class="container my-5">
    <h1>Add New Category</h1>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" id="slug" name="slug" class="form-control" required value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Category Image <span class="text-danger">*</span></label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
            <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF.</small>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
        <a href="categories.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
