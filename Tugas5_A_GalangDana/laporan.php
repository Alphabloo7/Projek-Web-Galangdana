<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Laporan</title>
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

    .card-body {
        background: url(images/bg4.png) no-repeat center center/cover !important;
        border-radius: 5px;
        border: none;
    }
    }
    </style>
</head>

<body class="bg-light py-5">
  <div class="container mt-5" style="max-width: 800px;">
    <div class="card shadow-lg">
      <div class="card-body">
        <h3 class="text-center text-primary">Form Laporan</h3>
        <p class="text-center text-muted mb-4">Silahkan isi data laporan dengan lengkap!</p>
        <form id="reportForm" action="proses_laporan.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-bold">Judul Laporan</label>
            <input type="text" name="judul_laporan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Tanggal Pengajuan Laporan</label>
            <input type="date" name="tgl_laporan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Isi Laporan</label>
            <textarea name="isi_laporan" class="form-control" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Bukti Laporan</label>
            <input type="file" name="bukti_laporan" class="form-control">
          </div>
            <div class="d-flex justify-content-between">
            <input type="hidden" name="status_laporan" value="Pending">
            <a href="index2.php" class="btn btn-secondary">Kembali</a>
            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>