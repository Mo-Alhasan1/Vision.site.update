<?php
session_start();
$pageTitle = "Contact Us – Vision Fashion";
include 'header.php';
?>

<main class="container my-5">
  <h1 class="text-center mb-4">Contact Us</h1>

  <div class="row">
    <!-- Contact Form -->
    <div class="col-md-7">
      <form action="mailto:support@visionwear.com" method="POST" enctype="text/plain">
        <div class="mb-3">
          <label for="name" class="form-label fw-bold">Your Name</label>
          <input type="text" class="form-control" id="name" name="Name" placeholder="John Doe" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label fw-bold">Your Email</label>
          <input type="email" class="form-control" id="email" name="Email" placeholder="you@example.com" required>
        </div>
        <div class="mb-3">
          <label for="subject" class="form-label fw-bold">Subject</label>
          <input type="text" class="form-control" id="subject" name="Subject" placeholder="Order issue, feedback, etc." required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label fw-bold">Message</label>
          <textarea class="form-control" id="message" name="Message" rows="5" placeholder="Write your message here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Send Message</button>
      </form>
    </div>

    <!-- Contact Info -->
    <div class="col-md-5 mt-4 mt-md-0">
      <div class="bg-light p-4 rounded shadow-sm h-100">
        <h4 class="mb-3 text-danger"><i class="fas fa-envelope me-2"></i>Support Info</h4>
        <p>Have a question or need help with your order? Reach out to us anytime.</p>
        <ul class="list-unstyled">
          <li><strong>Email:</strong> <a href="mailto:support@visionwear.com">support@visionwear.com</a></li>
          <li><strong>Response time:</strong> Within 24–48 hours</li>
        </ul>

        <hr>

        <h5 class="text-danger"><i class="fas fa-clock me-2"></i>Customer Hours</h5>
        <p>Monday – Friday: 9:00 AM to 6:00 PM (CET)<br>
        Saturday – Sunday: Closed</p>

        <p class="mt-3 text-muted small">Note: For returns, please visit our <a href="/vision-site/return_policy.php">Return Policy</a>.</p>
      </div>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
