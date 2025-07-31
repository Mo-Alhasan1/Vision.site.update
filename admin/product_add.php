<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../db.php';

// Fetch categories for dropdown
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category_id = $_POST['category_id'] ?? null;

    if ($name === '') {
        $errors[] = "Product name is required.";
    }
    if (!is_numeric($price) || $price < 0) {
        $errors[] = "Price must be a valid non-negative number.";
    }
    if ($category_id === null || $category_id == '') {
        $errors[] = "Please select a category.";
    }

    // Handle multiple file uploads
    $imageFilenames = [];

    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $uploadDir = __DIR__ . '/../images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $error = $_FILES['images']['error'][$key];
            $type = $_FILES['images']['type'][$key];
            $originalName = $_FILES['images']['name'][$key];

            if ($error === UPLOAD_ERR_OK) {
                if (in_array($type, $allowedTypes)) {
                    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                    $uniqueName = uniqid('prod_') . '.' . $ext;
                    $uploadFile = $uploadDir . $uniqueName;

                    if (move_uploaded_file($tmpName, $uploadFile)) {
                        $imageFilenames[] = $uniqueName;
                    } else {
                        $errors[] = "Failed to upload image: $originalName";
                    }
                } else {
                    $errors[] = "File type not allowed for image: $originalName";
                }
            } else {
                $errors[] = "Error uploading image: $originalName";
            }
        }
    } else {
        $errors[] = "At least one product image is required.";
    }

    if (empty($errors)) {
        // Use the first image as main image
        $mainImage = $imageFilenames[0] ?? '';

        // Save all images filenames as comma separated string for image_gallery
        $imageGallery = implode(',', $imageFilenames);

        // Insert product into database
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category_id, image, image_gallery) VALUES (?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $name,
            $description,
            $price,
            $category_id,
            $mainImage,
            $imageGallery
        ]);

        if ($result) {
            header("Location: products.php?msg=Product added successfully");
            exit;
        } else {
            $errors[] = "Failed to add product. Please try again.";
        }
    }
}

$pageTitle = "Add New Product";
include 'includes/header.php';
?>

<main class="container my-5">
    <h1>Add New Product</h1>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="product_add.php" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
            <input type="number" name="price" id="price" min="0" step="0.01" class="form-control" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Product Images <span class="text-danger">*</span></label>
            <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple required>
            <small class="form-text text-muted">You can upload multiple images. Allowed formats: JPG, PNG, GIF.</small>
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="products.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
