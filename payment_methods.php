<?php
$pageTitle = "Payment Methods | Vision Fashion";
include 'header.php';
?>

<main class="payment-content">
  <h1 class="text-center mb-5">💳 Secure Payment Options</h1>
  
  <div class="payment-method">
    <p>At VISION, we offer a wide range of trusted and secure payment methods to make your shopping experience easy and safe.</p>
  </div>
  
  <div class="payment-method">
    <h3>Credit/Debit Cards</h3>
    <div class="d-flex flex-wrap align-items-center">
      <a href="https://usa.visa.com/" target="_blank"><img src="images/visa logo.png" alt="Visa" class="payment-icon"></a>
      <a href="https://www.mastercard.us/" target="_blank"><img src="images/mastercard logo.png" alt="Mastercard" class="payment-icon"></a>
      <a href="https://www.americanexpress.com/" target="_blank"><img src="images/amex logo (1).png" alt="American Express" class="payment-icon"></a>
    </div>
    <p>We accept all major credit and debit cards with 3D Secure authentication.</p>
  </div>
  
  <div class="payment-method">
    <h3>Digital Wallets</h3>
    <div class="d-flex flex-wrap align-items-center">
      <a href="https://www.paypal.com/" target="_blank"><img src="images/paypal logo1.png" alt="PayPal" class="payment-icon"></a>
      <a href="https://pay.google.com/" target="_blank"><img src="images/googlepay logo (1).png" alt="Google Pay" class="payment-icon"></a>
      <a href="https://www.apple.com/apple-pay/" target="_blank"><img src="images/applepay logo1 (1).png" alt="Apple Pay" class="payment-icon"></a>
    </div>
    <p>Fast and secure payments through your favorite digital wallet services.</p>
  </div>
  
  <div class="payment-method">
    <h3>Other Payment Methods</h3>
    <ul>
      <li><a href="https://www.westernunion.com/" target="_blank" class="payment-link">Western Union Money Transfer</a></li>
      <li>Direct Bank Transfer (Available at checkout)</li>
      <li>Cash on Delivery (Available in select locations)</li>
    </ul>
  </div>
  
  <div class="security-badge">
    <h4>Your Security is Our Priority</h4>
    <p>All payments are processed using SSL encryption and certified gateways, ensuring your personal and financial data is fully protected. We never store your card information.</p>
    <p>Shop with confidence — your security is our priority.</p>
    <img src="images/ssl logos.png" alt="SSL Secure" style="height: 300px; margin-top: 0px;">
  </div>
</main>

<?php include 'footer.php'; ?>
