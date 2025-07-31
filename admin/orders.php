<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Manage Orders";
include 'includes/header.php';
?>

<main class="container my-5">
    <?php
$backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
?>
<a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn-danger mb-3">&larr; Back</a>

    <h1>Orders (Coming Soon)</h1>
    <p>This page will be built soon.</p>
</main>

<?php include 'includes/footer.php'; ?>
