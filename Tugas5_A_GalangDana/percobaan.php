<?php
// PHP logic from the second file to fetch data
include 'koneksi.php';
    require_once 'keamanan.php';  
     include 'navbar.php';
// Assuming 'keamanan.php' handles session checks or other security measures


// Initialize $data to prevent errors if the ID is not found
$data = null; 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM donasi WHERE id_donasi = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        // Use a more user-friendly error display within the page layout
        $error_message = "Donasi tidak ditemukan.";
    }
} else {
    $error_message = "ID donasi tidak valid atau tidak diberikan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Set title dynamically or show a default if data not found -->
  <title><?= $data ? htmlspecialchars($data['judul_donasi']) : 'Donasi Tidak Ditemukan' ?> - Donasi Bencana</title>
  
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="index-style.css">
    <style>
    body {
        font-family: 'DM Sans', sans-serif;
    }
    .donation-container {
      padding-top: 0px;
      background-color: #f8f9fa;
      min-height: 100vh;
    }
    .donation-header {
      background-color: #003366;
      color: white;
      padding: 4rem 0;
      position: relative;
      overflow: hidden;
    }
    .donation-image {
      height: 400px;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .organization-card {
      background: white;
      border-radius: 8px;
      padding: 1.5rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-top: -100px;
      position: relative;
      z-index: 10;
    }
    .profile-img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .donation-content {
      background: white;
      border-radius: 8px;
      padding: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-top: 2rem;
    }
    .payment-method {
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      border: 3px solid transparent;
      border-radius: 8px;
      padding: 5px;
      height: 50px;
      width: auto;
    }
    .payment-method:hover {
      transform: scale(1.05);
    }
    .payment-method.selected {
      border-color: #2196F3;
      box-shadow: 0 0 10px rgba(33, 150, 243, 0.5);
    }
    .donate-btn {
      background: #003366;
      color: white;
      border: none;
      padding: 12px;
      font-weight: 500;
      border-radius: 8px;
      transition: background 0.3s;
      width: 100%;
    }
    .donate-btn:hover {
      background: #002244;
    }
    .status-badge {
      background: #28a745;
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    .form-control-lg {
        font-size: 1.5rem;
        text-align: center;
    }
  </style>
</head>

<body>


  <div id="notifikasi" class="alert alert-dismissible fade show position-fixed w-100" style="
    display: none;
    top: 90px;
    z-index: 1000;
    border-radius: 0;
    text-align: center;
  "></div>

<?php if ($data): // Only show the main content if data was found ?>
  <div class="donation-container">
    <div class="donation-header">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 mb-4 mb-md-0">
            <h1 class="display-5 fw-bold"><?= htmlspecialchars($data['judul_donasi']) ?></h1>
            <p class="lead"><i class="fas fa-calendar-alt me-2"></i>Diunggah pada <?= date('d F Y', strtotime($data['tgl_unggah'])) ?></p>
            <p class="lead"><i class="fa-solid fa-bullseye me-2"></i>Status: <span class="fw-bold"><?= htmlspecialchars($data['status_donasi']) ?></span></p>
          </div>
          <div class="col-md-6">
            <?php if (!empty($data['gambar'])): ?>
              <img src="<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar Donasi" class="donation-image w-100">
            <?php else: ?>
              <img src="images/default-donation.png" alt="Tidak ada gambar" class="donation-image w-100">
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container">
      <div class="organization-card">
        <div class="d-flex align-items-center">
          <div>

            <div class="fs-5 fw-bold text-danger">
              <i class="fas fa-coins me-2"></i>Target Donasi: Rp <?= number_format($data['target_donasi'], 0, ',', '.') ?>
            </div>
          </div>
        </div>
      </div>
      
      <div class="donation-content mt-4">
        <form action="proses_transaksi.php" method="POST" onsubmit="return validateDonation();">
          <!-- Hidden inputs for form processing -->
          <input type="hidden" name="id_donasi" value="<?= $data['id_donasi'] ?>">
          <input type="hidden" name="metode" id="metode_pembayaran" required>
        
          <div class="mb-4">
            <h3 class="mb-3">Tentang Penggalangan Dana</h3>
            <p class="lead" style="white-space: pre-wrap;"><?= htmlspecialchars($data['isi_donasi']) ?></p>
            <p><strong>Bentuk Donasi yang Dibutuhkan:</strong> <?= htmlspecialchars($data['bentuk_donasi']) ?></p>
          </div>
          
          <div class="py-4 border-top border-bottom mt-4">
            <h3 class="mb-3 text-center">Masukkan Nominal Donasi Anda</h3>
            <div class="input-group input-group-lg">
                <span class="input-group-text">Rp</span>
                <input type="number" name="total" class="form-control" placeholder="50.000" min="1000" required>
            </div>
          </div>
          
          <div class="mt-5">
            <h3 class="mb-4">Pilih Metode Pembayaran</h3>
            <div class="d-flex flex-wrap justify-content-center gap-3">
              <img src="images/bni.png" alt="BNI" class="payment-method" data-method="BNI">
              <img src="images/bri.png" alt="BRI" class="payment-method" data-method="BRI">
              <img src="images/bca.png" alt="BCA" class="payment-method" data-method="BCA">
              <img src="images/mandiri.png" alt="Mandiri" class="payment-method" data-method="Mandiri">
              <img src="images/gopay.png" alt="Gopay" class="payment-method" data-method="Gopay">
              <img src="images/dana.png" alt="Dana" class="payment-method" data-method="Dana">
              <img src="images/shopeepay.png" alt="ShopeePay" class="payment-method" data-method="ShopeePay">
            </div>
          </div>
          
          <button type="submit" class="donate-btn mt-5 py-3 fs-5">
            <i class="fas fa-donate me-2"></i>Donasikan Sekarang
          </button>
        </form>
      </div>
    </div>
  </div>
<?php else: // Show an error message if data was not found ?>
    <div class="container" style="padding-top: 150px; text-align: center;">
        <div class="alert alert-danger">
            <h2 class="alert-heading">Terjadi Kesalahan</h2>
            <p><?= $error_message ?></p>
            <hr>
            <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
        </div>
    </div>
<?php endif; ?>

<script>
    // Event listener untuk metode pembayaran
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentInput = document.getElementById('metode_pembayaran');

    paymentMethods.forEach(img => {
        img.addEventListener('click', function() {
            // Reset semua style
            paymentMethods.forEach(el => {
                el.classList.remove('selected');
            });
            
            // Aktifkan yang dipilih
            this.classList.add('selected');
            paymentInput.value = this.dataset.method; // Set value untuk hidden input
        });
    });

    // Fungsi validasi form sebelum submit
    function validateDonation() {
        if (!paymentInput.value) {
            tampilkanNotifikasi("Silakan pilih metode pembayaran terlebih dahulu.", true);
            return false; // Mencegah form untuk submit
        }
        // Validasi lain (seperti jumlah) sudah ditangani oleh atribut `required` dan `min` pada input
        return true; // Lanjutkan submit form
    }

    // Fungsi notifikasi (diambil dari template)
    function tampilkanNotifikasi(pesan, isError) {
        const notif = document.getElementById('notifikasi');
        notif.style.display = 'block';
        notif.textContent = pesan;
        notif.className = 'alert alert-dismissible fade show position-fixed w-100'; // reset classes
        if (isError) {
            notif.classList.add('alert-danger');
        } else {
            notif.classList.add('alert-success');
        }
        
        // Auto-hide a notification
        setTimeout(() => {
            notif.style.display = 'none';
        }, 5000);
    }
</script>
</body>
</html>