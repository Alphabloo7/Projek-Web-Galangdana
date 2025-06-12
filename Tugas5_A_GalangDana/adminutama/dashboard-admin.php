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





























