<?php
// kosong, karena tidak ada logic PHP khusus di sini
?>
<style>
    .sidebar .nav-link.active {
        background-color: #00509e;
        color: #fff !important;
        font-weight: 700;
        border-radius: 4px;
    }

    .sidebar .nav-link:hover {
        background-color: #004080;
        color: white !important;
    }
</style>
<!-- sidebar.php -->
<div class="sidebar text-white position-fixed h-100" style="width: 250px; background-color: #003366;">
    <div class="text-center my-4">
        <img src="logo-dashboard.png" alt="Logo" width="150">
    </div>
    <ul class="nav flex-column px-3">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'dashboard-admin.php' ? 'active fw-bold' : ''; ?>" href="dashboard-admin.php">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <!-- Donations -->
        <li class="nav-item">
            <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'donations.php' ? 'active fw-bold' : ''; ?>" href="donations.php">
                <i class="fas fa-donate me-2"></i> Donations
            </a>
        </li>

        <!-- Documentation -->
        <li class="nav-item">
            <a href="documentations.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'documentations.php' ? 'active fw-bold' : ''; ?>">
                <i class="fas fa-file-alt me-2"></i> Documentation
            </a>
        </li>

        <!-- Donatur List -->
        <li class="nav-item">
            <a href="donatur-list.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'donatur-list.php' ? 'active fw-bold' : ''; ?>">
                <i class="fas fa-hand-holding-heart me-2"></i> Donatur List
            </a>
        </li>

        <!-- User Management -->
        <li class="nav-item">
            <a href="user-management.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'user-management.php' ? 'active fw-bold' : ''; ?>">
                <i class="fa-solid fa-address-card me-2"></i> User Management
            </a>
        </li>

        <!-- Comment -->
        <li class="nav-item">
            <a href="comments.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'comments.php' ? 'active fw-bold' : ''; ?>">
                <i class="fa-solid fa-comment me-2"></i> Comment
            </a>
        </li>

        <!-- Report -->
        <li class="nav-item">
            <a href="report.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'report.php' ? 'active fw-bold' : ''; ?>">
                <i class="fa-solid fa-flag me-2"></i> Report
            </a>
        </li>

        <!-- Logout -->
        <li class="nav-item mt-4">
            <a href="../index.php" class="btn btn-danger w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>

    </ul>
</div>