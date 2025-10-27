<?php
include('config.php');

// ✅ Start session at the very top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<header class="header_sticky" id="mainHeader">
  <nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid">
      <!-- Brand / Logo -->
     <a href="<?= $base_url ?>index.php" class="navbar-brand d-flex flex-column align-items-center text-center">
      <img src="<?= $base_url ?>img/s4smartshop.png" alt="S4 Smart Shop Logo" class="logo-img" id="logoImg">
      <span class="brand-name mt-1">S4 Smart Shop</span>
    </a>



      <!-- Mobile Toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu Items -->
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item"><a href="<?= $base_url ?>index.php" class="nav-link">Home</a></li>

          <!--<li class="nav-item dropdown">-->
          <!--  <a href="#" class="nav-link dropdown-toggle" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">-->
          <!--    About-->
          <!--  </a>-->
          <!--  <ul class="dropdown-menu" aria-labelledby="aboutDropdown">-->
          <!--    <li><a class="dropdown-item" href="<?= $base_url ?>mission.php">Mission & Vision</a></li>-->
          <!--    <li><a class="dropdown-item" href="<?= $base_url ?>history.php">History</a></li>-->
          <!--  </ul>-->
          <!--</li>-->
          <li class="nav-item"><a href="<?= $base_url ?>shop.php" class="nav-link">Shop</a></li>
          <li class="nav-item"><a href="<?= $base_url ?>our-process.php" class="nav-link">Our Process</a></li>
          <li class="nav-item"><a href="<?= $base_url ?>contact.php" class="nav-link">Contact Us</a></li>
        </ul>
      </div>

      <!-- Right Side (User / Login) -->
      <div class="d-flex align-items-center">
        <?php if (!empty($_SESSION['user']['name'])): ?>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION['user']['name']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="">Profile</a></li>
              <li><a class="dropdown-item" href="">Wishlist</a></li>
              <li><a class="dropdown-item" href="">Notifications</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= $base_url ?>login.php?action=logout">Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= $base_url ?>login.php" class="btn-hero">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>


<script>
  window.addEventListener("scroll", () => {
    const header = document.getElementById("mainHeader");
    header.classList.toggle("shrink", window.scrollY > 50);
  });
</script>
