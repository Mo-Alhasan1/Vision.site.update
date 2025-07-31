<?php
session_start();
?>
<?php include 'header.php'; ?>

<!-- Hero Banner -->
<section class="hero">
  <div class="text-center py-2 bg-danger text-white fw-bold">
    50% OFF + Free SHIPPING TODAY ONLY
  </div>
  <div id="carouselBanners" class="carousel slide position-relative" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="/vision-site/images/banner 1.png" class="d-block w-100" alt="Banner 1">
        <div class="carousel-caption d-block text-center">
          <a href="/vision-site/categories.php" class="btn btn-danger">BUY NOW</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="/vision-site/images/banner 2.png" class="d-block w-100" alt="Banner 2">
        <div class="carousel-caption d-block text-center">
          <a href="/vision-site/categories.php" class="btn btn-danger">BUY NOW</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="/vision-site/images/banner_3.png" class="d-block w-100" alt="Banner 3">
        <div class="carousel-caption d-block text-center">
          <a href="/vision-site/categories.php" class="btn btn-danger">BUY NOW</a>
        </div>
      </div>
    </div>

    <!-- Carousel Arrows -->
    <button class="carousel-control-prev custom-carousel-control" type="button" data-bs-target="#carouselBanners" data-bs-slide="prev">
      <i class="fas fa-chevron-left fa-2x"></i>
    </button>
    <button class="carousel-control-next custom-carousel-control" type="button" data-bs-target="#carouselBanners" data-bs-slide="next">
      <i class="fas fa-chevron-right fa-2x"></i>
    </button>
  </div>
</section>

<?php include 'footer.php'; ?>
