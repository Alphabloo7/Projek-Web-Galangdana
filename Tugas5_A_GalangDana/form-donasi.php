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
        padding-bottom: 50px;
    }

    .card-body {
        background: url(images/bg4.png) no-repeat center center/cover !important;
        border-radius: 5px;
        border: none;
    }
    </style>
</head>

<body>
    <div class="container mt-5" style="max-width: 800px;">
        <div class="card shadow-lg">
            <div class="card-body">
                <h3 class="text-center text-primary">Form Donasi</h3>
                <p class="text-center text-muted mb-4">Silakan isi data donasi dengan lengkap!</p>

        <form id="form-donasi" action="proses_donasi.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Judul Donasi</label>
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
                        <div class="form-text">Rekomendasi ukuran: 400x300 piksel. Gambar akan dicrop otomatis untuk
                            tampilan seragam.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bentuk Donasi</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="bentuk_donasi" id="donasi_uang"
                                value="uang" required>
                            <label class="form-check-label" for="donasi_uang">Uang</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="bentuk_donasi" id="donasi_barang"
                                value="barang">
                            <label class="form-check-label" for="donasi_barang">Barang</label>
                        </div>
                    </div>

                    <!-- Form Uang -->
                    <div id="form-uang" class="mb-3 d-none">
                        <label for="jumlah_uang" class="form-label">Jumlah Uang yang Dikumpulkan (Rp)</label>
                        <input type="number" class="form-control" name="target_donasi" id="jumlah_uang"
                            placeholder="Contoh: 10000000">
                    </div>

                    <!-- Form Barang -->
                    <div id="form-barang" class="mb-3 d-none">
                        <label class="form-label">Pilih Paket Barang</label>
                        <select class="form-select mb-3" id="paket_select">
                            <option value="">-- Pilih Paket --</option>
                            <option value="biasa">Paket Biasa (2 Barang)</option>
                            <option value="istimewa">Paket Istimewa (3 Barang)</option>
                        </select>

                        <div id="barang-fields"></div>

                        <div class="mt-3">
                            <label class="form-label">Ringkasan Barang</label>
                            <input type="text" class="form-control" name="daftar_barang" id="daftar_barang" readonly>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Ringkasan Harga</label>
                            <input type="text" class="form-control" name="daftar_harga" id="daftar_harga" readonly>
                        </div>
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
  <!-- AJAX Script -->
  <script>
    const form = document.getElementById('form-donasi');

    form.addEventListener('submit', function(e) {
      e.preventDefault(); // cegah submit biasa

      const formData = new FormData(form);

      fetch('proses_donasi.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json()) // anggap server balas JSON
        .then(data => {
          if (data.success) {
            // redirect ke landing page jika berhasil
            window.location.href = 'index2.php';
          } else {
            alert('Gagal submit donasi: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(error => {
          alert('Error saat submit: ' + error);
        });
    });
  </script>
</body>
<script>
const radioUang = document.getElementById('donasi_uang');
const radioBarang = document.getElementById('donasi_barang');
const formUang = document.getElementById('form-uang');
const formBarang = document.getElementById('form-barang');
const paketSelect = document.getElementById('paket_select');
const barangFields = document.getElementById('barang-fields');
const daftarBarang = document.getElementById('daftar_barang');
const daftarHarga = document.getElementById('daftar_harga');

function updateFormVisibility() {
    formUang.classList.add('d-none');
    formBarang.classList.add('d-none');
    if (radioUang.checked) {
        formUang.classList.remove('d-none');
    } else if (radioBarang.checked) {
        formBarang.classList.remove('d-none');
    }
}

radioUang.addEventListener('change', updateFormVisibility);
radioBarang.addEventListener('change', updateFormVisibility);

paketSelect.addEventListener('change', function() {
    const jumlah = this.value === 'istimewa' ? 3 : this.value === 'biasa' ? 2 : 0;
    barangFields.innerHTML = '';
    daftarBarang.value = '';
    daftarHarga.value = '';

    for (let i = 1; i <= jumlah; i++) {
        const row = document.createElement('div');
        row.classList.add('mb-2', 'row');
        row.innerHTML = `
        <div class="col-md-6">
          <input type="text" class="form-control barang-nama" placeholder="Nama Barang ${i}">
        </div>
        <div class="col-md-6">
          <input type="number" class="form-control barang-harga" placeholder="Harga Barang ${i}">
        </div>
      `;
        barangFields.appendChild(row);
    }

    barangFields.querySelectorAll('.barang-nama, .barang-harga').forEach(input => {
        input.addEventListener('input', updateRingkasan);
    });
});

function updateRingkasan() {
    const namaList = Array.from(document.querySelectorAll('.barang-nama'))
        .map(input => input.value.trim())
        .filter(Boolean);

    const hargaList = Array.from(document.querySelectorAll('.barang-harga'))
        .map(input => input.value.trim())
        .filter(Boolean);

    daftarBarang.value = namaList.join(', ');
    daftarHarga.value = hargaList.join(', ');
}
</script>

</html>