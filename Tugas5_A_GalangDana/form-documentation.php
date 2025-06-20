<?php
session_start();
include 'koneksi.php';
include 'pages/auth/keamanan.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Ajuan Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('images/bg6.jpg');
      background-repeat: no-repeat;
      background-position: center center;
      background-size: cover;
      background-attachment: fixed;
      min-height: 100vh;
    }
    .card-body{
      background: url(images/bg4.png) no-repeat center center/cover !important;
      border-radius: 5px;
      border: none;
    }
  </style>
</head>

<body class="bg-light py-5">
  <div class="container mt-5" style="max-width: 800px;">
    <div class="card shadow-lg">
      <div class="card-body">
        <h3 class="text-center text-primary">Form Dokumentasi</h3>
        <p class="text-center text-muted mb-4">Silakan isi data dokumentasi donasi dengan lengkap!</p>

        <form action="proses_donasi.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Judul Dokumentasi Penyaluran Donasi</label>
            <input type="text" name="judul_donasi" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Peluncuran Donasi</label>
            <input type="date" name="tgl_unggah" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Isi Deskripsi Donasi</label>
            <textarea name="isi_donasi" class="form-control" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Gambar Donasi</label>
            <input type="file" name="gambar" class="form-control">
          </div>

          <div class="mb-3">
            <label for="kategori" class="form-label fw-bold">Kategori Donasi</label>
            <select id="kategori" name="id_kategori" class="form-select" required>
              <option value="" disabled selected>Pilih Kategori</option>
              <option value="1">Bencana Alam</option>
              <option value="2">Sosial</option>
              <option value="3">Pendidikan</option>
              <option value="4">Kesehatan</option>
              <option value="5">Lingkungan</option>
              <option value="6">Keagamaan</option>
            </select>
          </div>
          <div class="d-flex justify-content-between">
            <input type="hidden" name="status" value="Non Active">

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