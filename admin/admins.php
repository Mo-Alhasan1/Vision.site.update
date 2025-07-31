<?php
session_start();

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'includes/header.php';
require_once '../db.php';

// Define "active" as last_active within last 30 minutes
$activeThreshold = date('Y-m-d H:i:s', time() - 30*60);

$stmt = $pdo->prepare("SELECT email, last_active FROM admins WHERE last_active >= ? ORDER BY email");
$stmt->execute([$activeThreshold]);
$activeAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Active Admins";
?>

<main class="container my-5">
    <?php
$backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';
?>
<a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn-danger mb-3">&larr; Back</a>

    <h1>Active Admins</h1>

    <?php if (empty($activeAdmins)): ?>
        <div class="alert alert-info">No active admins found.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Email</th>
                    <th>Last Active</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activeAdmins as $admin): ?>
                <tr>
                    <td><?= htmlspecialchars($admin['email']) ?></td>
                    <td><?= htmlspecialchars($admin['last_active']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
