<?php
include '../../koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
  echo "Donasi tidak ditemukan (ID tidak diberikan).";
  exit;
}

$id_donasi = intval($_GET['id']); // Gunakan id_donasi sebagai variabel utama

// Query donasi lengkap 
$query = $conn->query("SELECT d.*, k.jenis_kategori, m.nama_mitra  
    FROM donasi d 
    LEFT JOIN kategori_donasi k ON d.id_kategori = k.id_kategori
    LEFT JOIN mitra m ON d.id_mitra = m.id_mitra
    WHERE d.id_donasi = $id_donasi"); 

if (!$query || $query->num_rows == 0) {
  echo "Donasi tidak ditemukan (data kosong).";
  exit;
}

// Ambil data
$data = $query->fetch_assoc();
$judul     = $data['judul_donasi'];
$tanggal   = date('d F Y', strtotime($data['tgl_unggah']));
$gambar    = $data['gambar'];
$isi       = $data['isi_donasi'];
$status    = $data['status_donasi'];
$bentuk    = $data['bentuk_donasi'];
$lembaga   = $data['nama_mitra'] ?? 'Tidak Diketahui';

// Ambil paket jika bentuk donasi adalah Barang
$pakets = [];
if ($bentuk === 'Barang') {
  $paketResult = $conn->query("SELECT * FROM donasi_barang WHERE id_donasi = $id_donasi");
  while ($p = $paketResult->fetch_assoc()) {
    $pakets[] = $p;
  }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donasi Bencana</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    <?php include '../../index-style.css'; ?>
  </style>
</head>

<body>
  <div id="notifikasi" class="alert alert-dismissible fade show position-fixed w-100" style="display: none; top: 90px; z-index: 1000; border-radius: 0; text-align: center;"></div>

  <div class="donation-container">
    <div class="donation-header">
      <div class="container">
        <div class="row">
          <div class="col-md-6 mb-4 mb-md-0">
            <h1 class="display-5 fw-bold"><?= htmlspecialchars($judul) ?></h1>
            <p class="lead"><i class="fas fa-calendar-alt me-2"></i><?= $tanggal ?></p>
          </div>
          <div class="col-md-6">
            <img src="<?= $gambar ?>" alt="<?= htmlspecialchars($judul) ?>" class="donation-image w-100">
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="organization-card">
        <div class="d-flex align-items-center">
          <img src="images/gilang.png" class="profile-img me-4">
          <div>
            <h3 class="mb-1"><?= $lembaga ?></h3>
            <span class="status-badge"><?= $status ?></span>
            <div class="mt-3 fs-5 fw-bold text-primary">
              <i class="fas fa-donate me-2"></i>
              <?php
              $getTerkumpul = $conn->query("SELECT SUM(terkumpul_paket * harga_paket) as total FROM donasi_barang WHERE id_donasi = $id_donasi");
              $jumlah = $getTerkumpul->fetch_assoc()['total'] ?? 0;
              echo 'Rp ' . number_format($jumlah, 0, ',', '.');
              ?>
              terkumpul
            </div>
          </div>
        </div>
      </div>

      <div class="donation-content mt-4">
        <div class="mb-4">
          <h3 class="mb-3">Tentang Bencana</h3>
          <p class="lead"><?= nl2br(htmlspecialchars($isi)) ?></p>
        </div>

        <h3 class="mb-4">Pilihan Paket Barang</h3>
        <?php foreach ($pakets as $i => $paket): ?>
          <div class="donation-item">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-1"><?= htmlspecialchars($paket['jenis_paket']) ?> - Rp <?= number_format($paket['harga_paket'], 0, ',', '.') ?></h5>
                <p class="mb-0 text-muted">Target: <?= $paket['target_paket'] ?> paket</p>
              </div>
              <div class="quantity-control">
                <button class="quantity-btn" onclick="updateQuantity('paket<?= $i ?>', -1)">-</button>
                <span class="quantity-display" id="paket<?= $i ?>" data-harga="<?= $paket['harga_paket'] ?>">0</span>
                <button class="quantity-btn" onclick="updateQuantity('paket<?= $i ?>', 1)">+</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="py-3 border-top border-bottom mt-4">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Total Donasi:</h4>
            <h3 class="mb-0 text-primary">Rp <span id="totalAmount">0</span></h3>
          </div>
        </div>

        <div class="mt-5">
          <h3 class="mb-4">Pilih Metode Pembayaran</h3>
          <div class="d-flex flex-wrap gap-3">
            <img src="../../images/bni.png" alt="BNI" class="payment-method" data-method="BNI">
            <img src="../../images/bri.png" alt="BRI" class="payment-method" data-method="BRI">
            <img src="../../images/bca.png" alt="BCA" class="payment-method" data-method="BCA">
            <img src="../../images/mandiri.png" alt="Mandiri" class="payment-method" data-method="Mandiri">
            <img src="../../images/gopay.png" alt="Gopay" class="payment-method" data-method="Gopay">
            <img src="../../images/dana.png" alt="Dana" class="payment-method" data-method="Dana">
            <img src="../../images/shopeepay.png" alt="ShopeePay" class="payment-method" data-method="ShopeePay">
          </div>
        </div>

        <button class="donate-btn mt-5 py-3" onclick="prepareAndSubmit()">
          <i class="fas fa-donate me-2"></i>Donasikan Sekarang
        </button>
      </div>
    </div>
  </div>

  <script>
    function updateQuantity(id, change) {
      const el = document.getElementById(id);
      const harga = parseInt(el.dataset.harga);
      let qty = Math.max(0, parseInt(el.textContent) + change);
      el.textContent = qty;
      updateTotal();
    }

    function updateTotal() {
      let total = 0;
      document.querySelectorAll('.quantity-display').forEach(el => {
        const harga = parseInt(el.dataset.harga);
        const qty = parseInt(el.textContent);
        total += harga * qty;
      });
      document.getElementById('totalAmount').textContent = total.toLocaleString('id-ID');
    }

    let selectedPayment = "";
    document.querySelectorAll('.payment-method').forEach(img => {
      img.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(el => {
          el.classList.remove('selected');
          el.style.transform = 'scale(1)';
        });
        this.classList.add('selected');
        this.style.transform = 'scale(1.05)';
        selectedPayment = this.dataset.method;
      });
    });

    function prepareAndSubmit() {
      const paketData = [];
      document.querySelectorAll('.quantity-display').forEach((el, index) => {
        paketData.push({
          id: index,
          qty: parseInt(el.textContent)
        });
      });

      if (paketData.every(p => p.qty === 0)) {
        tampilkanNotifikasi("Minimal pilih satu paket!", true);
        return;
      }

      if (!selectedPayment) {
        tampilkanNotifikasi("Pilih metode pembayaran.", true);
        return;
      }

      const total = parseInt(document.getElementById('totalAmount').textContent.replace(/\./g, ''));
      const formData = new FormData();
      formData.append('id_donasi', <?= $id ?>);
      formData.append('metode', selectedPayment);
      formData.append('total', total);
      formData.append('paketData', JSON.stringify(paketData));

      fetch('proses_transaksi_barang.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(result => tampilkanNotifikasi(result, !result.includes("berhasil")))
        .catch(err => tampilkanNotifikasi("Gagal kirim data", true));
    }

    function tampilkanNotifikasi(pesan, isError) {
      const notif = document.getElementById('notifikasi');
      notif.style.display = 'block';
      notif.textContent = pesan;
      notif.style.backgroundColor = isError ? '#f8d7da' : '#d4edda';
      notif.style.color = isError ? '#721c24' : '#155724';
      notif.style.border = isError ? '1px solid #f5c6cb' : '1px solid #c3e6cb';
      setTimeout(() => notif.style.display = 'none', 5000);
    }
  </script>
</body>

</html>