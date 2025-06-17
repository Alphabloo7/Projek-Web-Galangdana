<?php
session_start();
include 'koneksi.php';
include 'pages/auth/keamanan.php';

$id_user = $_SESSION['id_user'];
$nama = $_SESSION['nama'];

$query = "SELECT * FROM laporan WHERE id_user = ? ORDER BY id_laporan DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Laporan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('images/bg6.jpg');
      background-repeat: no-repeat;
      background-position: center center;
      background-size: cover;
      background-attachment: fixed;
      min-height: 100vh;
      padding-bottom: 50px;
    }

    .card-body {
      background-color: #ceebfd;
      border-radius: 10px;
      padding: 30px;
    }

    .table thead {
      background-color: #0d6efd;
      color: white;
    }

    .table tbody tr:hover {
      background-color: #f1f9ff;
    }

    .btn {
      border-radius: 8px;
    }
  </style>
</head>
<body class="bg-light py-5">
  <div class="container mt-5" style="max-width: 900px;">
    <div class="card shadow-lg">
      <div class="card-body">
        <h3 class="text-center text-primary mb-4">Riwayat Laporan Anda</h3>

        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Isi Laporan</th>
                <th>Status</th>
                <th>Bukti</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['judul_laporan']) ?></td>
                <td><?= date('d M Y', strtotime($row['tgl_laporan'])) ?></td>
                <td class="text-start"><?= nl2br(htmlspecialchars($row['isi_laporan'])) ?></td>
                <td>
                  <span class="badge bg-<?= $row['status_laporan'] === 'Pending' ? 'warning text-dark' : 'success' ?>">
                    <?= $row['status_laporan'] ?>
                  </span>
                </td>
                <td>
                  <?php if ($row['bukti_laporan']): ?>
                    <a href="uploads_bukti/<?= $row['bukti_laporan'] ?>" 
                        target="_blank" 
                        class="btn btn-sm text-white" 
                        style="background-color: #0d6efd;">Lihat</a>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <div class="d-flex justify-content-between">
        <a href="index2.php" class="btn btn-secondary">Kembali</a>
        </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">Belum ada laporan yang Anda buat.</div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</body>
</html>
