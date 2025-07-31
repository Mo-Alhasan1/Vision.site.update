<?php
session_start();
require_once 'db.php';

function getFirstImageFromGallery($galleryStr) {
    if (!$galleryStr) return null;
    $images = array_map('trim', explode(',', $galleryStr));
    return $images[0] ?? null;
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    $products = [];
} else {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = "Shopping Cart – Vision Fashion";
include 'header.php';
?>

<main class="container my-5">
  <h1>Shopping Cart</h1>

  <?php if (empty($products)): ?>
    <p>Your cart is empty. <a href="/vision-site/categories.php">Start shopping now!</a></p>
  <?php else: ?>
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Product</th>
          <th style="width: 100px;">Quantity</th>
          <th style="width: 120px;">Price</th>
          <th style="width: 120px;">Subtotal</th>
          <th style="width: 50px;">Remove</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        foreach ($products as $product):
            $id = $product['id'];
            $quantity = $cart[$id];
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;

            $imageFile = $product['image'];
            $imagePath = __DIR__ . "/images/products/$imageFile";

            if (!$imageFile || !file_exists($imagePath)) {
                $firstGalleryImage = getFirstImageFromGallery($product['image_gallery']);
                $galleryImagePath = __DIR__ . "/images/products/$firstGalleryImage";
                if ($firstGalleryImage && file_exists($galleryImagePath)) {
                    $imageFile = $firstGalleryImage;
                } else {
                    $imageFile = 'placeholder.png';
                }
            }
        ?>
          <tr data-id="<?= $id ?>">
            <td>
              <img src="/vision-site/images/products/<?= htmlspecialchars($imageFile) ?>" 
                   alt="<?= htmlspecialchars($product['name']) ?>" 
                   width="60" height="60" 
                   style="object-fit: cover; margin-right: 10px; vertical-align: middle; border-radius: 4px;">
              <?= htmlspecialchars($product['name']) ?>
            </td>
            <td>
              <input type="number" min="0" class="form-control quantity-input" value="<?= $quantity ?>" style="width: 70px;">
            </td>
            <td>$<?= number_format($product['price'], 2) ?></td>
            <td class="line-total">$<?= number_format($subtotal, 2) ?></td>
            <td><button class="btn btn-sm btn-danger remove-btn" title="Remove">&times;</button></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-end fw-bold">Total:</td>
          <td id="grand-total" class="fw-bold">$<?= number_format($total, 2) ?></td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    <div class="d-flex justify-content-between">
      <a href="/vision-site/categories.php" class="btn btn-danger" id="continue-shopping">Continue Shopping</a>
      <a href="/vision-site/checkout.php" class="btn btn-success">Checkout</a>
    </div>
  <?php endif; ?>
</main>

<script>
 async function updateCart(productId, quantity) {
  try {
    const res = await fetch('/vision-site/cart_update.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ productId, quantity })
    });
    const data = await res.json();

    if (!data.success) {
      alert('Failed to update cart');
      return;
    }

    const row = document.querySelector(`tr[data-id="${productId}"]`);

    if (quantity === 0 && row) {
      row.remove();  // Remove row if quantity zero
    } else if (row) {
      // Update subtotal cell
      const subtotalCell = row.querySelector('.line-total');
      subtotalCell.textContent = '$' + (data.productPrice * quantity).toFixed(2);
    }

    // Update grand total
    const grandTotalEl = document.getElementById('grand-total');
    grandTotalEl.textContent = '$' + data.newTotal.toFixed(2);

    // Optionally update cart count in navbar
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) {
      if (data.cartCount > 0) {
        cartCountEl.textContent = data.cartCount;
        cartCountEl.style.display = 'inline-block';
      } else {
        cartCountEl.style.display = 'none';
      }
    }

    // If cart is empty, reload page to show empty message
    if (data.cartCount === 0) {
      location.reload();
    }

  } catch (error) {
    console.error('Error updating cart:', error);
    alert('Error updating cart');
  }
}

// Attach listeners to quantity inputs and remove buttons:
document.querySelectorAll('.quantity-input').forEach(input => {
  input.addEventListener('change', () => {
    let qty = parseInt(input.value);
    if (isNaN(qty) || qty < 0) qty = 0;
    input.value = qty;
    const row = input.closest('tr');
    const productId = parseInt(row.dataset.id);
    updateCart(productId, qty);
  });
});

document.querySelectorAll('.remove-btn').forEach(button => {
  button.addEventListener('click', () => {
    const row = button.closest('tr');
    const productId = parseInt(row.dataset.id);
    updateCart(productId, 0);
  });
});

// Style Continue Shopping button red and fully red on hover
const continueBtn = document.getElementById('continue-shopping');
if (continueBtn) {
  continueBtn.style.backgroundColor = '#d9534f';
  continueBtn.style.borderColor = '#d9534f';
  continueBtn.style.color = 'white';

  continueBtn.addEventListener('mouseover', () => {
    continueBtn.style.backgroundColor = '#b52b2b';
    continueBtn.style.borderColor = '#b52b2b';
  });
  continueBtn.addEventListener('mouseout', () => {
    continueBtn.style.backgroundColor = '#d9534f';
    continueBtn.style.borderColor = '#d9534f';
  });
}
</script>

<?php include 'footer.php'; ?>
