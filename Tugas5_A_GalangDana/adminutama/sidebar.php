<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
</head>

<body>
    <!-- sidebar.php -->
    <div class="sidebar p-3">
        <h3 class="text-center mb-4">
            <img src="logo-dashboard.png" alt="Logo" width="150">
        </h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard-admin.php' ? 'active' : ''; ?>" href="dashboard-admin.php">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'donations.php' ? 'active' : ''; ?>" href="donations.php">
                    <i class="fas fa-donate me-2"></i> Donations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'documentations.php' ? 'active' : ''; ?>" href="documentations.php">
                    <i class="fas fa-file-alt me-2"></i> Documentation
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'user-management.php' ? 'active' : ''; ?>" href="user-management.php">
                    <i class="fa-solid fa-address-card"></i> User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'comments.php' ? 'active' : ''; ?>" href="comments.php">
                    <i class="fa-solid fa-comment"></i> Comment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'report.php' ? 'active' : ''; ?>" href="report.php">
                    <i class="fa-solid fa-flag"></i> Report
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="btn btn-danger" href="../index.php"> 
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

</body>

</html>