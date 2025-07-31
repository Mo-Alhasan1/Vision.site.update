<?php
session_start();
$pageTitle = "Frequently Asked Questions (FAQ) – Vision Fashion";
include 'header.php';
?>

<main class="container my-5">
  <h1 class="mb-4 text-center">Frequently Asked Questions (FAQ)</h1>

  <div class="accordion" id="faqAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading1">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
          What products does VISION offer?
        </button>
      </h2>
      <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          We offer a curated collection of fashion essentials including footwear, shirts, accessories, and tailored suits – all designed with purpose and edge.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading2">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
          How do I find the right size?
        </button>
      </h2>
      <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Each product page includes a detailed size guide. If you’re unsure, feel free to contact us — we’re happy to help you choose the right fit.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading3">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
          Do you ship internationally?
        </button>
      </h2>
      <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Yes, we ship worldwide. Shipping costs and delivery times vary based on your location and will be calculated at checkout.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading4">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
          How long does shipping take?
        </button>
      </h2>
      <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Orders are usually processed within 1–3 business days. Delivery time depends on your location, but most orders arrive within 3–10 business days.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading5">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
          Can I return or exchange an item?
        </button>
      </h2>
      <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Yes. You can return or exchange unworn items within 14 days of delivery. Items must be in original condition with tags attached. Read our Return Policy for full details.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading6">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
          Are your products ethically made?
        </button>
      </h2>
      <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          We believe in slow fashion and ethical production. Our collections are designed with care, and we work with partners who share our values in quality and fairness.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading7">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
          How can I track my order?
        </button>
      </h2>
      <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Once your order is shipped, you’ll receive a tracking link via email. You can follow the delivery status anytime.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading8">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
          Do you offer limited drops or collaborations?
        </button>
      </h2>
      <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading8" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Yes. We regularly release limited-edition drops and exclusive collaborations. Subscribe to our newsletter or follow us on social media to stay updated.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading9">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
          Can I get custom tailoring for suits?
        </button>
      </h2>
      <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="heading9" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Currently, all our suits follow standard sizing, but tailored cuts are in development. Stay tuned for upcoming customization options.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading10">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
          How can I contact VISION?
        </button>
      </h2>
      <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="heading10" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          You can reach us through the Contact page or by emailing support@visionwear.com. We typically respond within 24–48 hours.
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>
