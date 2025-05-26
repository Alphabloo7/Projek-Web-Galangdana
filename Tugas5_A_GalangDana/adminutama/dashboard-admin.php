<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="dashboard-admin.css">

</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <nav class="navbar bg-light mb-4">
      <div class="container-fluid">
        <h4 class="mb-0">Admin Dashboard</h4>
      </div>
    </nav>

    <section id="dashboard">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Total Donations</h5>
            <h2 class="text-primary">Rp 25.4 JT</h2>
            <small class="text-muted">+12% dari bulan lalu</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Kampanye Aktif</h5>
            <h2 class="text-success">15</h2>
            <small class="text-muted">5 kampanye baru</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Pengguna Terdaftar</h5>
            <h2 class="text-warning">1.2 RB</h2>
            <small class="text-muted">+58 pengguna baru</small>
          </div>
        </div>
      </div>

      <div class="row mt-4 g-4">
        <div class="col-md-8">
          <div class="stat-card">
            <h5>Statistik Donasi</h5>
            <canvas id="donationChart"></canvas>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Distribusi Donasi</h5>
            <canvas id="donationDistribution"></canvas>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('donationChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
        datasets: [{
          label: 'Total Donasi',
          data: [65, 59, 80, 81, 56, 55],
          borderColor: '#3498db',
          tension: 0.4
        }]
      }
    });

    const pieChart = document.getElementById('donationDistribution').getContext('2d');
    new Chart(pieChart, {
      type: 'pie',
      data: {
        labels: ['Bencana', 'Pendidikan', 'Kesehatan', 'Lainnya'],
        datasets: [{
          data: [45, 30, 15, 10],
          backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f1c40f']
        }]
      }
    });
  </script>
</body>

</html>





























<!-- Sidebar 
  <div class="sidebar p-3">
    <h3 class="text-center mb-4">
      <img src="logo-dashboard.png" alt="Logo" width="150">
    </h3>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white active" href="#dashboard">
          <i class="fas fa-home me-2"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="donations.php">
          <i class="fas fa-donate me-2"></i> Donations
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="documentation.php">
          <i class="fas fa-file-alt me-2"></i> Documentation
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="user-management.php">
          <i class="fa-solid fa-address-card"></i> User Management
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="comments.php">
          <i class="fa-solid fa-comment"></i> Comment
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="report.php">
          <i class="fa-solid fa-flag"></i> Report
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="btn btn-danger" href="../../index.php">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
      </li>
    </ul>
  </div>

  Main Content 
  <div class="main-content">

    Top Nav 

    <nav class="navbar bg-light mb-4">
      <div class="container-fluid">
        <button class="btn btn-primary d-md-none" id="sidebarToggle">
          <i class="fas fa-bars"></i>
        </button>
        <h4 class="mb-0">Admin Dashboard</h4>
      </div>
    </nav>

    Dashboard Section 
    
    <section id="dashboard">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Total Donations</h5>
            <h2 class="text-primary">Rp 25.4 JT</h2>
            <small class="text-muted">+12% dari bulan lalu</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Kampanye Aktif</h5>
            <h2 class="text-success">15</h2>
            <small class="text-muted">5 kampanye baru</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Pengguna Terdaftar</h5>
            <h2 class="text-warning">1.2 RB</h2>
            <small class="text-muted">+58 pengguna baru</small>
          </div>
        </div>
      </div>

      <div class="row mt-4 g-4">
        <div class="col-md-8">
          <div class="stat-card">
            <h5>Statistik Donasi</h5>
            <canvas id="donationChart"></canvas>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <h5>Distribusi Donasi</h5>
            <canvas id="donationDistribution"></canvas>
          </div>
        </div>
      </div>
    </section>

    Donations Section 

    <section id="donations" class="d-none">
      <div class="container">
        <h2 class="display-4 fw-bold mb-4">Manage Donations</h2>

        <div class="row row-cols-1 row-cols-md-3 g-4">
          <div class="col">
            <div class="card shadow-sm">
              <img src="images/OpenDonation1.png" class="card-img-top fixed-size-img" alt="Banjir Bandung">
              <div class="card-body">
                <h5 class="card-title">Banjir di Kabupaten Bandung</h5>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-success">Edit</button>
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </div>
                  <small class="text-muted">Active</small>
                </div>
              </div>
            </div>
          </div>
          Add more donation cards
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Donation
          </button>
        </div>
      </div>
    </section>

    Documentation Section 

    <section id="documentation" class="d-none">
      <div class="container">
        <h2 class="display-4 fw-bold mb-4">Manage Documentation</h2>

        <div class="row row-cols-1 row-cols-md-2 g-4">
          <div class="col">
            <div class="card shadow-sm">
              <img src="images/banjirSemarang.jpeg" class="card-img-top fixed-size-img" alt="Documentation">
              <div class="card-body">
                <h5 class="card-title">Banjir Semarang</h5>
                <p class="card-text short-text">Donasi telah tersalurkan untuk bencana banjir di kota Semarang...</p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-success">Edit</button>
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </div>
                  <small class="text-muted">18 Maret 2022</small>
                </div>
              </div>
            </div>
          </div>
          Add more documentation cards 
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Documentation
          </button>
        </div>
      </div>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Navigation System
    function handleNavigation() {
      const sections = {
        dashboard: document.getElementById('dashboard'),
        donations: document.getElementById('donations'),
        documentation: document.getElementById('documentation')
      };

      document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          const target = link.getAttribute('href').substring(1);

          // Update active class
          document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
          link.classList.add('active');

          // Toggle sections
          Object.values(sections).forEach(section => section.classList.add('d-none'));
          document.getElementById(target).classList.remove('d-none');
        });
      });
    }

    // Initialize Charts
    function initCharts() {
      const ctx = document.getElementById('donationChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
          datasets: [{
            label: 'Total Donasi',
            data: [65, 59, 80, 81, 56, 55],
            borderColor: '#3498db',
            tension: 0.4
          }]
        }
      });

      const pieChart = document.getElementById('donationDistribution').getContext('2d');
      new Chart(pieChart, {
        type: 'pie',
        data: {
          labels: ['Bencana', 'Pendidikan', 'Kesehatan', 'Lainnya'],
          datasets: [{
            data: [45, 30, 15, 10],
            backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f1c40f']
          }]
        }
      });
    }

    // Toggle Sidebar
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      document.querySelector('.sidebar').classList.toggle('active');
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      handleNavigation();
      initCharts();

      // Show dashboard by default
      document.getElementById('dashboard').classList.remove('d-none');
    });
  </script>
</body>

</html> -->