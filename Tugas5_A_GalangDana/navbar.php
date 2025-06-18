<?php
// It's good practice to have session_start() at the very top of your file.
session_start();
?>

<!-- GILANGGGGGGGGGGGGGGGGG -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a href="index.php" class="navbar-brand d-flex align-items-center">
      <img src="images/logo-navbar.png" alt="Logo" width="160" height="auto" class="logo-hover">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php#about-us">About Us</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="index.php#how-to-donate">How to Donate</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php#documentation">Documentation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="pages/donasi/form-donasi.php">Form Donate</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="form-documentation.php">Form Documentation</a>
        </li>
      </ul>



      <?php // --- START OF CORRECTED LOGIC --- 
      ?>
      <?php if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true && isset($_SESSION['nama'])): ?>
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['nama']); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li>
              <h6 class="dropdown-header">Halo, <?= htmlspecialchars($_SESSION['nama']); ?>!</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="pages/user/profile.php"><i class="fas fa-user-edit me-2"></i> Profile</a></li>
            <li><a class="dropdown-item" href="laporan.php"><i class="fas fa-file-alt me-2"></i> Laporan</a></li>
            <li><a class="dropdown-item text-danger" href="pages/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </div>
      <?php else: ?>
        <div class="d-flex">
          <a class="btn btn-login me-2" href="pages/auth/Login.php">
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </a>
          <a class="btn btn-signup" href="pages/auth/SignupUser.php">
            <i class="fas fa-user-plus me-2"></i>Sign Up
          </a>
        </div>
      <?php endif; ?>
      <?php // --- END OF CORRECTED LOGIC --- 
      ?>

    </div>
  </div>
</nav>