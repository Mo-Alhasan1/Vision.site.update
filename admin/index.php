<?php
require_once 'includes/header.php';
require_once '../db.php';

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn(); // Add this line
?>

<style>
  .dashboard-link {
    text-decoration: none;
    color: inherit;
    transition: color 0.3s ease;
  }
  .dashboard-link .card {
    border: 1px solid transparent;
    transition: 
      border-color 0.3s ease, 
      color 0.3s ease, 
      box-shadow 0.3s ease,
      transform 0.3s ease;
    color: inherit;
  }
  .dashboard-link:hover {
    color: #dc3545;
  }
  .dashboard-link:hover .card {
    border-color: #dc3545;
    color: #dc3545;
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
    transform: translateY(-5px);
    cursor: pointer;
  }
  .dashboard-link:hover .card h4,
  .dashboard-link:hover .card p {
    color: #dc3545;
  }
</style>

<main class="container my-5">
  <h1>Admin Dashboard</h1>
  <div class="row text-center">
    <div class="col-md-3">
      <a href="products.php" class="dashboard-link">
        <div class="card p-3 shadow-sm">
          <h4><?= $totalProducts ?></h4>
          <p>Products</p>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="categories.php" class="dashboard-link">
        <div class="card p-3 shadow-sm">
          <h4><?= $totalCategories ?></h4>
          <p>Categories</p>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="orders.php" class="dashboard-link">
        <div class="card p-3 shadow-sm">
          <h4><?= $totalOrders ?></h4>
          <p>Orders</p>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="admins.php" class="dashboard-link">
        <div class="card p-3 shadow-sm">
          <h4><?= $totalAdmins ?></h4>
          <p>Admin(s)</p>
        </div>
      </a>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
